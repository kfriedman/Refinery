<?php
namespace NYPL\Refinery\Provider\RESTAPI;

use NYPL\Refinery\Cache\CacheData;
use NYPL\Refinery\Cache\CacheData\RawDataXML;
use NYPL\Refinery\HealthCheckResponse;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a D7RefineryServerCurrent Provider
 *
 * @package NYPL\Refinery\NDO
 */
class GoogleSearch extends RESTAPI
{
    /**
     * Get the count of objects returned by the Provider.
     *
     * @return int|null
     */
    public function getCount()
    {
        // TODO: Implement getCount() method.
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
     * @param CacheData $cacheData
     * @param string    $rawData
     */
    protected function saveCacheData(CacheData $cacheData, $rawData = '')
    {
        $cacheData->save($rawData);
    }

    /**
     * Initiate a GET request on the REST API Provider.
     *
     * @param string    $partialURL The partial URL fragment for the request to combine with the base URL to produce the full URL.
     * @param array     $headers Headers to send with the request.
     * @param NDOFilter $ndoFilter NDOFilter to parse for the request.
     * @param bool      $allowEmptyResults Whether empty results is considered an error or not.
     * @param null      $addedErrorMessage An error message to add to the request error message when throwing an exception.
     * @param bool      $doNotCache If true, do not cache the response.
     *
     * @return string                      The response from the request.
     * @throws RefineryException
     */
    public function clientGet($partialURL = '', $headers = array(), NDOFilter $ndoFilter = null, $allowEmptyResults = false, $addedErrorMessage = null, $doNotCache = false)
    {
        $response = parent::clientGet($partialURL, $headers, $ndoFilter, $allowEmptyResults, $addedErrorMessage, $doNotCache);

        $response = json_decode($response, true);

        return $response;
    }
}
