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
class StaffTranslator extends D7RefineryServerTranslator implements
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
        return $provider->clientGet('user/' . $ndoFilter->getFilterID(), null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\Person\Staff
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawData = $providerRawData->getRawDataArray();

        $ndo = new NDO\Person\Staff($rawData['uid']);

        $ndo->setRead(true);

        if (isset($rawData['mail'])) {
            $ndo->setEmail($rawData['mail']);
        }

        if (isset($rawData['field_first_name']['und'][0]['value']) && isset($rawData['field_last_name']['und'][0]['value'])) {
            $ndo->setFullName($rawData['field_first_name']['und'][0]['value'] . ' ' . $rawData['field_last_name']['und'][0]['value']);
        } else {
            if (isset($rawData['profile_author_name'])) {
                $ndo->setFullName($rawData['profile_author_name']);
            }
        }

        if (isset($rawData['field_title_position']['und'][0]['value'])) {
            $ndo->setTitle($rawData['field_title_position']['und'][0]['value']);
        } else {
            if (isset($rawData['profile_position'])) {
                $ndo->setTitle($rawData['profile_position']);
            }
        }

        if (isset($rawData['profile_phone'])) {
            $ndo->setPhone($rawData['profile_phone']);
        }

        return $ndo;
    }
}
