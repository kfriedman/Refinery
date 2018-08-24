<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerNewTranslator;

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
class ScheduledFeaturedItemTranslator extends FeaturedItemTranslator implements RESTAPITranslator\ReadInterface
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
        return $provider->clientGet('node/scheduled_featured_item/' . $ndoFilter->getFilterID(), null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\SiteDatum\ScheduledFeaturedItem
     */
    public function translate(ProviderRawData $providerRawData)
    {
        if (!$this->getReturnNDO()) {
            $this->setReturnNDO(new NDO\SiteDatum\ScheduledFeaturedItem());
        }

        /**
         * @var NDO\SiteDatum\ScheduledFeaturedItem $ndo
         */
        $ndo = parent::translate($providerRawData);

        // Setting the Featured Item
        if ($featuredItemUUID = $this->getValueFromRawData($providerRawData, 'field_rel_featured_item', 'uuid')) {
            $ndo->setFeaturedItem(new NDO\SiteDatum\FeaturedItem($featuredItemUUID));
        }

        // Setting the Schedule
        if ($schedules = $this->getValueFromRawData($providerRawData, 'field_sfi_schedule', null)) {
            $scheduleGroup = new NDO\SiteDatum\ScheduleGroup();

            foreach ($schedules as $sfiSchedule) {
                if ($sfiSchedule['field_sfi_date']['und'] && $sfiSchedule['field_sfi_slot']['und']) {
                    $beginTime = new NDO\LocalDateTime($sfiSchedule['field_sfi_date']['und'][0]['value'], new \DateTimeZone($sfiSchedule['field_sfi_date']['und'][0]['timezone_db']));
                    $endTime = new NDO\LocalDateTime($sfiSchedule['field_sfi_date']['und'][0]['value2'], new \DateTimeZone($sfiSchedule['field_sfi_date']['und'][0]['timezone_db']));

                    $endTime->fixDrupalEndTime($beginTime);

                    if ($endTime->isCurrent()) {
                        $schedule = new NDO\SiteDatum\Schedule();

                        $schedule->setSlotUUID($sfiSchedule['field_sfi_slot']['und'][0]['uuid']);
                        $schedule->setBeginDateTime($beginTime);
                        $schedule->setEndDateTime($endTime);

                        $scheduleGroup->append($schedule);
                    }
                }
            }

            $ndo->setCurrentSchedules($scheduleGroup);
        }

        return $ndo;
    }
}
