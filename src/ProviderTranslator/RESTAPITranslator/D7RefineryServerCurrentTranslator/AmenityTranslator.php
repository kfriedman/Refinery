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
class AmenityTranslator extends D7RefineryServerTranslator implements
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

        $ndo = new NDO\Content\Amenity($rawData['tid']);

        $ndo->setName($rawData['name']);
        $ndo->setAmenityID($rawData['tid']);
        $ndo->setSortOrder($rawData['weight']);

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

        if (isset($rawData[self::ENHANCED_DATA]['parent_tid'])) {
            $ndo->setParentAmenityID($rawData[self::ENHANCED_DATA]['parent_tid']);

            $ndo->setParent(new NDO\Content\Amenity($rawData[self::ENHANCED_DATA]['parent_tid']));
        }

        if (isset($rawData[self::ENHANCED_DATA]['amenities_locations'])) {
            $locationTIDs = array();

            foreach ($rawData[self::ENHANCED_DATA]['amenities_locations'] as $amenityLocation) {
                $locationTIDs[] = $amenityLocation['tid'];

                $ndo->addLocation(new NDO\Location($amenityLocation['tid']));
            }

            $ndo->setAmenityLocationIDArray($locationTIDs);
        }

        return $ndo;
    }
}
