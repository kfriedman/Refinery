<?php
namespace NYPL\Refinery\Provider;

use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Message\ResponseInterface;
use GuzzleHttp\Query;
use NYPL\Refinery\Cache\CacheData;
use NYPL\Refinery\Cache\CacheData\RawData;
use NYPL\Refinery\Config\Config;
use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\PerformanceTracker;
use NYPL\Refinery\Provider;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use NYPL\Refinery\QueryParameter;
use NYPL\Refinery\Cache\CacheClient;
use NYPL\Refinery\Server;
use NYPL\Refinery\StaticCache\StaticRawDataCache;

/**
 * The parent class for all REST API Providers. Currently uses Guzzle for
 * reading and parsing responses from Providers.
 *
 * @see http://guzzlephp.org/
 *
 * @package NYPL\Refinery\Provider
 */
abstract class RESTAPI extends Provider
{
    /**
     * The Guzzle client to use for requests.
     *
     * @var Client
     */
    protected $guzzleClient;

    /**
     * The host for requests.
     *
     * @var string
     */
    protected $host = '';

    /**
     * The base URL for requests.
     *
     * @var string
     */
    protected $baseURL = '';

    /**
     * The response from the Guzzle client.
     *
     * @var ResponseInterface
     */
    protected $response;

    /**
     * CacheClient object to use for caching responses.
     *
     * @var CacheClient
     */
    protected $cache;

    /**
     * @var array
     */
    protected $requestOptions = array();

    /**
     * @var bool
     */
    protected $https = false;

    /**
     * Constructor for the REST API Provider.
     *
     * @param string $host
     * @param string $baseURL
     *
     * @throws RefineryException
     */
    public function __construct($host = '', $baseURL = '')
    {
        if ($host) {
            $this->setHost($host);
        }
        if ($baseURL) {
            $this->setBaseURL($baseURL);
        }
    }

    /**
     * Setter for the host for requests.
     *
     * @param string $host
     *
     * @throws RefineryException
     */
    public function setHost($host = '')
    {
        if (!$host) {
            throw new RefineryException('Host provided is blank.');
        }

        if ($this->checkHost($host)) {
            $this->host = $host;
        }
    }

    /**
     * Getter for the host for requests.
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Setter for the base URL for requests.
     *
     * @param string $baseURL
     */
    public function setBaseURL($baseURL = '')
    {
        $this->baseURL = $baseURL;
    }

    /**
     * Getter for the base URL for requests.
     *
     * @return string
     */
    public function getBaseURL()
    {
        return $this->baseURL;
    }

    /**
     * Initiate a GET request on the REST API Provider.
     *
     * @param string    $partialURL        The partial URL fragment for the request to combine with the base URL to produce the full URL.
     * @param array     $headers           Headers to send with the request.
     * @param NDOFilter $ndoFilter         NDOFilter to parse for the request.
     * @param bool      $allowEmptyResults Whether empty results is considered an error or not.
     * @param null      $addedErrorMessage An error message to add to the request error message when throwing an exception.
     * @param bool      $doNotCache        If true, do not cache the response.
     *
     * @return string                      The response from the request.
     * @throws RefineryException
     */
    public function clientGet($partialURL = '', $headers = array(), NDOFilter $ndoFilter = null, $allowEmptyResults = false, $addedErrorMessage = null, $doNotCache = false)
    {
        // Check to make sure all required parameters are set before initiating a request.
        $this->checkRequiredParameters();

        // Get the Guzzle client for the request.
        $guzzleClient = $this->getGuzzleClient();

        $this->addRequestOption('verify', false);

        // Create a Request from the Guzzle Client
        $request = $guzzleClient->createRequest('GET', $this->buildFullURL($partialURL), $this->getRequestOptions());

        // Initialize the Request with requested headers and the filter setting query parameters, etc.
        $this->initializeRequest($request, $headers, $ndoFilter);

        if ($rawData = StaticRawDataCache::read($request->getUrl())) {
            return $rawData;
        }

        // Create a RawData object to cache the results.
        $cacheData = new RawData($request->getUrl(), $request->getUrl());

        // Only send/GET the request if cached data does not exist.
        if ($cacheData->isExists() && (bool) Config::getItem('Cache.RawData.Enabled')) {
            PerformanceTracker::trackEvent($request->getUrl());

            $cacheData->processRefresh();
        } else {
            try {
                // Track how long sending the request takes
                PerformanceTracker::trackEvent($request->getUrl(), function() use ($guzzleClient, $request) {
                    $this->setResponse($guzzleClient->send($request));
                });

                $rawData = (string) $this->getResponse()->getBody();

                if (!$doNotCache) {
                    // Save the response to cache
                    $this->saveCacheData($cacheData, $rawData);

                    StaticRawDataCache::save($request->getUrl(), $rawData);
                }

                return $rawData;
            } catch (RequestException $exception) {
                $this->handleRequestError($exception, $cacheData, $allowEmptyResults, $addedErrorMessage);
            }
        }

        StaticRawDataCache::save($request->getUrl(), $cacheData->getData());

        // Return the cached data if cached data exists
        return $cacheData->getData();
    }

    /**
     * @param CacheData $cacheData
     * @param string    $rawData
     */
    protected function saveCacheData(CacheData $cacheData, $rawData = '')
    {
        $cacheData->save(json_decode($rawData, true));
    }

    /**
     * Initiate a PUT request on the REST API Provider. Note: this has not been
     * fully implemented.
     *
     * @param string $partialURL
     * @param array  $rawData
     *
     * @return string
     * @throws RefineryException
     */
    public function clientPut($partialURL = '', array $rawData = null)
    {
        $this->checkRequiredParameters();

        try {
            $fullURL = $this->buildFullURL($partialURL);

            $guzzleClient = $this->getGuzzleClient();

            $this->setResponse($guzzleClient->put($fullURL, array('body' => $rawData)));

            return (string) $this->getResponse()->getBody();
        } catch (RequestException $exception) {
            throw new RefineryException($exception->getMessage(), $exception->getResponse()->getStatusCode(), (string) $exception->getResponse()->getBody());
        }
    }

    /**
     * Initiate a POST request on the REST API Provider. Note: this has not been
     * fully implemented.
     *
     * @param string $partialURL
     * @param array  $rawData
     *
     * @return string
     * @throws RefineryException
     */
    public function clientPost($partialURL = '', array $rawData = null)
    {
        $this->checkRequiredParameters();

        try {
            $fullURL = $this->buildFullURL($partialURL);

            $guzzleClient = $this->getGuzzleClient();

            $this->setResponse($guzzleClient->post($fullURL, array('body' => $rawData)));

            return (string) $this->getResponse()->getBody();
        } catch (RequestException $exception) {
            throw new RefineryException($exception->getMessage(), $exception->getResponse()->getStatusCode(), (string) $exception->getResponse()->getBody());
        }
    }

    /**
     * Initialize the Guzzle Request.
     *
     * @param Request   $request   The Request to initialize.
     * @param array     $headers   Headers to use to initialize the request.
     * @param NDOFilter $ndoFilter Filter to use to create query parameters on the request.
     */
    protected function initializeRequest(Request $request, $headers = array(), NDOFilter $ndoFilter = null)
    {
        $request->addHeader('Accept-Encoding', 'gzip');

        if ($headers) {
            foreach ($headers as $headerType => $headerData) {
                $request->addHeader($headerType, $headerData);
            }
        }

        if ($ndoFilter) {
            $request->setQuery($this->getQuery($ndoFilter));
        }
    }

    /**
     * Create the query string for a request from an NDOFilter.
     *
     * @param NDOFilter $ndoFilter
     *
     * @return Query
     * @throws RefineryException
     */
    protected function getQuery(NDOFilter $ndoFilter)
    {
        $query = new Query();

        if ($ndoFilter->getStart() && $ndoFilter->getStart() != 1) {
            $query->set('start', $ndoFilter->getStart());
        }
        if ($ndoFilter->getPerPage()) {
            $query->set('limit', $ndoFilter->getPerPage());
        }
        if ($ndoFilter->getPage()) {
            $query->set('offset', $ndoFilter->getPage());
        }
        if ($ndoFilter->getQueryParameterArray()) {
            /**
             * @var $queryParameterName string
             * @var $queryParameter QueryParameter
             */
            foreach ($ndoFilter->getQueryParameterArray() as $queryParameterName => $queryParameter) {
                $query->set($queryParameterName, $queryParameter->getValueWithOperator());
            }
        }

        return $query;
    }

    /**
     * Check that the host specified is valid.
     *
     * @param string $host
     *
     * @return bool
     * @throws RefineryException
     */
    protected function checkHost($host = '')
    {
        if (strstr($host, 'http')) {
            throw new RefineryException('Host (' . $host . ') should not contain protocol.');
        }
        if (!filter_var('http://' . $host, FILTER_VALIDATE_URL)) {
            throw new RefineryException('Host (' . $host . ') contains invalid characters.');
        }

        return true;
    }

    /**
     * Check that required parameters are set for a request.
     *
     * @return bool
     * @throws RefineryException
     */
    protected function checkRequiredParameters()
    {
        if (!$this->host) {
            throw new RefineryException('Required parameter (host) is missing.');
        }

        return true;
    }

    /**
     * Create a Guzzle client.
     *
     * @return Client
     */
    public function createGuzzleClient()
    {
        return new Client();
    }

    /**
     * Setter for a Guzzle client.
     *
     * @param Client $guzzleClient
     */
    public function setGuzzleClient(Client $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * Getter for a Guzzle client.
     *
     * @return Client
     */
    public function getGuzzleClient()
    {
        if (!$this->guzzleClient) {
            $this->setGuzzleClient($this->createGuzzleClient());
        }

        return $this->guzzleClient;
    }

    /**
     * Given the partial URL fragment, build the complete URL for the request.
     *
     * @param string $partialURL
     *
     * @return string
     */
    protected function buildFullURL($partialURL = '')
    {
        $fullURL = '';

        if ($this->isHttps()) {
            $fullURL .= 'https://';
        } else {
            $fullURL .= 'http://';
        }

        $fullURL .= $this->host;

        if ($this->baseURL) {
            $fullURL .= '/' . $this->baseURL;
        }

        if (substr($partialURL, 0, 1) == '?') {
            $fullURL .= $partialURL;

        } else {
            $fullURL .= '/'  . $partialURL;
        }

        return $fullURL;
    }

    /**
     * Get the Response from the request.
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set the Response from the request.
     *
     * @param ResponseInterface $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * Handle an error generated by the REST API Provider. Sometimes an error
     * is acceptable.
     *
     * @param RequestException $exception         The Exception that was thrown from the request.
     * @param RawData          $cacheData         The cache object for the request.
     * @param bool             $allowEmptyResults Whether empty results is considered an error or now.
     * @param null             $addedErrorMessage Additional error message to add to the exception's error message.
     *
     * @throws RefineryException
     */
    protected function handleRequestError(RequestException $exception, RawData $cacheData, $allowEmptyResults = false, $addedErrorMessage = null)
    {
        PerformanceTracker::trackEvent($exception->getRequest()->getUrl());

        // Server did not send a normal response.
        if (!$exception->getResponse()) {
            throw new RefineryException('No response returned by server', 0, array($exception->getRequest()->getUrl()));
        // Empty results not allowed or empty results are allowed and server returned an error code other than 404.
        } elseif (!$allowEmptyResults || ($allowEmptyResults && $exception->getResponse()->getStatusCode() != 404 && $exception->getResponse()->getStatusCode() != 403)) {
            throw new RefineryException($exception->getMessage(), $exception->getResponse()->getStatusCode(), array((string) $exception->getResponse()->getBody(), $addedErrorMessage, $exception->getRequest()->getUrl()));
        // Server returned a 404 and this is acceptable behavior so clear the cache.
        } else {
            $cacheData->save(null);
        }
    }

    /**
     * Getter for the request options array.
     *
     * @return array
     */
    public function getRequestOptions()
    {
        return $this->requestOptions;
    }

    /**
     * Setter for the request options array.
     *
     * @param array $requestOptions
     */
    public function setRequestOptions($requestOptions)
    {
        $this->requestOptions = $requestOptions;
    }

    /**
     * Add an option to the request options array.
     *
     * @param string $name
     * @param mixed  $option
     */
    public function addRequestOption($name = '', $option = null)
    {
        $this->requestOptions[$name] = $option;
    }

    /**
     * @return boolean
     */
    public function isHttps()
    {
        return $this->https;
    }

    /**
     * @param boolean $https
     */
    public function setHttps($https)
    {
        $this->https = (bool) $https;
    }
}
