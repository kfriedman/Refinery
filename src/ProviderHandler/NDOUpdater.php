<?php
namespace NYPL\Refinery\ProviderHandler;

use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\ProviderHandler;
use NYPL\Refinery\Provider;
use NYPL\Refinery\ProviderRawData;

/**
 * Handler to update an NDO on a Provider.
 *
 * @package NYPL\Refinery\ProviderHandler
 */
class NDOUpdater extends ProviderHandler
{
    /**
     * @param Provider     $provider
     * @param NDO          $ndo
     * @param NDOFilter    $ndofilter
     * @param array|string $rawData
     *
     * @return NDO
     * @throws RefineryException
     */
    public static function updateNDO(Provider $provider, NDO $ndo, NDOFilter $ndofilter, array $rawData = null)
    {
        self::checkSupportedProvider($provider, $ndo);

        if ($rawData) {
            $provider->setProviderRawData(new ProviderRawData($rawData));
        }

        self::checkFilterIsSpecified($ndo, $ndofilter);

        $providerNDO = $provider->updateNDO($ndo, $ndofilter);

        try {
            self::checkNDO($providerNDO, $ndo);
        } catch (\Exception $exception) {
            throw new RefineryException('Provider did not return a valid NDO: ' . $exception->getMessage());
        }

        return $providerNDO;
    }
}