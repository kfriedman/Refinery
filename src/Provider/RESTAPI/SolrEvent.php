<?php
namespace NYPL\Refinery\Provider\RESTAPI;

use NYPL\Refinery\Config\Config;
use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\HealthCheckResponse;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a Solr Event Provider
 *
 * @package NYPL\Refinery\NDO
 */
class SolrEvent extends RESTAPI
{
    /**
     * @var int Solr rows per page by default
     */
    const DEFAULT_ROWS_PER_PAGE = 10;

    /**
     * Get the count of objects returned by the Provider.
     *
     * @return int|null
     */
    public function getCount()
    {
        $rawData = $this->getProviderRawData()->getRawDataAllArray();

        return isset($rawData['response']['numFound']) ?
            (int) $rawData['response']['numFound'] : null;
    }

    /**
     * Get the current page being returned by the Provider.
     *
     * @return int|null
     */
    public function getPage()
    {
        $rawData = $this->getProviderRawData()->getRawDataAllArray();
        return isset($rawData['response']['start']) ?
            (int) $rawData['response']['start'] : null;
    }

    /**
     * Get the number of records per page returned by the Provider.
     *
     * @return int|null
     */
    public function getPerPage()
    {
        $rawData = $this->getProviderRawData()->getRawDataAllArray();
        return isset($rawData['responseHeader']['params']['rows']) ?
            (int) $rawData['responseHeader']['params']['rows'] : self::DEFAULT_ROWS_PER_PAGE;
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
        return array(
            'host' => $this->getHost()
        );
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
    public function clientGet($partialURL = '', $headers = array(), NDOFilter $ndoFilter = null, $allowEmptyResults = false, $addedErrorMessage = null, $doNotCache = true)
    {
        $this->addRequestOption('auth', array(
            Config::getItem('DefaultProviders.SolrEvent.User'),
            Config::getItem('DefaultProviders.SolrEvent.Key')
        ));
        $response = parent::clientGet($partialURL, $headers, $ndoFilter, $allowEmptyResults, $addedErrorMessage, $doNotCache);

        return $response;
    }

    /**
     * Checks to make sure the Provider is healthy.
     *
     * @return HealthCheckResponse
     */
    public function checkHealth()
    {
        try {
            $this->addRequestOption('connect_timeout', 5);
            $this->clientGet('select?q=*:*', array(), null, null, null, true);

            return new HealthCheckResponse(true, $this->getHost());
        } catch (RefineryException $exception) {
            return new HealthCheckResponse(false, $exception->getMessage() . ' | ' . $exception->getAddedMessage(true));
        }
    }
}
