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
abstract class D7RefineryServerTranslator implements ProviderTranslatorInterface
{
    /**
     * Name of key that contains enhanced data.
     */
    const ENHANCED_DATA = '_enhanced';

    /**
     * Timezone to use for update operations.
     */
    const TIMEZONE = 'America/New_York';

    const UNDEFINED_LANGUAGE_CODE = 'und';

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
     * @param array|string    $fieldName
     * @param string          $valueKey
     * @param bool            $useEnhanced
     *
     * @return array|string
     */
    public function getValueFromRawData(ProviderRawData $providerRawData, $fieldName, $valueKey = 'value', $useEnhanced = false)
    {
        $rawDataArray = $providerRawData->getRawDataArray();

        if (is_array($fieldName)) {
            if ($useEnhanced) {
                array_unshift($fieldName, self::ENHANCED_DATA);
            }

            $fields = $fieldName;

            array_walk($fields, function(&$item, $key, &$rawDataArray) {
                if (isset($rawDataArray[$item])) {
                    $rawDataArray = $rawDataArray[$item];
                    $item = $rawDataArray;
                } else {
                    $item = null;
                }
            }, $rawDataArray);

            return $fields[count($fieldName) - 1];
        } else {
            if ($useEnhanced) {
                if (isset($rawDataArray[self::ENHANCED_DATA])) {
                    $rawDataArray = $rawDataArray[self::ENHANCED_DATA];
                } else {
                    return false;
                }
            }

            if (isset($rawDataArray[$fieldName]['en'])) {
                if ($valueKey) {
                    if (count($rawDataArray[$fieldName]['en']) > 1) {
                        return array_column($rawDataArray[$fieldName]['en'], $valueKey);
                    } else {
                        return $rawDataArray[$fieldName]['en'][0][$valueKey];
                    }
                } else {
                    return $rawDataArray[$fieldName]['en'];
                }
            }

            if (isset($rawDataArray[$fieldName]['und'])) {
                if ($valueKey) {
                    if (count($rawDataArray[$fieldName]['und']) > 1) {
                        return array_column($rawDataArray[$fieldName]['und'], $valueKey);
                    } else {
                        if (isset($rawDataArray[$fieldName]['und'][0][$valueKey])) {
                            return $rawDataArray[$fieldName]['und'][0][$valueKey];
                        } else {
                            return null;
                        }
                    }
                } else {
                    return $rawDataArray[$fieldName]['und'];
                }
            }

            if (isset($rawDataArray[$fieldName])) {
                if ($rawDataArray[$fieldName]) {
                    return $rawDataArray[$fieldName];
                }
            }
        }

        return '';
    }

    /**
     * @param ProviderRawData $providerRawData
     * @param string          $fieldName
     * @param string          $valueKey
     * @param string          $multiValueKey
     *
     * @return null|NDO\TextGroup
     */
    protected function getTextGroupFromRawData(ProviderRawData $providerRawData, $fieldName = '', $valueKey = 'value', $multiValueKey = '')
    {
        $allRawData = $providerRawData->getRawDataArray();

        if (isset($allRawData[$fieldName])) {
            $ndoArray = array();

            $rawDataArray = $allRawData[$fieldName];

            if (!is_array($rawDataArray)) {
                $rawDataArray = array(self::UNDEFINED_LANGUAGE_CODE => array(0 => array($valueKey => $rawDataArray)));
            }

            foreach ($rawDataArray as $languageCode => $rawData) {
                if (count($rawDataArray) == 1 || count($rawDataArray) > 1 && $languageCode != self::UNDEFINED_LANGUAGE_CODE) {
                    if ($multiValueKey) {
                        $ndo = new NDO\Text\TextMulti();
                        if (isset($rawData[0][$valueKey]) && isset($rawData[0][$multiValueKey])) {
                            $ndo->setFullText($rawData[0][$valueKey]);
                            $ndo->setShortText($rawData[0][$multiValueKey]);
                        }
                    } else {
                        $ndo = new NDO\Text\TextSingle();
                        if (isset($rawData[0][$valueKey])) {
                            $ndo->setText($rawData[0][$valueKey]);
                        }
                    }

                    if ($languageCode != self::UNDEFINED_LANGUAGE_CODE) {
                        $ndo->setLanguageCode($languageCode);
                    }

                    $ndoArray[] = $ndo;
                }
            }

            return new NDO\TextGroup($ndoArray);
        } else {
            return null;
        }
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

    /**
     * @param ProviderRawData $providerRawData
     * @param string          $fieldName
     * @param NDOGroup        $ndoGroup
     * @param NDO             $ndo
     * @param string          $valueName
     *
     * @return bool|NDOGroup
     */
    protected function setNDOGroupFromField(ProviderRawData $providerRawData = null, $fieldName = '', NDOGroup $ndoGroup = null, NDO $ndo = null, $valueName = '')
    {
        $rawData = $providerRawData->getRawDataArray();

        if (isset($rawData[$fieldName]['und'])) {
            if (!$valueName) {
                $valueName = 'value';
            }

            foreach ($rawData[$fieldName]['und'] as $value) {
                $ndo = clone $ndo;

                $ndo->setNdoID($value[$valueName]);

                $ndoGroup->append($ndo);
            }
        }

        if ($ndoGroup->items) {
            return $ndoGroup;
        } else {
            return false;
        }
    }

    /**
     * @param ProviderRawData                       $providerRawData
     * @param NDO\Content\Node|NDO\Content\Page|NDO $ndo
     */
    protected function doCommonNodeTranslate(ProviderRawData $providerRawData, NDO $ndo)
    {
        $ndo->setDateCreated(new NDO\LocalDateTime('@' . $this->getValueFromRawData($providerRawData, 'created')));

        $ndo->setDateModified(new NDO\LocalDateTime('@' . $this->getValueFromRawData($providerRawData, 'changed')));
    }
}
