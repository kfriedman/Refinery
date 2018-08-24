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
class SearchFacetTranslator extends GoogleSearchTranslator implements RESTAPITranslator\ReadInterface
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
     * @return NDO\Search\SearchFacet
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $facet = $providerRawData->getRawDataArray();

        $ndo = new NDO\Search\SearchFacet($facet['label']);

        $ndo->setRead(true);

        $ndo->setLabel($facet['label']);
        $ndo->setLabelWithOp($facet['label_with_op']);
        $ndo->setAnchor($facet['anchor']);
        $ndo->setCount(0);

        return $ndo;
    }
}
