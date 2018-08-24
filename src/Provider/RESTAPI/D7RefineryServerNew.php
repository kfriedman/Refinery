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
class D7RefineryServerNew extends RESTAPI
{
    protected $rawDataKey = 'data';

    /**
     * @return int|null
     */
    public function getCount()
    {
        $rawDataAllArray = $this->getProviderRawData()->getRawDataAllArray();

        if (isset($rawDataAllArray['meta']['count'])) {
            return (int) $rawDataAllArray['meta']['count'];
        }

        return null;
    }

    /**
     * @return int|null
     */
    public function getPage()
    {
        $rawDataAllArray = $this->getProviderRawData()->getRawDataAllArray();

        if (isset($rawDataAllArray['meta']['number'])) {
            return (int) $rawDataAllArray['meta']['page']['number'];
        }

        return null;
    }

    /**
     * @return int|null
     */
    public function getPerPage()
    {
        $rawDataAllArray = $this->getProviderRawData()->getRawDataAllArray();

        if (isset($rawDataAllArray['meta']['page']['size'])) {
            if ($rawDataAllArray['meta']['page']['size'] === null) {
                return $rawDataAllArray['meta']['page']['size'];
            } else {
                return (int) $rawDataAllArray['meta']['page']['size'];
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
            $this->clientGet('node', array(), null, null, null, true);

            return new HealthCheckResponse(true, $this->getHost());
        } catch (RefineryException $exception) {
            return new HealthCheckResponse(false, $exception->getMessage() . ' | ' . $exception->getAddedMessage(true));
        }
    }
}
