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
class StaffPickListGroupTranslator extends StaffPicksServerTranslator
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
        $rawData = $provider->clientGet('manifest.yaml', $ndoFilter);

        if ($ndoFilter->getFilterID()) {
            $rawData = array_intersect_key($rawData['lists'], array_flip($ndoFilter->getFilterID()));
        } else {
            $rawData = $rawData['lists'];
        }

        return $provider->filterRawData($rawData, $ndoFilter);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\StaffPickListGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawDataArray = $providerRawData->getRawDataArray();

        $ndo = new NDO\StaffPickListGroup();

        foreach ($rawDataArray as $rawData) {
            $providerTranslator = new StaffPickListTranslator();
            $ndo->append($providerTranslator->translate(new ProviderRawData($rawData)));
        }

        return $ndo;
    }
}
