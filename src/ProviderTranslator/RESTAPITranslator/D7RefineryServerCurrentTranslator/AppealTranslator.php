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
class AppealTranslator extends D7RefineryServerTranslator implements
  RESTAPITranslator\ReadInterface
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
        return $provider->clientGet('node/appeal/' . $ndoFilter->getFilterID(), null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\Content\Appeal
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawData = $providerRawData->getRawDataArray();

        $ndo = new NDO\Content\Appeal($rawData['nid']);

        $ndo->setTitle($rawData['title']);
        $ndo->setAppealId((int) $rawData['nid']);
        $ndo->setStatement($rawData['title']);
        $ndo->setTitle($rawData['body']['und'][0]['safe_value']);
        $ndo->setButtonTitle($rawData['field_appeal_button']['und'][0]['title']);
        $ndo->setButtonLink(new NDO\URI($rawData['field_appeal_button']['und'][0]['url']));

        $this->setNDOGroupFromField($providerRawData, 'field_appeal_location', $ndo->getLocations(), new NDO\Location());

        $this->doCommonNodeTranslate($providerRawData, $ndo);

        return $ndo;
    }

}
