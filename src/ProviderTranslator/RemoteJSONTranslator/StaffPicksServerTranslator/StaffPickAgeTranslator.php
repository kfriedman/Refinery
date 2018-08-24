<?php
namespace NYPL\Refinery\ProviderTranslator\RemoteJSONTranslator\StaffPicksServerTranslator;

use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Provider\RemoteJSON;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\RemoteJSONTranslator;
use NYPL\Refinery\ProviderTranslator\RemoteJSONTranslator\StaffPicksServerTranslator;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class StaffPickAgeTranslator extends StaffPicksServerTranslator
    implements RemoteJSONTranslator\ReadInterface
{
    /**
     * @param RemoteJSON $provider
     * @param NDOFilter  $ndoFilter
     * @param bool       $allowEmptyResults
     *
     * @return string
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    public function read(RemoteJSON $provider, NDOFilter $ndoFilter, $allowEmptyResults = false)
    {
        $rawData = $provider->clientGet('manifest.yaml', $ndoFilter, null, $allowEmptyResults);

        if (isset($rawData['ages'][$ndoFilter->getFilterID()])) {
            return $rawData['ages'][$ndoFilter->getFilterID()];
        } else {
            throw new RefineryException('Age requested (' . $ndoFilter->getFilterID().  ') was not found', 404);
        }
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\StaffPick\StaffPickItem()
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawDataArray = $providerRawData->getRawDataArray();

        $ndo = new NDO\StaffPick\StaffPickAge();

        $ndo->setRead(true);

        $ndo->setNdoID($rawDataArray['id']);
        $ndo->setAge($rawDataArray['age']);

        if (isset($rawDataArray['_relationships']['picks'])) {
            foreach ($rawDataArray['_relationships']['picks'] as $pickID) {
                $ndo->addPick(new NDO\StaffPick($pickID));
            }
        }

        return $ndo;
    }
}
