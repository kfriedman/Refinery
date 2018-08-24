<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerCurrentTranslator;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderHandler\NDOReader;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerTranslator;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class AuthorTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
        $ndo = new NDO\Person\Author();

        $ndo->setNdoID($this->getValueFromRawData($providerRawData, 'person_id'));

        $ndo->setFirstName($this->getValueFromRawData($providerRawData, 'first_name'));
        $ndo->setLastName($this->getValueFromRawData($providerRawData, 'last_name'));

        $ndo->setPhone($this->getValueFromRawData($providerRawData, 'field_person_phone'));
        $ndo->setTitle($this->getValueFromRawData($providerRawData, 'field_person_position'));
        $ndo->setEmail($this->getValueFromRawData($providerRawData, 'field_person_email', 'email'));

        $staffProfileFilter = new NDOFilter();
        $staffProfileFilter->addQueryParameter('filter', array('person_id' => $this->getValueFromRawData($providerRawData, 'person_id')));

        /**
         * @var NDO\StaffProfileGroup $staffProfileGroupNDO
         */
        if ($staffProfileGroupNDO = NDOReader::readNDO($providerRawData->getProvider(), new NDO\StaffProfileGroup(), $staffProfileFilter, null, true)) {
            /**
             * @var NDO\StaffProfile $staffProfileNDO
             */
            $staffProfileNDO = $staffProfileGroupNDO->items->current();
            $staffProfileNDO->setProvider(new RESTAPI\D7RefineryServerCurrent());

            $ndo->setStaffProfile($staffProfileGroupNDO->items->current());
        }

        if ($imageID = $this->getValueFromRawData($providerRawData, 'field_person_headshot', 'fid')) {
            $image = new NDO\Content\Image($imageID);
            $image->setProvider(new RESTAPI\D7RefineryServerCurrent());

            $ndo->setHeadshot($image);
        }

        return $ndo;
    }
}