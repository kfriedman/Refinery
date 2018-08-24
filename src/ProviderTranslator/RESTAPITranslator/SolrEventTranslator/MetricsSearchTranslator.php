<?php

namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\SolrEventTranslator;

use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\MetricsEventTranslator;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class MetricsSearchTranslator extends MetricsEventTranslator implements RESTAPITranslator\ReadInterface
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
        $result = $provider->clientGet($this->getUrlFromFilter($ndoFilter), null, null, $allowEmptyResults);

        $result = json_decode($result, true);

        if (empty($result['response'])) {
            return null;
        }

        // Getting the number of found documents
        $numFound = (int) $result['response']['numFound'];

        if ($numFound > self::CSV_MAX_ROWS) {

            $numChunks = ceil($numFound / self::CSV_MAX_ROWS);

            for ($i = 1; $i < $numChunks; $i++) {

                $response = $provider->clientGet($this->getUrlFromFilter($ndoFilter, $i * self::CSV_MAX_ROWS), null, null, $allowEmptyResults);

                if (!empty($response['response']['numFound'])) {
                    $result['response']['docs'] = array_merge($result['response']['docs'], $response['response']['docs']);
                }
            }
        }

        // Setting the effective row count value
        $result['responseHeader']['params']['rows'] = $numFound;

        return $result;
    }

    /**
     * @param  ProviderRawData $providerRawData
     * @return NDO\SolrEvent\Search
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawDataArray = $providerRawData->getRawDataArray();

        $ndo = new NDO\SolrEvent\MetricsSearch();
        $ndo->setRead(true);

        // Getting the year and month
        $parameters = $providerRawData
            ->getNdoFilter()
            ->getQueryParameter('filter')
            ->getValue();

        // Setting the year
        $ndo->setYear($parameters['year']);

        // Setting the month
        $ndo->setMonth($parameters['month']);

        // Setting the status
        $ndo->setStatus(
            isset($parameters['status']) ? $parameters['status'] : self::DEFAULT_STATUS
        );

        // Setting the Event Metrics
        if (!empty($rawDataArray['response']['docs'])) {
            $translator = new EventMetricsGroupTranslator();

            $ndo->setEventMetrics(
                $translator->translate(
                    new ProviderRawData($rawDataArray['response']['docs'])
                )
            );
        }

        // Checks if the output must deliver a CSV file
        if ($this->deliverAsCsvFile()) {
            $this->createCsvFile($ndo);
        }

        return $ndo;
    }
}
