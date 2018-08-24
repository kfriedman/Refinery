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
class EventTranslator extends D7RefineryServerTranslator implements
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
     * @return NDO\Event
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawData = $providerRawData->getRawDataArray();

        $ndo = new NDO\Event();

        $ndo->setUri(new NDO\URI($rawData[self::ENHANCED_DATA]['uri_absolute']));

        if ($rawData['body']) {
            $ndo->setDescriptionFull($rawData['body']['und'][0]['value']);
        }

        if (isset($rawData[self::ENHANCED_DATA]['summary'])) {
            $ndo->setDescriptionShort($rawData[self::ENHANCED_DATA]['summary']);
        }

        $ndo->setName($rawData['title']);
        $ndo->setNdoID($rawData['nid']);
        $ndo->setEventID($rawData['nid']);

        if (isset($rawData[self::ENHANCED_DATA]['field_program_image'])) {
            $ndo->setImage(new NDO\Content\Image(null, new NDO\URI($rawData[self::ENHANCED_DATA]['field_program_image'])));
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

        return $ndo;
    }
}