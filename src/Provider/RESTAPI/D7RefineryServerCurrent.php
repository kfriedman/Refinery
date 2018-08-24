<?php
namespace NYPL\Refinery\Provider\RESTAPI;

use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\HealthCheckResponse;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a D7RefineryServerCurrent Provider
 *
 * @package NYPL\Refinery\NDO
 */
class D7RefineryServerCurrent extends RESTAPI
{
    protected $rawDataKey = 'data';

    /**
     * @return int|null
     */
    public function getCount()
    {
        $rawData = $this->getProviderRawData()->getRawDataAllArray();

        if (isset($rawData['meta']['count'])) {
            return (int) $rawData['meta']['count'];
        }

        $rawData = $this->getProviderRawData()->getRawDataArray();

        if (isset($rawData[0])) {
            return count($rawData);
        }

        return null;
    }

    /**
     * @return int|null
     */
    public function getPage()
    {
        $rawDataAllArray = $this->getProviderRawData()->getRawDataAllArray();

        if (isset($rawDataAllArray['meta']['page']['offset'])) {
            return (int) $rawDataAllArray['meta']['page']['offset'];
        }

        return null;
    }

    /**
     * @return int|null
     */
    public function getPerPage()
    {
        $rawDataAllArray = $this->getProviderRawData()->getRawDataAllArray();

        if (isset($rawDataAllArray['meta']['page']['limit'])) {
            if ($rawDataAllArray['meta']['page']['limit'] === null) {
                return $rawDataAllArray['meta']['page']['limit'];
            } else {
                return (int) $rawDataAllArray['meta']['page']['limit'];
            }
        }

        return null;
    }

    /**
     * @return int|null
     */
    public function getStatusCode()
    {
        if ($this->getResponse()) {
            return (int) $this->getResponse()->getStatusCode();
        } else {
            return null;
        }
    }

    /**
     * @return array
     */
    public function getProviderMetaData()
    {
        return array(
            'host' => $this->getHost()
        );
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
            $this->clientGet('', array(), null, null, null, true);

            return new HealthCheckResponse(true, $this->getHost());
        } catch (RefineryException $exception) {
            return new HealthCheckResponse(false, $exception->getMessage() . ' | ' . $exception->getAddedMessage(true));
        }
    }
}
