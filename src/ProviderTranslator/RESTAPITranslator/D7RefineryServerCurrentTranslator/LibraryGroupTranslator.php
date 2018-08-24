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
class LibraryGroupTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
        $ndoFilter->addQueryParameter('filter[parent]', 0);
        $ndoFilter->addQueryParameter('filter[unit]', 'not:admin');

        return $provider->clientGet('other/location', null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\LibraryGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawDataArray = $providerRawData->getRawDataArray();

        $ndo = new NDO\LibraryGroup();

        foreach ($rawDataArray as $rawData) {
            $providerTranslator = new LibraryTranslator();
            $ndo->append($providerTranslator->translate(new ProviderRawData($rawData, null, $providerRawData->getMetaData())));
        }

        return $ndo;
    }
}