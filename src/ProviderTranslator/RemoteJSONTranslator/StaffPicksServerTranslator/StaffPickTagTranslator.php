<?php
namespace NYPL\Refinery\ProviderTranslator\RemoteJSONTranslator\StaffPicksServerTranslator;

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
class StaffPickTagTranslator extends StaffPicksServerTranslator
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

        if (isset($rawData['tags'][$ndoFilter->getFilterID()])) {
            return $rawData['tags'][$ndoFilter->getFilterID()];
        }

        return null;
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\StaffPick\StaffPickItem()
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawDataArray = $providerRawData->getRawDataArray();

        $ndo = new NDO\StaffPick\StaffPickTag();

        $ndo->setRead(true);

        $ndo->setNdoID($rawDataArray['id']);
        $ndo->setTag($rawDataArray['tag']);

        if (isset($rawDataArray['_relationships']['picks'])) {
            foreach ($rawDataArray['_relationships']['picks'] as $pickID) {
                $ndo->addPick(new NDO\StaffPick($pickID));
            }
        }

        return $ndo;
    }
}