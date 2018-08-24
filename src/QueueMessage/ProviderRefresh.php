<?php
namespace NYPL\Refinery\QueueMessage;

use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\ProviderInitializer;
use NYPL\Refinery\QueueMessage;

/**
 * The ProviderRefresh QueueMessage used to process cache updates for the Refinery
 * Server.
 *
 * @package NYPL\Refinery
 */
class ProviderRefresh extends QueueMessage
{
    /**
     * The URI that should retrieved for the cache update.
     *
     * @var string
     */
    protected $refreshURI = '';

    /**
     * @var string
     */
    protected $providerName = '';

    /**
     * @var NDOFilter
     */
    protected $ndoFilter;

    /**
     * Constructor for the ProviderRefresh message.
     *
     * @param string    $message
     * @param string    $refreshURI
     * @param Provider  $provider
     * @param NDOFilter $ndoFilter
     */
    public function __construct($message = '', $refreshURI = '', Provider $provider = null, NDOFilter $ndoFilter = null)
    {
        if ($message) {
            $this->setMessageProperties(stripslashes($message));
        }

        if ($refreshURI) {
            $this->setRefreshURI($refreshURI);
        }

        if ($provider) {
            $this->setProviderName(get_class($provider));
        }

        if ($ndoFilter) {
            $this->setNdoFilter($ndoFilter);
        }
    }

    /**
     * Process the RefreshCache message by getting the refreshURI, using
     * Guzzle to GET the URI, and then saving the response as a RawData object.
     *
     * @return \GuzzleHttp\Message\FutureResponse|\GuzzleHttp\Message\ResponseInterface|\GuzzleHttp\Ring\Future\FutureInterface|null
     */
    public function processMessage()
    {
        $providerName = $this->getProviderName();

        /**
         * @var Provider\RemoteJSON $provider
         */
        $provider = new $providerName;

        ProviderInitializer::initializeProvider($provider);

        $provider->clientGet($this->getRefreshURI(), $this->getNdoFilter(), false, true);
    }

    /**
     * Getter for the message.
     *
     * @return string
     */
    public function getMessage()
    {
        return addslashes(serialize(get_object_vars($this)));
    }

    /**
     * Getter for the URI used to refresh the cache.
     *
     * @return string
     */
    public function getRefreshURI()
    {
        return $this->refreshURI;
    }

    /**
     * Setter for the URI used to refresh the cache.
     *
     * @param string $refreshURI
     */
    public function setRefreshURI($refreshURI)
    {
        $this->refreshURI = $refreshURI;
    }

    /**
     * @return string
     */
    public function getProviderName()
    {
        return $this->providerName;
    }

    /**
     * @param string $providerName
     */
    public function setProviderName($providerName)
    {
        $this->providerName = $providerName;
    }

    /**
     * @return NDOFilter
     */
    public function getNdoFilter()
    {
        return $this->ndoFilter;
    }

    /**
     * @param NDOFilter $ndoFilter
     */
    public function setNdoFilter($ndoFilter)
    {
        $this->ndoFilter = $ndoFilter;
    }
}
