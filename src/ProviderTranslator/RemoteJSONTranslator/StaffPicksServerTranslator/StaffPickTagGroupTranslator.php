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
class StaffPickTagGroupTranslator extends StaffPicksServerTranslator
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

        if ($ndoFilter->getFilterID()) {
            $rawData = array_intersect_key($rawData['tags'], array_flip($ndoFilter->getFilterID()));
        } else {
            $rawData = $rawData['tags'];
        }

        return $provider->filterRawData($rawData, $ndoFilter);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\StaffPickGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawDataArray = $providerRawData->getRawDataArray();

        $ndo = new NDO\StaffPickTagGroup();

        foreach ($rawDataArray as $rawData) {
            $providerTranslator = new StaffPickTagTranslator();
            $ndo->append($providerTranslator->translate(new ProviderRawData($rawData)));
        }

        return $ndo;
    }
}
