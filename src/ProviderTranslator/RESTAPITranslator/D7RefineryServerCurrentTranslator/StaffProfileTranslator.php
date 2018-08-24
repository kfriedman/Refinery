<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerCurrentTranslator;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class StaffProfileTranslator extends ProfileTranslator
{
    /**
     * @return NDO\StaffProfile
     */
    public function getNDO()
    {
        return new NDO\StaffProfile();
    }

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
        return $provider->clientGet('profile/subject/' . $ndoFilter->getFilterID(), null, null, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\StaffProfile
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = parent::translate($providerRawData);

        $rawData = $providerRawData->getRawDataArray();

        $ndo->setPerson(new NDO\Person($rawData['person_id']));

        if ($rawData['field_person_use_profile']) {
            $ndo->setUseContactInfo($rawData['field_person_use_profile']['und'][0]['value']);
        }

        if ($rawData['field_profile_subject_biography']) {
            $ndo->setProfileText(
                new NDO\TextGroup(
                    array(new NDO\Text\TextMulti($rawData['field_profile_subject_biography']['und'][0]['value']))
                )
            );
        }

        return $ndo;
    }
}
