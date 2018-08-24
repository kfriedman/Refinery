<?php
namespace NYPL\Refinery;

use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\Helpers\ClassNameHelper;
use NYPL\Refinery\Provider\RemoteJSON;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to initialize Providers with default settings.
 *
 * @package NYPL\Refinery
 * @SuppressWarnings(PMD.UnusedPrivateMethod)
 */
class ProviderInitializer
{
    /**
     * Run the primary initializer method on the Provider.
     *
     * @param Provider $provider
     * @param string   $environment
     *
     * @return Provider
     * @throws RefineryException
     */
    public static function initializeProvider(Provider $provider, $environment = '')
    {
        $parentName = ClassNameHelper::stripNamespace(get_parent_class($provider));
        $providerName = ClassNameHelper::stripNamespace(get_class($provider));

        if ($parentName === ClassNameHelper::stripNamespace(get_class($provider))) {
            throw new RefineryException('Parent class is same as provider class');
        }

        if ($provider instanceof RESTAPI) {
            $provider->setHost(DIManager::getConfig()->getItem('DefaultProviders.' . $providerName . '.Host', null, false, $environment));
            $provider->setBaseURL(DIManager::getConfig()->getItem('DefaultProviders.' . $providerName . '.BaseURL', null, false, $environment));
            $provider->setHttps(DIManager::getConfig()->getItem('DefaultProviders.' . $providerName . '.HTTPS', null, false, $environment));
        }

        if ($provider instanceof RemoteJSON) {
            $provider->setHost('https://' . DIManager::getConfig()->getItem('DefaultProviders.' . $providerName . '.Host', null, false, $environment));
            $provider->setBaseURL(DIManager::getConfig()->getItem('DefaultProviders.' . $providerName . '.BaseURL', null, false, $environment));
        }

        if ($manualCacheTtl = DIManager::getConfig()->getItem('DefaultProviders.' . $providerName . '.DefaultTTL', null, false, $environment)) {
            Server::setManualCacheTtl($manualCacheTtl);
        }

        $provider->setInitialized(true);

        return $provider;
    }
}
