<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\SolrEventTranslator;

use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\MetricsEventTranslator;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class EventMetricsTranslator extends MetricsEventTranslator implements RESTAPITranslator\ReadInterface
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
        return null;
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\SolrEvent\EventMetrics
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawDataArray = $providerRawData->getRawDataArray();

        $ndo = new NDO\SolrEvent\EventMetrics();
        $ndo->setRead(true);

        // Setting the metrics_id field
        $ndo->setMetricsId($rawDataArray['metrics_id']);

        // Setting the delta field
        $ndo->setDelta($rawDataArray['delta']);

        // Setting the nid field
        $ndo->setNid($rawDataArray['nid']);

        // Setting the language field
        $ndo->setLanguage($rawDataArray['language']);

        // Setting the title
        if (isset($rawDataArray['title'])) {
            $ndo->setTitle($rawDataArray['title']);
        }

        // Setting the status
        if (isset($rawDataArray['status'])) {
            $ndo->setStatus($rawDataArray['status']);
        }

        // Setting the description
        if (isset($rawDataArray['body'])) {
            $ndo->setDescription($rawDataArray['body']);
        }

        // Setting the dates
        $ndo->setCreated(
            new NDO\LocalDateTime('@' . $rawDataArray['created'], new \DateTimeZone(self::TIMEZONE))
        );
        $ndo->setChanged(
            new NDO\LocalDateTime('@' . $rawDataArray['changed'], new \DateTimeZone(self::TIMEZONE))
        );
        $ndo->setDateTimeStart(
            new NDO\LocalDateTime($rawDataArray['date_time_start'])
        );
        $ndo->setDateTimeEnd(
            new NDO\LocalDateTime($rawDataArray['date_time_end'])
        );

        // Setting the date status
        if (isset($rawDataArray['date_status'])) {
            $ndo->setDateStatus($rawDataArray['date_status']);
        }

        // Setting the date details
        if (isset($rawDataArray['date_details'])) {
            $ndo->setDateDetails($rawDataArray['date_details']);
        }

        // Setting the related division
        if (isset($rawDataArray['related_division'])) {
            $ndo->setRelatedDivision($rawDataArray['related_division']);
        }

        // Setting the library name
        if (isset($rawDataArray['library_name'])) {
            $ndo->setLibraryName($rawDataArray['library_name']);
        }

        // Setting the location phone
        if (isset($rawDataArray['location_phone'])) {
            $ndo->setLocationPhone($rawDataArray['location_phone']);
        }

        // Setting the external location
        if (isset($rawDataArray['external_location'])) {
            $ndo->setExternalLocation($rawDataArray['external_location']);
        }

        // Setting the event type
        if (isset($rawDataArray['event_type'])) {
            $ndo->setEventType($rawDataArray['event_type']);
        }

        // Setting the event topic
        if (isset($rawDataArray['event_topic'])) {
            $ndo->setEventTopic($rawDataArray['event_topic']);
        }

        // Setting the series
        if (isset($rawDataArray['series'])) {
            $ndo->setSeries($rawDataArray['series']);
        }

        // Setting the target audience
        if (isset($rawDataArray['target_audience'])) {
            $ndo->setTargetAudience($rawDataArray['target_audience']);
        }

        // Setting the target audience
        if (isset($rawDataArray['audience'])) {
            $ndo->setAudience(
                $this->removeDuplicateItems($rawDataArray['audience'])
            );
        }

        // Setting the doe activities
        if (isset($rawDataArray['doe_activities'])) {
            $ndo->setDoeActivities($rawDataArray['doe_activities']);
        }

        // Setting the school
        if (isset($rawDataArray['school'])) {
            $ndo->setSchool($rawDataArray['school']);
        }

        // Setting the school type
        if (isset($rawDataArray['school_type'])) {
            $ndo->setSchoolType($rawDataArray['school_type']);
        }

        // Setting the grade
        if (isset($rawDataArray['grade'])) {
            $ndo->setGrade($rawDataArray['grade']);
        }

        // Setting the capacity
        if (isset($rawDataArray['capacity'])) {
            $ndo->setCapacity($rawDataArray['capacity']);
        }

        // Setting the capacity
        if (isset($rawDataArray['total_time'])) {
            $ndo->setTotalTime($rawDataArray['total_time']);
        }

        // Setting the grant funder
        if (isset($rawDataArray['grant_funder'])) {
            $ndo->setGrantFunder($rawDataArray['grant_funder']);
        }

        // Setting the materials
        if (isset($rawDataArray['materials'])) {
            $ndo->setMaterials($rawDataArray['materials']);
        }

        // Setting the prep time
        if (isset($rawDataArray['prep_time'])) {
            $ndo->setPrepTime($rawDataArray['prep_time']);
        }

        // Setting the comments
        if (isset($rawDataArray['comments'])) {
            $ndo->setComments($rawDataArray['comments']);
        }

        // Setting the ignore conflicts flag
        if (isset($rawDataArray['ignore_conflicts'])) {
            $ndo->setIgnoreConflicts($rawDataArray['ignore_conflicts']);
        }

        // Setting the sponsor
        if (isset($rawDataArray['sponsor'])) {
            $ndo->setSponsor($rawDataArray['sponsor']);
        }

        // Setting the total adults
        if (isset($rawDataArray['total_adults'])) {
            $ndo->setTotalAdults($rawDataArray['total_adults']);
        }

        // Setting the total children
        if (isset($rawDataArray['total_children'])) {
            $ndo->setTotalChildren($rawDataArray['total_children']);
        }

        // Setting the total young adults
        if (isset($rawDataArray['total_young_adults'])) {
            $ndo->setTotalYoungAdults($rawDataArray['total_young_adults']);
        }

        // Setting the resources
        if (isset($rawDataArray['resources'])) {
            $ndo->setResources($rawDataArray['resources']);
        }

        // Setting the class size
        if (isset($rawDataArray['class_size'])) {
            $ndo->setClassSize($rawDataArray['class_size']);
        }

        // Setting the teacher name
        if (isset($rawDataArray['teacher_name'])) {
            $ndo->setTeacherName($rawDataArray['teacher_name']);
        }

        // Setting the teacher email
        if (isset($rawDataArray['teacher_email'])) {
            $ndo->setTeacherEmail($rawDataArray['teacher_email']);
        }

        // Setting the performer
        if (isset($rawDataArray['performer'])) {
            $ndo->setPerformer($rawDataArray['performer']);
        }

        // Setting the performer type
        if (isset($rawDataArray['performer_type'])) {
            $ndo->setPerformerType($rawDataArray['performer_type']);
        }

        // Setting the created by
        if (isset($rawDataArray['created_by'])) {
            $ndo->setCreatedBy($rawDataArray['created_by']);
        }

        // Setting the modified by
        if (isset($rawDataArray['modified_by'])) {
            $ndo->setModifiedBy($rawDataArray['modified_by']);
        }

        return $ndo;
    }

    /**
     * Sets the Event language
     *
     * @param NDO\SolrEvent\EventMetrics $ndo
     * @param array                      $rawDataArray
     */
    protected function setLanguage(NDO\SolrEvent\EventMetrics $ndo, array $rawDataArray)
    {
        if (isset($rawDataArray['language'])) {

            $language = $rawDataArray['language'];
            if ($rawDataArray['language'] == self::UNDEFINED_LANGUAGE_CODE) {
                $language = self::DEFAULT_LANGUAGE_CODE;
            }

            $ndo->setLanguage($language);
        }
    }

    /**
     * Removes duplicate values from an array
     *
     * @param  array $array Input array
     * @return array Filtered array
     */
    protected function removeDuplicateItems(array $array)
    {
        return array_unique($array, SORT_STRING);
    }
}
