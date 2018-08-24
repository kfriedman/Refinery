<?php
namespace NYPL\Refinery;

use NYPL\Refinery\Cache\CacheClient;
use NYPL\Refinery\Cache\CacheData\CachedResponse;
use NYPL\Refinery\Config\Config;
use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\QueueMessage\URLRefresh;
use NYPL\Refinery\Server\Endpoint;
use NYPL\Refinery\Server\ServerOutputter;
use NYPL\Refinery\Server\ServerSystemRoutes;
use Slim\Slim;

/**
 * Creates the Refinery Server
 *
 * This as a static class that is primarily used to serve requests from the
 * Refinery REST API. It currently uses the Slim framework.
 *
 * @see http://www.slimframework.com/
 *
 * @package NYPL\Refinery
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Server
{
    const API_BASE_NAMESPACE = 'NYPL\\Refinery\\Server\\Endpoint\\api';

    /**
     * The Slim server.
     *
     * @var \Slim\Slim
     */
    public static $slim;

    /**
     * The debug mode status for the server. Will output debug information if
     * set to true.
     *
     * @var bool
     */
    private static $debug = false;

    /**
     * The performance tracking status for the server. Will track performance
     * information if set to true.
     *
     * @var bool
     */
    private static $performance = false;

    /**
     * Tracks whether the server's client requested a refresh (most likely
     * by hitting SHIFT-R).
     *
     * @var bool
     */
    private static $forceRefresh = false;

    /**
     * The priority for the refresh.
     *
     * @var int
     */
    private static $forceRefreshPriority = 0;

    /**
     * Tracks whether a cache refresh should be done if data is stale.
     *
     * @var bool
     */
    private static $refreshIfStale = false;

    /**
     * @var string
     */
    private static $manualCacheTtl = '';

    /**
     * Initialize the Refinery server.
     *
     * @throws RefineryException
     */
    protected static function initializeServer()
    {
        self::$slim = new Slim();

        if (self::$slim->request->post('debug') || (bool) Config::getItem('Server.Debug')) {
            self::setDebug(true);
            self::$slim->config('debug', true);
        }
        if (self::$slim->request->headers('Pragma') == 'no-cache') {
            self::setForceRefresh(true);
            self::setForceRefreshPriority(10);
        }

        NewRelicClient::nameTransaction(self::$slim->request->getResourceUri(), true);

        NewRelicClient::addParameter('request_ip', self::$slim->request->getIp());
        NewRelicClient::addParameter('request_query_string', http_build_query(self::$slim->request->get()));

        self::setPerformance((bool) Config::getItem('Server.Performance'));
    }

    /**
     * Run the Slim server, process the request URL, and output response.
     *
     * @param string $basePath The base URL path for the server
     * @throws RefineryException
     */
    public static function run($basePath = '')
    {
        self::initializeServer();

        self::$slim->get('/', function () {
            echo '<code>OKAY</code>';
        });

        self::$slim->get('/health', function () {
            NewRelicClient::ignoreTransaction();
            HealthChecker::run();
        });

        self::$slim->get('/health/lb', function () {
            NewRelicClient::ignoreTransaction();

            CacheClient::getInfo();

            echo '<code>OKAY</code>';
        });

        self::$slim->get('/health/:urlArray+', function () {
            NewRelicClient::ignoreTransaction();
            HealthChecker::run(false, false, true);
        });

        self::$slim->get('/system/cache/:pattern', function ($cacheKeyPattern) {
            ServerOutputter::outputAsJSON(ServerSystemRoutes::getCacheRoute(self::$slim->request, $cacheKeyPattern));
        });

        self::$slim->get('/system/clearqueue', function () {
            ServerSystemRoutes::getClearQueueRoute();
        });

        self::$slim->get('/system/testerror', function () {
            ServerSystemRoutes::testError();
        });

        self::$slim->get('/system/sleep/:pattern', function ($sleepTime) {
            ServerSystemRoutes::getLoadRoute($sleepTime);
        });

        self::$slim->get('/system/phpinfo', function () {
            ServerSystemRoutes::getPHPInfoRoute();
        });

        self::$slim->get('/system/info', function () {
            ServerOutputter::outputAsJSON(ServerSystemRoutes::getInfoRoute(), 0, true);
        });

        self::$slim->map('/' . $basePath . '/:urlArray+', function (array $urlArray) {
            try {
                $method = self::$slim->request->getMethod();

                self::checkAuthorization($method);

                if (self::isPerformance()) {
                    PerformanceTracker::initialize();
                }

                self::checkAPIURL($urlArray);

                $response = self::processRequest($urlArray, $method);

                ServerOutputter::outputFullResponse($response);
            } catch (RefineryException $exception) {
                if (isset($endpoint)) {
                    ServerOutputter::outputError($exception, $endpoint->getResponse());
                } else {
                    ServerOutputter::outputError($exception);
                }
            } catch (\Exception $exception) {
                ServerOutputter::outputError(
                    new RefineryException(
                        $exception->getMessage() . ' in ' . $exception->getFile() . ' on line ' . $exception->getLine()
                    )
                );
            }
        })->via('GET', 'POST', 'PUT', 'DELETE');

        self::$slim->run();
    }

    /**
     * Process the request from the Slim server.
     *
     * @param array  $urlArray        An array of the request URL.
     * @param string $method          The HTTP method used.
     * @param array  $queryParameters An array of query parameters.
     * @param bool   $isUrlRefresh    Is URL Refresh message.
     *
     * @return Endpoint\Response
     * @throws RefineryException
     */
    public static function processRequest(
        array $urlArray,
        $method = '',
        array $queryParameters = array(),
        $isUrlRefresh = false
    ) {
        if (self::$slim) {
            $queryParameters = self::$slim->request->get();
        }

        $cacheData = new CachedResponse(self::getCacheArrayFromURLParameters($urlArray, $queryParameters));

        if ($cacheData->isExists() &&
            !self::isForceRefresh() &&
            !self::isRefreshIfStale() &&
            (bool) Config::getItem('Cache.CachedResponse.Enabled')
        ) {
            /**
             * @var Endpoint\Response $response
             */
            $response = $cacheData->getData();

            $response->addMetaNotice(array('cache' => array(
                'last-refresh' => $cacheData->getDateAdded()->format('c')
            )));
        } else {
            $endpointClassNameAndID = self::getEndpointClassNameAndID($urlArray);

            /**
             * @var $endpoint Endpoint
             */
            $endpoint = self::getEndpointClass($endpointClassNameAndID['className']);

            self::checkEndpointMethodExists($endpoint, $method);

            $filter = new NDOFilter();

            self::initializeEndpointAndFilter(
                $endpoint,
                $filter,
                $endpointClassNameAndID['id'],
                $urlArray,
                $queryParameters
            );

            switch ($method) {
                case 'GET':
                    /**
                     * @var $endpoint Endpoint\GetInterface
                     */
                    $endpoint->get($filter);
                    break;

                case 'PUT':
                    $endpoint->setRawData(self::$slim->request->put());

                    /**
                     * @var $endpoint Endpoint\PutInterface
                     */
                    $endpoint->put($filter);
                    break;

                case 'POST':
                    $endpoint->setRawData(self::$slim->request->post());

                    /**
                     * @var $endpoint Endpoint\PostInterface
                     */
                    $endpoint->post();
                    break;
            }

            $response = $endpoint->getResponse();

            if ((self::isRefreshIfStale() || self::isForceRefresh()) && !$isUrlRefresh) {
                $response->addMetaNotice(array('cache' => array(
                    'last-refresh' => (($cacheData->getDateAdded()) ? $cacheData->getDateAdded()->format('c') : false),
                    'refresh-in-progress' => true
                )));

                DIManager::getQueueManager()->add(
                    new URLRefresh(self::getCacheArrayFromURLParameters($urlArray, $queryParameters, true)),
                    true,
                    0,
                    5
                );
            }

            $cacheData->save($response);
        }

        self::processPreFetching(
            $response,
            $isUrlRefresh,
            self::getCacheArrayFromURLParameters($urlArray, $queryParameters, true)
        );

        return $response;
    }

    /**
     * @param Endpoint\Response $response
     * @param bool              $isUrlRefresh
     * @param string            $uri
     */
    protected static function processPreFetching(Endpoint\Response $response, $isUrlRefresh = false, $uri = '')
    {
        if (!$isUrlRefresh) {
            $prefetchUri = '';

            $parsedUrl = parse_url($uri);

            if (isset($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], $query);

                if (isset($query['page']['number'])) {
                    ++$query['page']['number'];

                    $prefetchUri = $parsedUrl['path'] . '?' . http_build_query($query);
                }
            }

            if (!$prefetchUri && isset($response->getOtherTopLevel()['links']['next'])) {
                $prefetchUri = $response->getOtherTopLevel()['links']['next'];
            }

            if ($prefetchUri) {
                $nextLink = explode('/api/', $prefetchUri);

                DIManager::getQueueManager()->add(
                    new URLRefresh(array_pop($nextLink)),
                    true,
                    0,
                    5
                );
            }
        }
    }

    /**
     * Initialize the endpoint and filter using Slim request parameters.
     *
     * @param Endpoint  $endpoint        The endpoint to be initialized.
     * @param NDOFilter $filter          The filter to be initialized.
     * @param mixed     $endpointID      The endpoint ID to initialize the filter with, if any.
     * @param array     $urlArray        The URL array of the request.
     * @param array     $queryParameters An array of query parameters.
     */
    protected static function initializeEndpointAndFilter(
        Endpoint $endpoint,
        NDOFilter $filter,
        $endpointID = null,
        array $urlArray = null,
        array $queryParameters = array()
    ) {
        if (self::isDebug()) {
            $endpoint->setDebug(true);
        }

        if (isset($queryParameters['page'])) {
            if (isset($queryParameters['page']['size'])) {
                $filter->setPerPage((int) $queryParameters['page']['size']);
            }
            if (isset($queryParameters['page']['limit'])) {
                $endpoint->getResponse()->addMetaNotice(
                    'Deprecated page parameter (limit) specified - use "size" instead'
                );

                $filter->setPerPage((int) $queryParameters['page']['limit']);
            }
            if (isset($queryParameters['page']['number'])) {
                $filter->setPage((int) $queryParameters['page']['number']);
            }
        }
        if (isset($queryParameters['filter'])) {
            $filter->addQueryParameter('filter', $queryParameters['filter']);
        }
        if (isset($queryParameters['include'])) {
            $includeArray = explode(',', $queryParameters['include']);

            foreach ($includeArray as $includeName) {
                $filter->addInclude($includeName);
            }
        }
        if (isset($queryParameters['fields'])) {
            $filter->setFieldsArray($queryParameters['fields']);
        }

        $filter->setUrlArray(array_slice($urlArray, 3));

        if ($endpointID) {
            $filter->setFilterID($endpointID);
        }
    }

    /**
     * Check that the Endpoint implements the HTTP method requested.
     *
     * @param Endpoint $endpoint
     * @param string   $methodName
     *
     * @throws RefineryException
     */
    protected static function checkEndpointMethodExists(Endpoint $endpoint, $methodName = '')
    {
        if (!method_exists($endpoint, strtolower($methodName))) {
            throw new RefineryException(
                'Method (' . $methodName . ') is not supported by endpoint (' . get_class($endpoint) . ')',
                400
            );
        }
    }

    /**
     * Convert the version in the request to namespace-compatible version.
     *
     * @param string $urlVersion
     *
     * @return mixed
     */
    protected static function getVersionNamespace($urlVersion = '')
    {
        return str_replace('.', '_', $urlVersion);
    }

    /**
     * Create the Endpoint class name given the URL array.
     *
     * @param array $urlArray
     *
     * @return string
     */
    protected static function buildEndpointClassName(array $urlArray)
    {
        $lastPartOfArray = array_pop($urlArray);

        if ($urlArray) {
            return self::API_BASE_NAMESPACE . '\\' .
                implode('\\', $urlArray)  . '\\' . ucwords($lastPartOfArray) . 'Endpoint';

        } else {
            return self::API_BASE_NAMESPACE . '\\' . ucwords($lastPartOfArray) . 'Endpoint';
        }
    }

    /**
     * Convert URL parameters into an array while ignoring certain keys.
     *
     * @param array $urlArray
     * @param array $queryParameters
     * @param bool  $returnAsString
     *
     * @return array|string
     */
    protected static function getCacheArrayFromURLParameters(
        array $urlArray,
        array $queryParameters = array(),
        $returnAsString = false
    ) {
        if ($queryParameters) {
            $ignoreKeys = array('callback');

            foreach ($ignoreKeys as $ignoreKey) {
                if (isset($queryParameters[$ignoreKey])) {
                    unset($queryParameters[$ignoreKey]);
                }
            }
        }

        if ($returnAsString) {
            $urlString = implode('/', $urlArray);

            if ($queryParameters) {
                $urlString .= '?' . http_build_query($queryParameters);
            }

            return $urlString;
        } else {
            if ($queryParameters) {
                $urlArray[] = http_build_query($queryParameters);
            }

            return $urlArray;
        }
    }

    /**
     * Instantiate the Endpoint class given the Endpoint class name.
     *
     * @param string $endpointClassName
     *
     * @return Endpoint
     */
    protected static function getEndpointClass($endpointClassName)
    {
        return new $endpointClassName();
    }

    /**
     * Given the URL array, return the Endpoint class name that should be
     * instantiated as well as any ID that might be contained in the URL array.
     *
     * @param array $urlArray
     *
     * @return array
     * @throws RefineryException
     */
    protected static function getEndpointClassNameAndID(array $urlArray)
    {
        $classNameAndID = array(
            'className' => '',
            'id' => 0
        );

        if (isset($urlArray[2])) {
            $urlArray[2] = self::getVersionNamespace($urlArray[2]);
        }

        $className = self::buildEndpointClassName($urlArray);

        if (class_exists($className)) {
            $classNameAndID['className'] = $className;

            return $classNameAndID;
        } else {
            if (isset($urlArray[1])) {
                if ($urlArray[1] == 'ndo') {
                    $urlArray = array_slice($urlArray, 0, 3);
                } else {
                    $classNameAndID['id'] = array_pop($urlArray);
                }
            } else {
                $classNameAndID['id'] = array_pop($urlArray);
            }

            array_push($urlArray, 'index');

            $otherClassName = self::buildEndpointClassName($urlArray);

            if (class_exists($otherClassName)) {
                $classNameAndID['className'] = $otherClassName;

                return $classNameAndID;
            }
        }

        throw new RefineryException('Endpoint class (' . $className . ' | ' . $otherClassName . ') was not found', 404);
    }

    /**
     * Check if an API URL is an invalid URL
     *
     * @param array $urlArray
     *
     * @throws RefineryException
     */
    protected static function checkAPIURL(array $urlArray)
    {
        $badURLs = Config::getItem('BadAPIURLs', null, true);

        if (in_array(implode('/', $urlArray), $badURLs)) {
            throw new RefineryException('Invalid API URL specified', 400);
        }
    }

    /**
     * Simple scheme to check if the "X-Authorization-Key" header matches a key
     * listed in the config file and if the key is read or write.
     *
     * @param string $method
     *
     * @return bool
     * @throws RefineryException
     */
    protected static function checkAuthorization($method = '')
    {
        if ($method != 'GET') {
            $apiKey = self::$slim->request->headers->get('X-Authorization-Key');

            if ($apiKey) {
                $writeKeys = Config::getItem('Server.Keys.Write');

                if (!in_array($apiKey, $writeKeys)) {
                    throw new RefineryException('Invalid API Key was provided', 401);
                } else {
                    return true;
                }
            } else {
                throw new RefineryException('API Key was not supplied', 401);
            }
        }

        return true;
    }

    /**
     * Returns the debug mode status for the Refinery Server.
     *
     * @return boolean
     */
    public static function isDebug()
    {
        return self::$debug;
    }

    /**
     * Sets the debug mode status for the Refinery Server.
     *
     * @param boolean $debug
     */
    public static function setDebug($debug)
    {
        self::$debug = $debug;
    }

    /**
     * Indicates whether a refresh was requested by the client.
     *
     * @return boolean
     */
    public static function isForceRefresh()
    {
        return self::$forceRefresh;
    }

    /**
     * Sets whether a refresh was requested by the client.
     *
     * @param boolean $forceRefresh
     */
    public static function setForceRefresh($forceRefresh)
    {
        if ($forceRefresh) {
            NewRelicClient::ignoreTransaction();
        }

        self::$forceRefresh = $forceRefresh;
    }

    /**
     * Sets the performance tracking status for the Refinery Server.
     *
     * @return boolean
     */
    public static function isPerformance()
    {
        return self::$performance;
    }

    /**
     * Sets the performance tracking status for the Refinery Server.
     *
     * @param boolean $performance
     */
    public static function setPerformance($performance)
    {
        self::$performance = $performance;
    }

    /**
     * Getter for the refresh priority.
     *
     * @return int
     */
    public static function getForceRefreshPriority()
    {
        return self::$forceRefreshPriority;
    }

    /**
     * Setter for the refresh priority.
     *
     * @param int $forceRefreshPriority
     */
    public static function setForceRefreshPriority($forceRefreshPriority)
    {
        self::$forceRefreshPriority = $forceRefreshPriority;
    }

    /**
     * Getter for the refresh if stale parameter.
     *
     * @return boolean
     */
    public static function isRefreshIfStale()
    {
        return self::$refreshIfStale;
    }

    /**
     * Setter for the refresh if stale parameter.
     *
     * @param boolean $refreshIfStale
     */
    public static function setRefreshIfStale($refreshIfStale)
    {
        self::$refreshIfStale = $refreshIfStale;
    }

    /**
     * @return string
     */
    public static function getManualCacheTtl()
    {
        return self::$manualCacheTtl;
    }

    /**
     * @param string $manualCacheTtl
     */
    public static function setManualCacheTtl($manualCacheTtl = '')
    {
        self::$manualCacheTtl = $manualCacheTtl;
    }
}
