<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\GoogleSearchTranslator;

use GuzzleHttp\Exception\ClientException;
use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\GoogleSearchTranslator;
use NYPL\Refinery\NDO\Search\Search;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class GlobalSearchTranslator extends GoogleSearchTranslator implements RESTAPITranslator\ReadInterface
{
    const DEFAULT_SIZE = 10;

    const MAXIMUM_SIZE = 20;

    protected $badSearches = [
        'apachesolr_search', 'websql', 'myadmin', 'cgi', 'webdb', 'sqladmin', 'phpmy-admin', 'dbweb', 'phpmy-admin', 'pma', 'sqlmanager'
    ];

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

        $queryParameter = $ndoFilter->getQueryParameter('filter')->getValue('q');

        foreach ($this->badSearches as $badSearch) {
            if (strpos($queryParameter, $badSearch) !== false) {
                throw new RefineryException('Query parameter (' . $queryParameter . ') specified is not allowed');
            }
        }

        if (!$ndoFilter->getQueryParameter('filter')->getValue('size')) {
            $ndoFilter->getQueryParameter('filter')->addValue(array('size' => self::DEFAULT_SIZE));
        } else {
            if ($ndoFilter->getQueryParameter('filter')->getValue('size') > self::MAXIMUM_SIZE) {
                throw new RefineryException(
                    'Size parameter specified exceeds maximum size (' . self::MAXIMUM_SIZE . ')'
                );
            }
        }

        try {
            return $provider->clientGet($this->getUrlFromFilter($ndoFilter), null, null, $allowEmptyResults);
        } catch (RefineryException $exception) {
            $addedMessage = json_decode($exception->getAddedMessage()[0], true);

            if (isset($addedMessage['error']['message'])) {
                throw new RefineryException($addedMessage['error']['message']);
            }

            throw new RefineryException('Unable to complete search as Google API returned status code ' . $exception->getStatusCode());
        }
    }

    /**
     * @param ProviderRawData $providerRawData
     * @param string          $correctedQuery
     *
     * @return ProviderRawData
     * @throws RefineryException
     */
    protected function runCorrectedSearch(ProviderRawData $providerRawData, $correctedQuery = '')
    {
        $providerRawData->getNdoFilter()->getQueryParameter('filter')->updateValue('q', $correctedQuery);

        $newProviderRawData = new ProviderRawData(
            $this->read($providerRawData->getProvider(), $providerRawData->getNdoFilter())
        );

        $newProviderRawData->setNdoFilter($providerRawData->getNdoFilter());

        return $newProviderRawData;
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\Search\GlobalSearch
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\Search\GlobalSearch($providerRawData->getNdoFilter()->getFilterID());

        $ndo->setQ($providerRawData->getNdoFilter()->getQueryParameter('filter')->getValue('q'));

        $ndo->setSearchType($providerRawData->getNdoFilter()->getQueryParameter('filter')->getValue('search-type'));

        $ndo->setSort($providerRawData->getNdoFilter()->getQueryParameter('filter')->getValue('sort'));

        $ndo->setSize($providerRawData->getNdoFilter()->getQueryParameter('filter')->getValue('size'));

        $rawData = $providerRawData->getRawDataArray();

        if (isset($rawData['spelling'])) {
            $suggestion = $rawData['spelling'];

            $ndo->getMeta()->setCorrectedQuery($suggestion['correctedQuery']);
            $ndo->getMeta()->setHtmlCorrectedQuery($suggestion['htmlCorrectedQuery']);

            $providerRawData = $this->runCorrectedSearch($providerRawData, $suggestion['correctedQuery']);

            $rawData = $providerRawData->getRawDataArray();
        }

        if (!isset($rawData['items'])) {
            $ndo->getMeta()->setTotalResults(0);
        } else {
            $resultSet = $rawData['items'];
            $context = $rawData['context'];

            $ndo->setStart($providerRawData->getNdoFilter()->getQueryParameter('filter')->getValue('start'));

            if ($context['facets']) {
                $facets = array();

                foreach ($context['facets'] as $facetGroup) {
                    foreach ($facetGroup as $facet) {
                        $facets[$facet['label']] = $facet;
                    }
                }

                $searchFacetGroupTranslator = new SearchFacetGroupTranslator();

                $ndo->setFacets($searchFacetGroupTranslator->translate(
                    new ProviderRawData($facets)
                ));
            }

            if ($ndo->getActiveFacet()) {
                /**
                 * @var NDO\Search\SearchFacet $activeSearchFacet
                 */
                $activeSearchFacet = $ndo->getFacets()->searchItemsByIndex('label_with_op', $ndo->getActiveFacet())->current();

                $ndo->getMeta()->setTotalResults((int) $rawData['searchInformation']['totalResults']);
            } else {
                $ndo->getMeta()->setTotalResults((int) $rawData['searchInformation']['totalResults']);
            }

            if (isset($rawData['queries']['request'])) {
                $ndo->getMeta()->setCount($rawData['queries']['request'][0]['count']);
            }

            $results = array();

            $results['facets'] = $facets;

            foreach ($resultSet as $result) {
                $results['results'][] = $result;
            }

            $searchItemGroupTranslator = new SearchItemGroupTranslator();

            $ndo->setItems($searchItemGroupTranslator->translate(
                new ProviderRawData($results)
            ));
        }

        return $ndo;
    }
}
