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
class PersonTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
        return $provider->clientGet('person/' . $ndoFilter->getFilterID(), null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\Person
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\Person($this->getValueFromRawData($providerRawData, 'person_id'));

        $ndo->setRead(true);

        $ndo->setFirstName($this->getValueFromRawData($providerRawData, 'first_name'));
        $ndo->setLastName($this->getValueFromRawData($providerRawData, 'last_name'));

        if ($phone = $this->getValueFromRawData($providerRawData, 'field_person_phone')) {
            $ndo->setPhone($phone);
        }

        if ($title = $this->getValueFromRawData($providerRawData, 'field_person_position')) {
            $ndo->setTitle($title);
        }

        if ($email = $this->getValueFromRawData($providerRawData, 'field_person_email', 'email')) {
            $ndo->setEmail($email);
        }

        if ($unit = $this->getValueFromRawData($providerRawData, 'field_person_unit')) {
            $ndo->setUnit($unit);
        }

        if ($locationID = $this->getValueFromRawData($providerRawData, 'field_person_nypl_location', 'target_id')) {
            $library = new NDO\Location\Library($locationID);
            $library->setProvider($providerRawData->getProvider());

            $ndo->setNyplLocation($library);
        }

        if ($headshotFid = $this->getValueFromRawData($providerRawData, 'field_person_headshot', 'fid')) {
            $headShotNdo = new NDO\Content\Image($headshotFid);
            $headShotNdo->setProvider(new RESTAPI\D7RefineryServerCurrent());

            $ndo->setHeadshot($headShotNdo);
        }

        return $ndo;
    }
}
