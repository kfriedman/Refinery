<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerCurrentTranslator;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerTranslator;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class SubjectGroupTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
{
    /**
     * @param RESTAPI   $provider
     * @param NDOFilter $ndoFilter
     * @param bool      $allowEmptyResults
     *
     * @return string
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    public function read(RESTAPI $provider, NDOFilter $ndoFilter, $allowEmptyResults = false)
    {
        if ($ndoFilter->getFilterID()) {
            $urlFilter = new NDOFilter();
            $urlFilter->addQueryParameter('filter[tid]', implode('|', $ndoFilter->getFilterID()));

            return $provider->clientGet('taxonomy/term/vocabulary_4', null, $urlFilter, $allowEmptyResults);
        } else {
            if ($ndoFilter->getQueryParameter('filter')->getValue()) {
                $ndoFilter = $this->translateFilter($ndoFilter);
            }

            return $provider->clientGet('taxonomy/term/vocabulary_4', null, $ndoFilter, $allowEmptyResults);
        }
    }

    /**
     * @param NDOFilter $ndoFilter
     *
     * @return NDOFilter
     */
    protected function translateFilter(NDOFilter $ndoFilter)
    {
        $translatedFilter = new NDOFilter();

        $filter = $ndoFilter->getQueryParameter('filter')->getValue();

        if (isset($filter['relationships']['parent'])) {
            $translatedFilter->addQueryParameter('filter[_enhanced][parent_uuid]', $filter['relationships']['parent']);
        }

        return $translatedFilter;
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\SubjectGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawDataArray = $providerRawData->getRawDataArray();

        $ndo = new NDO\SubjectGroup();

        foreach ($rawDataArray as $rawData) {
            $providerTranslator = new SubjectTranslator();
            $ndo->append($providerTranslator->translate(new ProviderRawData($rawData, null, null, null, null, $providerRawData->getProvider())));
        }

        return $ndo;
    }
}
