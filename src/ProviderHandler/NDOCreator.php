<?php
namespace NYPL\Refinery\ProviderHandler;

use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\NDO;
use NYPL\Refinery\ProviderHandler;
use NYPL\Refinery\Provider;
use NYPL\Refinery\ProviderRawData;

/**
 * Handler to create an NDO on a Provider.
 *
 * @package NYPL\Refinery\ProviderHandler
 */
class NDOCreator extends ProviderHandler
{
    /**
     * Create an NDO on a Provider.
     *
     * @param Provider $provider The Provider to create an NDO on.
     * @param NDO      $ndo      The NDO that you want to create.
     * @param array    $rawData  The raw data that should be used to build the NDO.
     *
     * @return NDO
     * @throws RefineryException
     */
    public static function createNDO(Provider $provider, NDO $ndo, array $rawData = null)
    {
        // Check to make sure the Provider supports create operations.
        self::checkSupportedProvider($provider, $ndo);

        if ($rawData) {
            $provider->setProviderRawData(new ProviderRawData($rawData));
        }

        $providerNDO = $provider->createNDO($ndo);

        try {
            self::checkNDO($providerNDO, $ndo);
        } catch (\Exception $exception) {
            throw new RefineryException('Provider did not return a valid NDO: ' . $exception->getMessage());
        }

        return $providerNDO;
    }
}