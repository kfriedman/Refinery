<?php
namespace NYPL\Refinery;

use NYPL\Refinery\Exception\RefineryException;

/**
 * Abstract class to create a Provider Handler.
 *
 * @package NYPL\Refinery
 */
abstract class ProviderHandler
{
    /**
     * Check that a Provider supports handling the requested NDO.
     *
     * @param Provider  $provider
     * @param NDO       $ndo
     *
     * @return bool
     * @throws RefineryException
     */
    protected static function checkSupportedProvider(Provider $provider, NDO $ndo)
    {
        $supportedProviders = $ndo->getSupportedReadProviders();

        foreach ($supportedProviders as $supportedProvider) {
            if (self::isSupportedProvider($provider, $supportedProvider)) {
                return true;
            }
        }

        throw new RefineryException('Provider (' . get_class($provider) . ') does not support this NDO (' . get_class($ndo) . ') ');
    }

    /**
     * Method to compare whether the current Provider (or it's parent) is a
     * the same as a supported Provider.
     *
     * @param Provider $provider
     * @param Provider $supportedProvider
     *
     * @return bool
     */
    protected static function isSupportedProvider(Provider $provider, Provider $supportedProvider)
    {
        if (get_class($provider) == get_class($supportedProvider) || get_parent_class($provider) == get_class($supportedProvider)) {
            return true;
        }

        return false;
    }

    /**
     * Method to check whether the NDO returned by the Provider is the same
     * as the NDO expected by the handler.
     *
     * @param NDO $providerNDO
     * @param NDO $expectedNDO
     *
     * @throws RefineryException
     */
    protected static function checkNDO(NDO $providerNDO, NDO $expectedNDO)
    {
        if (!$providerNDO instanceof $expectedNDO) {
            throw new RefineryException('Returned NDO (' . get_class($providerNDO) . ') is a different type from requested NDO (' . get_class($expectedNDO) . ')');
        }
    }

    /**
     * Method to check whether a filter was provided for the requested NDO.
     *
     * @param NDO       $ndo
     * @param NDOFilter $ndoFilter
     *
     * @throws RefineryException
     */
    protected static function checkFilterIsSpecified(NDO $ndo, NDOFilter $ndoFilter = null)
    {
        if (!$ndoFilter) {
            throw new RefineryException('NDO (' . $ndo->getNdoType() . ') requires that a filter be specified.');
        }
    }
}