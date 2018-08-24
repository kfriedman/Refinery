<?php
namespace NYPL\Refinery\Provider;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use NYPL\Refinery\Cache\CacheData\RawData;
use NYPL\Refinery\Config\Config;
use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\HealthCheckResponse;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\PerformanceTracker;
use NYPL\Refinery\Provider;
use NYPL\Refinery\StaticCache\StaticRawDataCache;
use Symfony\Component\Yaml\Yaml;

/**
 * The parent class for all REST API Providers. Currently uses Guzzle for
 * reading and parsing responses from Providers.
 *
 * @see http://guzzlephp.org/
 *
 * @package NYPL\Refinery\Provider
 */
abstract class RemoteJSON extends Provider
{
    abstract protected function processManifestFiles(array $manifestFiles, NDOFilter $ndoFilter = null);

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
     * @var int
     */
    protected $count = 0;

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

        if (!strstr($host, 'http')) {
            $host = 'http://' . $host;
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
     * Check that the host specified is valid.
     *
     * @param string $host
     *
     * @return bool
     * @throws RefineryException
     */
    protected function checkHost($host = '')
    {
        if (!filter_var($host, FILTER_VALIDATE_URL)) {
            throw new RefineryException('Host (' . $host . ') contains invalid characters.');
        }

        return true;
    }

    /**
     * Initiate a GET request on the REST API Provider.
     *
     * @param string    $partialURL   The partial URL fragment for the request to combine with the base URL to produce the full URL.
     * @param NDOFilter $ndoFilter    NDOFilter to parse for the request.
     * @param bool      $doNotCache   If true, do not cache the response.
     * @param bool      $forceRefresh Force refreshing of the cache.
     *
     * @return string                      The response from the request.
     * @throws RefineryException
     */
    public function clientGet($partialURL = '', NDOFilter $ndoFilter = null, $doNotCache = false, $forceRefresh = false)
    {
        $uri = $this->buildFullURL($partialURL);

        if ($ndoFilter->getQueryParameter('filter')->getValue()) {
            $uri .= '?' .  http_build_query($ndoFilter->getQueryParameter('filter')->getValue());
        }

        // Check to make sure all required parameters are set before initiating a request.
        $this->checkRequiredParameters();

        if ($rawData = StaticRawDataCache::read($uri)) {
            return $rawData;
        }

        // Create a RawData object to cache the results.
        $cacheData = new RawData($uri, $uri);

        // Only send/GET the request if cached data does not exist.
        if ($cacheData->isExists() && (bool) Config::getItem('Cache.RawData.Enabled') && !$forceRefresh) {
            PerformanceTracker::trackEvent($uri);

            $cacheData->processRefresh(true, $partialURL, $this, $ndoFilter);
        } else {
            // Track how long sending the request takes
            $rawData = PerformanceTracker::trackEvent($uri, function() use ($uri, $ndoFilter) {
                $client = new Client();

                try {
                    $manifestFile = Yaml::parse((string) $client->get($uri)->getBody());
                } catch (RequestException $exception) {
                    if ($exception->getResponse()) {
                        $statusCode = $exception->getResponse()->getStatusCode();
                    } else {
                        $statusCode = null;
                    }

                    throw new RefineryException('Unable to load Remote JSON: ' . $exception->getMessage(), $statusCode);
                } catch (\Exception $exception) {
                    throw new RefineryException('Unable to load Remote JSON: ' . $exception->getMessage());
                }

                return $this->processManifestFiles($manifestFile['file-list'], $ndoFilter);
            });

            if (!$rawData) {
                throw new RefineryException('No records found', 404);
            }

            if (!$doNotCache) {
                // Save the response to cache
                $cacheData->save($rawData);

                StaticRawDataCache::save($uri, $rawData);
            }

            return $rawData;
        }

        StaticRawDataCache::save($uri, $cacheData->getData());

        // Return the cached data if cached data exists
        return $cacheData->getData();
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
     * Given the partial URL fragment, build the complete URL for the request.
     *
     * @param string $partialURL
     *
     * @return string
     */
    protected function buildFullURL($partialURL = '')
    {
        $fullURL = '';

        $fullURL .= $this->host;

        if ($this->baseURL) {
            $fullURL .= '/' . $this->baseURL;
        }

        $fullURL .= '/'  . $partialURL;

        return $fullURL;
    }

    /**
     * @param int $count
     */
    public function setCount($count)
    {
        $this->count = (int) $count;
    }


    /**
     * Get the count of objects returned by the Provider.
     *
     * @return int|null
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Get the current page being returned by the Provider.
     *
     * @return int|null
     */
    public function getPage()
    {
        // TODO: Implement getPage() method.
    }

    /**
     * Get the number of records per page returned by the Provider.
     *
     * @return int|null
     */
    public function getPerPage()
    {
        // TODO: Implement getPerPage() method.
    }

    /**
     * Get the HTTP status code returned by the Provider.
     *
     * @return int|null
     */
    public function getStatusCode()
    {
        // TODO: Implement getStatusCode() method.
    }

    /**
     * Get the meta data returned by the Provider.
     *
     * @return array
     */
    public function getProviderMetaData()
    {
        // TODO: Implement getProviderMetaData() method.
    }

    /**
     * Checks to make sure the Provider is healthy.
     *
     * @return HealthCheckResponse
     */
    public function checkHealth()
    {
        // TODO: Implement checkHealth() method.
    }

    /**
     * @param array     $rawData
     * @param NDOFilter $ndoFilter
     *
     * @return array
     */
    public function filterRawData(array $rawData, NDOFilter $ndoFilter)
    {
        if ($ndoFilter->getPerPage()) {
            $rawData = array_slice($rawData, 0, $ndoFilter->getPerPage());
        }

        $this->setCount(count($rawData));

        return $rawData;
    }
}
