<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerNewTranslator;

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
class NodeTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
        return $provider->clientGet('node/page/' . $ndoFilter->getFilterID(), null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\Content\Node
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\Content\Node($this->getValueFromRawData($providerRawData, 'nid'));

        $ndo->setTitle($this->getTextGroupFromRawData($providerRawData, 'title_field'));

        $ndo->setBody($this->getTextGroupFromRawData($providerRawData, 'body', 'safe_value', 'safe_summary'));

        if ($uri = $this->getValueFromRawData($providerRawData, 'uri_absolute', null, true)) {
            $ndo->setURI(new NDO\URI($uri));
        }

        return $ndo;
    }
}