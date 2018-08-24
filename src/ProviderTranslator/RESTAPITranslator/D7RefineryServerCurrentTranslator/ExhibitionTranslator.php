<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerCurrentTranslator;

use NYPL\Refinery\Helpers\DebugHelper;
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
class ExhibitionTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
        return $provider->clientGet('node/event_exhibition/' . $ndoFilter->getFilterID(), null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\Event\Exhibition
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawData = $providerRawData->getRawDataArray();

        $ndo = new NDO\Event\Exhibition($rawData['nid']);

        $ndo->setRead(true);

        $ndo->setUri(new NDO\URI($rawData[self::ENHANCED_DATA]['uri_absolute']));

        if ($rawData['body']) {
            $ndo->setDescriptionFull($rawData['body']['und'][0]['safe_value']);
        }

        $ndo->setDescriptionShort($rawData[self::ENHANCED_DATA]['summary']);
        $ndo->setName($rawData['title']);
        $ndo->setEventID($rawData['nid']);

        if (isset($rawData[self::ENHANCED_DATA]['field_project_image'])) {
            $ndo->setImage(new NDO\Content\Image(null, new NDO\URI($rawData[self::ENHANCED_DATA]['field_project_image'])));
        }

        if (isset($rawData[self::ENHANCED_DATA]['event_status_other'])) {
            if ($rawData[self::ENHANCED_DATA]['event_status_other'] == 'Ongoing') {
                $ndo->setOngoing(true);
            }
        }

        if ($rawData[self::ENHANCED_DATA]['date_start']) {
            $ndo->setStartDate(new NDO\LocalDateTime($rawData[self::ENHANCED_DATA]['date_start']));
        }

        if ($rawData[self::ENHANCED_DATA]['date_end'] && !$ndo->isOngoing()) {
            $ndo->setEndDate(new NDO\LocalDateTime($rawData[self::ENHANCED_DATA]['date_end']));
        }

        return $ndo;
    }
}
