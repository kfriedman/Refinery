<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerCurrentTranslator;

use NYPL\Refinery\NDO;
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
abstract class ProfileGroupTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
{
    /**
     * @return NDO\StaffProfileGroup|NDO\Blog\BloggerProfileGroup
     */
    abstract public function getNDOGroup();

    /**
     * @return StaffProfileTranslator|BloggerProfileTranslator
     */
    abstract public function getTranslator();

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\StaffProfileGroup|NDO\Blog\BloggerProfileGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        return $this->getNDOGroupFromRawData($providerRawData, $this->getNDOGroup(), $this->getTranslator());
    }
}
