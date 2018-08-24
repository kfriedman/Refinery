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
class StaffProfileGroupTranslator extends ProfileGroupTranslator
{
    /**
     * @return NDO\StaffProfileGroup
     */
    public function getNDOGroup()
    {
        return new NDO\StaffProfileGroup();
    }

    /**
     * @return StaffProfileTranslator
     */
    public function getTranslator()
    {
        return new StaffProfileTranslator();
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
        return $provider->clientGet('profile/subject', null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\StaffProfileGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        return parent::translate($providerRawData);
    }
}
