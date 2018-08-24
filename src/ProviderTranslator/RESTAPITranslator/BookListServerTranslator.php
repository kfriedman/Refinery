<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOGroup;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslatorInterface;

/**
 * RESTAPITranslator Interface to support UPDATE operations for NDOs.
 *
 * @package NYPL\Refinery
 */
abstract class BookListServerTranslator implements ProviderTranslatorInterface
{
    /**
     * Timezone to use for update operations.
     */
    const TIMEZONE = 'America/New_York';

    /**
     * Getter for the timezone constant.
     *
     * @return string
     */
    public function getTimeZone()
    {
        return self::TIMEZONE;
    }

    /**
     * @param ProviderRawData $providerRawData
     * @param string          $fieldName
     *
     * @return null
     */
    public function getValueFromRawData(ProviderRawData $providerRawData, $fieldName = '')
    {
        $rawDataArray = $providerRawData->getRawDataArray();

        if (isset($rawDataArray[$fieldName])) {
            return $rawDataArray[$fieldName];
        }

        return null;
    }

    /**
     * @param ProviderRawData             $providerRawData
     * @param NDOGroup                    $ndoGroup
     * @param ProviderTranslatorInterface $providerTranslator
     *
     * @return NDOGroup
     */
    protected function getNDOGroupFromRawData(ProviderRawData $providerRawData, NDOGroup $ndoGroup, ProviderTranslatorInterface $providerTranslator)
    {
        $rawDataArray = $providerRawData->getRawDataArray();

        foreach ($rawDataArray as $rawData) {
            $newProviderRawData = new ProviderRawData($rawData);
            $newProviderRawData->setProvider($providerRawData->getProvider());

            $ndo = $providerTranslator->translate($newProviderRawData);
            $ndo->setRead(true);

            $ndoGroup->append($ndo);
        }

        return $ndoGroup;
    }
}