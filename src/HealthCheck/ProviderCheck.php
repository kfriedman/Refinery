<?php
namespace NYPL\Refinery\HealthCheck;

use NYPL\Refinery\Helpers\ClassNameHelper;
use NYPL\Refinery\Provider;
use NYPL\Refinery\HealthCheck;

/**
 * Provider health test
 *
 * @package NYPL\Refinery
 */
class ProviderCheck extends HealthCheck
{
    /**
     * The Provider to check
     *
     * @var Provider
     */
    protected $provider;

    /**
     * Constructor to set the Provider to check.
     *
     * @param Provider $provider
     */
    public function __construct(Provider $provider)
    {
        $this->setProvider($provider);
    }

    /**
     * Run the isHealthy() method of the Provider to determine the health.
     *
     * @return bool
     */
    public function runCheck()
    {
        $healthCheckResponse = $this->getProvider()->checkHealth();

        if ($healthCheckResponse->isHealthy()) {
            return $this->setSuccess('Connection to provider (' .  $this->getProviderName() . ' @ ' . $healthCheckResponse->getMessage() . ') succeeded');
        } else {
            return $this->setFailure('Connection to provider (' .  $this->getProviderName() . ' @ ' . $healthCheckResponse->getMessage() . ') failed');
        }
    }

    /**
     * Getter for the Provider to check.
     *
     * @return Provider
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Setter for the Provider to check.
     *
     * @param Provider $provider
     */
    public function setProvider(Provider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Get the name of the Provider to output to messages.
     *
     * @return string
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    protected function getProviderName()
    {
        return ClassNameHelper::getNameWithoutNamespace($this->getProvider());
    }
}