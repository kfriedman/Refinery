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
class SearchFacetTranslator extends SolrEventTranslator implements RESTAPITranslator\ReadInterface
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
     * @return NDO\SolrEvent\SearchFacet
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawDataArray = $providerRawData->getRawDataArray();

        $field = $rawDataArray['field'];
        $items = $rawDataArray['items'];

        $ndo = new NDO\SolrEvent\SearchFacet($field);
        $ndo->setRead(true);

        if (!empty($items)) {

            $facets = array();

            $count = count($items);
            for ($i = 0; $i < $count; $i+=2) {

                $name = $items[$i];

                $facets[] = [
                    'name'   => $name,
                    'count'  => $items[$i+1],
                    'filter' => $field.':'.$name,
                ];
            }

            $ndo->setItems($facets);
        }

        return $ndo;
    }
}

