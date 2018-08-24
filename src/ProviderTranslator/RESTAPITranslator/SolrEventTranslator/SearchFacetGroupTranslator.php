<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\SolrEventTranslator;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\SolrEventTranslator;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class SearchFacetGroupTranslator extends SolrEventTranslator implements RESTAPITranslator\ReadInterface
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
     * @param  ProviderRawData $providerRawData
     *
     * @return NDO\SolrEvent\SearchFacetGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawData = $providerRawData->getRawDataArray();

        $ndo = new NDO\SolrEvent\SearchFacetGroup();
        $ndo->setRead(true);

        if (!empty($rawData)) {
            foreach ($rawData as $field => $items) {

                // Creating the provider raw data
                $providerData = new ProviderRawData([
                    'field' => $field,
                    'items' => $items
                ]);

                // Appending the NDO to the group
                $searchFacetTranslator = new SearchFacetTranslator();
                $ndo->append(
                    $searchFacetTranslator->translate($providerData)
                );
            }
        }

        return $ndo;
    }
}
