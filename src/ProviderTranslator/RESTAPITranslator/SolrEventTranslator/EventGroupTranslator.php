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
class EventGroupTranslator extends SolrEventTranslator implements RESTAPITranslator\ReadInterface
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
     * @return NDO\SolrEvent\EventGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\SolrEvent\EventGroup();
        $ndo->setRead(true);

        foreach ($providerRawData->getRawDataArray() as $item) {
            $eventTranslator = new EventTranslator();
            $ndo->append($eventTranslator->translate(new ProviderRawData($item)));
        }

        return $ndo;
    }
}
