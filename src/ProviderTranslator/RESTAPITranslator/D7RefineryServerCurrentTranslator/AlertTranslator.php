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
class AlertTranslator extends D7RefineryServerTranslator implements
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
        return $provider->clientGet('node/alert_message/' . $ndoFilter->getFilterID(), null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\Content\Alert
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawData = $providerRawData->getRawDataArray();

        $ndo = new NDO\Content\Alert();

        $ndo->setNdoID((int) $rawData['nid']);

        $ndo->setScope($rawData['field_alert_type']['und'][0]['value']);
        $ndo->setParentAlertSetArray($rawData[self::ENHANCED_DATA]['parent_has_alert']);

        $ndo->setUri(new NDO\URI($rawData[self::ENHANCED_DATA]['uri_absolute']));
        $ndo->setMessage($rawData['body']['und'][0]['safe_value']);

        if ($rawData['field_alert_closed_for']) {
            $ndo->setClosedMessage($rawData['field_alert_closed_for']['und'][0]['value']);
        }

        $displayDateStart = new NDO\LocalDateTime($rawData['field_date_range']['und'][0]['value'], new \DateTimeZone($rawData['field_date_range']['und'][0]['timezone']));

        $ndo->setDisplayDateStart($displayDateStart);

        $displayDateEnd = new NDO\LocalDateTime($rawData['field_date_range']['und'][0]['value2'], new \DateTimeZone($rawData['field_date_range']['und'][0]['timezone']));

        if ($displayDateStart->getDateTime()->format('H:i') == '00:00' &&  $displayDateEnd->getDateTime()->format('H:i') == '00:00') {
            $displayDateEnd->getDateTime()->add(new \DateInterval('P1D'));
        }

        $ndo->setDisplayDateEnd($displayDateEnd);

        if ($rawData['field_date_range_applies']) {
            $closingDateStart = new NDO\LocalDateTime($rawData['field_date_range_applies']['und'][0]['value'], new \DateTimeZone($rawData['field_date_range_applies']['und'][0]['timezone']));

            $ndo->setClosingDateStart($closingDateStart);

            $closingDateEnd = new NDO\LocalDateTime($rawData['field_date_range_applies']['und'][0]['value2'], new \DateTimeZone($rawData['field_date_range_applies']['und'][0]['timezone']));

            if ($closingDateStart->getDateTime()->format('H:i') == '00:00' &&  $closingDateEnd->getDateTime()->format('H:i') == '00:00') {
                $closingDateEnd->getDateTime()->add(new \DateInterval('P1D'));
            }

            $ndo->setClosingDateEnd($closingDateEnd);
        }

        if ($rawData['field_alert_locations']) {
            foreach ($rawData['field_alert_locations']['und'] as $locationArray) {
                $ndo->addLocation(new NDO\Location($locationArray['value']));
            }
        }

        $this->doCommonNodeTranslate($providerRawData, $ndo);

        return $ndo;
    }
}
