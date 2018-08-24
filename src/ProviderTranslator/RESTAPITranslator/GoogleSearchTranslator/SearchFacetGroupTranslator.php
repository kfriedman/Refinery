<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\GoogleSearchTranslator;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\GoogleSearchTranslator;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class SearchFacetGroupTranslator extends GoogleSearchTranslator implements RESTAPITranslator\ReadInterface
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
        return null;
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\Search\SearchFacetGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\Search\SearchFacetGroup();
        $ndo->setRead(true);

        foreach ($providerRawData->getRawDataArray() as $facet) {
            $searchFacetTranslator = new SearchFacetTranslator();

            $ndo->append($searchFacetTranslator->translate(new ProviderRawData($facet)));
        }

        return $ndo;
    }
}
