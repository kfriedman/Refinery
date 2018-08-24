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
class LocationAmenityTranslator extends D7RefineryServerTranslator implements
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
        return $provider->clientGet('taxonomy/term/amenities/' . $ndoFilter->getFilterID(), null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\Content\Amenity\LocationAmenity
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawData = $providerRawData->getRawDataArray();

        $ndo = new NDO\Content\Amenity\LocationAmenity($rawData['tid']);

        $ndo->setName($rawData['name']);
        $ndo->setAmenityID($rawData['tid']);

        if ($rawData['field_rank']) {
            $ndo->setSortOrder($rawData['field_rank']['und'][0]['value']);
        }

        if ($rawData['field_amenity_action_item']) {
            $ndo->setActionName($rawData['field_amenity_action_item']['und'][0]['title']);
        }

        if (isset($rawData[self::ENHANCED_DATA]['field_amenity_action_item_absolute'])) {
            $ndo->setActionURI(new NDO\URI($rawData[self::ENHANCED_DATA]['field_amenity_action_item_absolute']));
        }

        if ($rawData['field_amenity_info']) {
            $ndo->setInfoLabel($rawData['field_amenity_info']['und'][0]['title']);
        }

        if (isset($rawData[self::ENHANCED_DATA]['field_amenity_info_absolute'])) {
            $ndo->setInfoURI(new NDO\URI($rawData[self::ENHANCED_DATA]['field_amenity_info_absolute']));
        }

        if (isset($rawData[self::ENHANCED_DATA]['parent_name'])) {
            $ndo->setParentName($rawData[self::ENHANCED_DATA]['parent_name']);
        }

        if ($queryParameter = $providerRawData->getNdoFilter()->getQueryParameter('filter[_enhanced][amenities_locations][tid]')) {
            $locationTid = $queryParameter->getValue();

            if ($locationTid) {
                $locationAmenityRowNumber = array_search($locationTid, array_column($rawData[self::ENHANCED_DATA]['amenities_locations'], 'tid'));

                if ($locationAmenityRowNumber !== false) {
                    $locationAmenityRawData = $rawData[self::ENHANCED_DATA]['amenities_locations'][$locationAmenityRowNumber];

                    $ndo->setLocationSortOrder($locationAmenityRawData['weight']);

                    switch ($locationAmenityRawData['access']) {
                        case 'yes':
                            $ndo->setAccessible(true);
                            break;
                        case 'no':
                            $ndo->setAccessible(false);
                            break;
                        default:
                            $ndo->setAccessible($locationAmenityRawData['access']);
                    }

                    $ndo->setStaffAssistanceRequired(boolval($locationAmenityRawData['assist']));
                    $ndo->setAccessibilityNote($locationAmenityRawData['access_note']);
                }
            }
        }

        return $ndo;
    }
}
