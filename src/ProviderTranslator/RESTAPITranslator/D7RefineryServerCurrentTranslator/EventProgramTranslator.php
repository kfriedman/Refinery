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
class EventProgramTranslator extends D7RefineryServerTranslator implements
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
        return $provider->clientGet('node/event_program/' . $ndoFilter->getFilterID(), null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\Content\Node\EventProgram
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawData = $providerRawData->getRawDataArray();

        $ndo = new NDO\Content\Node\EventProgram($rawData['nid']);

        $ndo->setUri(new NDO\URI($rawData[self::ENHANCED_DATA]['uri_absolute']));

        $ndo->setName(new NDO\TextGroup(new NDO\Text\TextSingle($rawData['title'])));

        if (isset($rawData['body']['und'])) {
            $ndo->setDescription(new NDO\TextGroup(new NDO\Text\TextMulti($rawData['body']['und'][0]['value'], $rawData[self::ENHANCED_DATA]['summary'])));
        }

        if ($rawData['field_program_image']) {
            $image = new NDO\Content\Image($rawData['field_program_image']['und'][0]['fid']);
            $ndo->setImage($image);
        }

        if ($rawData[self::ENHANCED_DATA]['date_start']) {
            $ndo->setStartDate(new NDO\LocalDateTime($rawData[self::ENHANCED_DATA]['date_start']));
        }

        if ($rawData[self::ENHANCED_DATA]['date_end']) {
            $ndo->setEndDate(new NDO\LocalDateTime($rawData[self::ENHANCED_DATA]['date_end']));
        }

        $ndo->setEventStatus($rawData[self::ENHANCED_DATA]['event_status']);

        if (isset($rawData[self::ENHANCED_DATA]['registration_type'])) {
            $ndo->setRegistrationType($rawData[self::ENHANCED_DATA]['registration_type']);
        }

        if (isset($rawData[self::ENHANCED_DATA]['registration_open'])) {
            $ndo->setRegistrationOpen(new NDO\LocalDateTime($rawData[self::ENHANCED_DATA]['registration_open']['value'], new \DateTimeZone($rawData[self::ENHANCED_DATA]['registration_open']['timezone_db'])));
        }

        if (isset($rawData[self::ENHANCED_DATA]['space_name'])) {
            $ndo->setSpaceName($rawData[self::ENHANCED_DATA]['space_name']);
        }

        if (isset($rawData[self::ENHANCED_DATA]['location_tid'])) {
            $location = new NDO\Location($rawData[self::ENHANCED_DATA]['location_tid']);

            if ($providerRawData->getProvider()) {
                $location->setProvider($providerRawData->getProvider());
            }

            $ndo->setLocation($location);
        }

        return $ndo;
    }
}
