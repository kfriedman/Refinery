<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\SolrEventTranslator;

use NYPL\Refinery\Exception\RefineryException;
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
class SearchTranslator extends SolrEventTranslator implements RESTAPITranslator\ReadInterface
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
        if (!$ndoFilter->getQueryParameter('filter')->getValue('q')) {
            throw new RefineryException('"filter[q]" parameter must be specified');
        }

        return $provider->clientGet($this->getUrlFromFilter($ndoFilter), null, null, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     * @return NDO\SolrEvent\Search
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawDataArray = $providerRawData->getRawDataArray();

        // Getting the query
        $q = $rawDataArray['responseHeader']['params']['q'];

        $ndo = new NDO\SolrEvent\Search();
        $ndo->setRead(true);

        // Setting the ID
        $ndo->setNdoID(md5($q));

        // Setting the query
        $ndo->setQ($q);

        // Setting the fq
        $ndo->setFq((array)$rawDataArray['responseHeader']['params']['fq']);

        // Setting the sort
        $ndo->setSort((array)$rawDataArray['responseHeader']['params']['sort']);

        // Setting the start
        $start = (int) $rawDataArray['response']['start'];
        $ndo->setStart($start);

        // Setting the rows
        if (!empty($rawDataArray['responseHeader']['params']['rows'])) {
            $rows = (int) $rawDataArray['responseHeader']['params']['rows'];
            $ndo->setRows($rows);
        }

        // Setting the facet fields
        if (!empty($rawDataArray['responseHeader']['params']['facet.field'])) {
            $ndo->setFacetFields((array) $rawDataArray['responseHeader']['params']['facet.field']);
        }

        // Setting the Search Metadata
        $this->setSearchMetadata($ndo, $providerRawData);

        // Setting the Events
        if (!empty($rawDataArray['response']['docs'])) {
            $eventGroupTranslator = new EventGroupTranslator();
            $ndo->setEvents(
                $eventGroupTranslator->translate(
                    new ProviderRawData($rawDataArray['response']['docs'])
                )
            );
        }

        // Setting the facets
        if (!empty($rawDataArray['facet_counts']['facet_fields'])) {
            $searchFacetGroupTranslator = new SearchFacetGroupTranslator();
            $ndo->setFacets(
                $searchFacetGroupTranslator->translate(
                    new ProviderRawData($rawDataArray['facet_counts']['facet_fields'])
                )
            );
        }

        return $ndo;
    }

    /**
     * @param NDO\SolrEvent\Search $ndo
     * @param ProviderRawData      $providerRawData
     */
    protected function setSearchMetadata(NDO\SolrEvent\Search $ndo, ProviderRawData $providerRawData)
    {
        $rawDataArray = $providerRawData->getRawDataArray();

        $searchMeta = $ndo->getMeta();

        // Setting the number of found documents
        $numFound = (int) $rawDataArray['response']['numFound'];
        $searchMeta->setNumFound($numFound);

        // Setting the start
        $start = $ndo->getStart();
        $searchMeta->setStart($start);
    }
}
