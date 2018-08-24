<?php

namespace NYPL\Refinery\NDO\SolrEvent;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class EventMetrics extends NDO
{
    /**
     * @var string
     */
    public $metricsId;

    /**
     * @var int
     */
    public $delta;

    /**
     * @var int
     */
    public $nid;

    /**
     * @var string
     */
    public $language;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $description;

    /**
     * @var NDO\LocalDateTime
     */
    public $created;

    /**
     * @var NDO\LocalDateTime
     */
    public $changed;

    /**
     * @var NDO\LocalDateTime
     */
    public $dateTimeStart;

    /**
     * @var NDO\LocalDateTime
     */
    public $dateTimeEnd;

    /**
     * @var string
     */
    public $dateStatus;

    /**
     * @var string
     */
    public $dateDetails;

    /**
     * @var array
     */
    public $relatedDivision;

    /**
     * @var string
     */
    public $libraryName;

    /**
     * @var string
     */
    public $locationPhone;

    /**
     * @var array
     */
    public $externalLocation;

    /**
     * @var string
     */
    public $eventType;

    /**
     * @var string
     */
    public $eventTopic;

    /**
     * @var array
     */
    public $series;

    /**
     * @var string
     */
    public $targetAudience;

    /**
     * @var array
     */
    public $audience;

    /**
     * @var string
     */
    public $doeActivities;

    /**
     * @var string
     */
    public $school;

    /**
     * @var string
     */
    public $schoolType;

    /**
     * @var string
     */
    public $grade;

    /**
     * @var string
     */
    public $capacity;

    /**
     * @var string
     */
    public $totalTime;

    /**
     * @var array
     */
    public $grantFunder;

    /**
     * @var string
     */
    public $materials;

    /**
     * @var string
     */
    public $prepTime;

    /**
     * @var string
     */
    public $comments;

    /**
     * @var int
     */
    public $ignoreConflicts;

    /**
     * @var array
     */
    public $sponsor;

    /**
     * @var int
     */
    public $totalAdults;

    /**
     * @var int
     */
    public $totalChildren;

    /**
     * @var int
     */
    public $totalYoungAdults;

    /**
     * @var string
     */
    public $resources;

    /**
     * @var int
     */
    public $classSize;

    /**
     * @var string
     */
    public $teacherName;

    /**
     * @var string
     */
    public $teacherEmail;

    /**
     * @var string
     */
    public $performer;

    /**
     * @var string
     */
    public $performerType;

    /**
     * @var string
     */
    public $createdBy;

    /**
     * @var string
     */
    public $modifiedBy;

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new RESTAPI\SolrEvent());
    }

    /**
     * @param string $metricsId
     */
    public function setMetricsId($metricsId)
    {
        $this->metricsId = $metricsId;
    }

    /**
     * @return string
     */
    public function getMetricsId()
    {
        return $this->metricsId;
    }

    /**
     * @param int $delta
     */
    public function setDelta($delta)
    {
        $this->delta = (int) $delta;
    }

    /**
     * @return int
     */
    public function getDelta()
    {
        return $this->delta;
    }

    /**
     * @param int $nid
     */
    public function setNid($nid)
    {
        $this->nid = (int) $nid;
    }

    /**
     * @return int
     */
    public function getNid()
    {
        return $this->nid;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return NDO\LocalDateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param NDO\LocalDateTime $created
     */
    public function setCreated(NDO\LocalDateTime $created)
    {
        $this->created = $created;
    }

    /**
     * @return NDO\LocalDateTime
     */
    public function getChanged()
    {
        return $this->changed;
    }

    /**
     * @param NDO\LocalDateTime $changed
     */
    public function setChanged(NDO\LocalDateTime $changed)
    {
        $this->changed = $changed;
    }

    /**
     * @return NDO\LocalDateTime
     */
    public function getDateTimeStart()
    {
        return $this->dateTimeStart;
    }

    /**
     * @param NDO\LocalDateTime $dateTimeStart
     */
    public function setDateTimeStart(NDO\LocalDateTime $dateTimeStart)
    {
        $this->dateTimeStart = $dateTimeStart;
    }

    /**
     * @return NDO\LocalDateTime
     */
    public function getDateTimeEnd()
    {
        return $this->dateTimeEnd;
    }

    /**
     * @param NDO\LocalDateTime $dateTimeEnd
     */
    public function setDateTimeEnd(NDO\LocalDateTime $dateTimeEnd)
    {
        $this->dateTimeEnd = $dateTimeEnd;
    }

    /**
     * @return string
     */
    public function getDateStatus()
    {
        return $this->dateStatus;
    }

    /**
     * @param string $dateStatus
     */
    public function setDateStatus($dateStatus)
    {
        $this->dateStatus = $dateStatus;
    }

    /**
     * @return string
     */
    public function getDateDetails()
    {
        return $this->dateDetails;
    }

    /**
     * @param string $dateDetails
     */
    public function setDateDetails($dateDetails)
    {
        $this->dateDetails = $dateDetails;
    }

    /**
     * @return array
     */
    public function getRelatedDivision()
    {
        return $this->relatedDivision;
    }

    /**
     * @param array $relatedDivision
     */
    public function setRelatedDivision(array $relatedDivision)
    {
        $this->relatedDivision = $relatedDivision;
    }

    /**
     * @return string
     */
    public function getLibraryName()
    {
        return $this->libraryName;
    }

    /**
     * @param string $libraryName
     */
    public function setLibraryName($libraryName)
    {
        $this->libraryName = $libraryName;
    }

    /**
     * @return string
     */
    public function getLocationPhone()
    {
        return $this->locationPhone;
    }

    /**
     * @param string $locationPhone
     */
    public function setLocationPhone($locationPhone)
    {
        $this->locationPhone = $locationPhone;
    }

    /**
     * @return array
     */
    public function getExternalLocation()
    {
        return $this->externalLocation;
    }

    /**
     * @param array $externalLocation
     */
    public function setExternalLocation(array $externalLocation)
    {
        $this->externalLocation = $externalLocation;
    }

    /**
     * @return string
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * @param string $eventType
     */
    public function setEventType($eventType)
    {
        $this->eventType = $eventType;
    }

    /**
     * @return string
     */
    public function getEventTopic()
    {
        return $this->eventTopic;
    }

    /**
     * @param string $eventTopic
     */
    public function setEventTopic($eventTopic)
    {
        $this->eventTopic = $eventTopic;
    }

    /**
     * @return array
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * @param array $series
     */
    public function setSeries(array $series)
    {
        $this->series = $series;
    }

    /**
     * @return string
     */
    public function getTargetAudience()
    {
        return $this->targetAudience;
    }

    /**
     * @param string $targetAudience
     */
    public function setTargetAudience($targetAudience)
    {
        $this->targetAudience = $targetAudience;
    }

    /**
     * @return array
     */
    public function getAudience()
    {
        return $this->audience;
    }

    /**
     * @param array $audience
     */
    public function setAudience($audience)
    {
        $this->audience = $audience;
    }

    /**
     * @return string
     */
    public function getDoeActivities()
    {
        return $this->doeActivities;
    }

    /**
     * @param string $doeActivities
     */
    public function setDoeActivities($doeActivities)
    {
        $this->doeActivities = $doeActivities;
    }

    /**
     * @return string
     */
    public function getSchool()
    {
        return $this->school;
    }

    /**
     * @param string $school
     */
    public function setSchool($school)
    {
        $this->school = $school;
    }

    /**
     * @return string
     */
    public function getSchoolType()
    {
        return $this->schoolType;
    }

    /**
     * @param string $schoolType
     */
    public function setSchoolType($schoolType)
    {
        $this->schoolType = $schoolType;
    }

    /**
     * @return string
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * @param string $grade
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;
    }

    /**
     * @return string
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * @param string $capacity
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;
    }

    /**
     * @return string
     */
    public function getTotalTime()
    {
        return $this->totalTime;
    }

    /**
     * @param string $totalTime
     */
    public function setTotalTime($totalTime)
    {
        $this->totalTime = $totalTime;
    }

    /**
     * @return array
     */
    public function getGrantFunder()
    {
        return $this->grantFunder;
    }

    /**
     * @param array $grantFunder
     */
    public function setGrantFunder(array $grantFunder)
    {
        $this->grantFunder = $grantFunder;
    }

    /**
     * @return string
     */
    public function getMaterials()
    {
        return $this->materials;
    }

    /**
     * @param string $materials
     */
    public function setMaterials($materials)
    {
        $this->materials = $materials;
    }

    /**
     * @return string
     */
    public function getPrepTime()
    {
        return $this->prepTime;
    }

    /**
     * @param string $prepTime
     */
    public function setPrepTime($prepTime)
    {
        $this->prepTime = $prepTime;
    }

    /**
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param string $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @return int
     */
    public function getIgnoreConflicts()
    {
        return $this->ignoreConflicts;
    }

    /**
     * @param int $ignoreConflicts
     */
    public function setIgnoreConflicts($ignoreConflicts)
    {
        $this->ignoreConflicts = (int) $ignoreConflicts;
    }

    /**
     * @return array
     */
    public function getSponsor()
    {
        return $this->sponsor;
    }

    /**
     * @param array $sponsor
     */
    public function setSponsor(array $sponsor)
    {
        $this->sponsor = $sponsor;
    }

    /**
     * @return int
     */
    public function getTotalAdults()
    {
        return $this->totalAdults;
    }

    /**
     * @param int $totalAdults
     */
    public function setTotalAdults($totalAdults)
    {
        $this->totalAdults = (int) $totalAdults;
    }

    /**
     * @return int
     */
    public function getTotalChildren()
    {
        return $this->totalChildren;
    }

    /**
     * @param int $totalChildren
     */
    public function setTotalChildren($totalChildren)
    {
        $this->totalChildren = (int) $totalChildren;
    }

    /**
     * @return int
     */
    public function getTotalYoungAdults()
    {
        return $this->totalYoungAdults;
    }

    /**
     * @param int $totalYoungAdults
     */
    public function setTotalYoungAdults($totalYoungAdults)
    {
        $this->totalYoungAdults = (int) $totalYoungAdults;
    }

    /**
     * @return string
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @param string $resources
     */
    public function setResources($resources)
    {
        $this->resources = $resources;
    }

    /**
     * @return int
     */
    public function getClassSize()
    {
        return $this->classSize;
    }

    /**
     * @param int $classSize
     */
    public function setClassSize($classSize)
    {
        $this->classSize = (int) $classSize;
    }

    /**
     * @return string
     */
    public function getTeacherName()
    {
        return $this->teacherName;
    }

    /**
     * @param string $teacherName
     */
    public function setTeacherName($teacherName)
    {
        $this->teacherName = $teacherName;
    }

    /**
     * @return string
     */
    public function getTeacherEmail()
    {
        return $this->teacherEmail;
    }

    /**
     * @param string $teacherEmail
     */
    public function setTeacherEmail($teacherEmail)
    {
        $this->teacherEmail = $teacherEmail;
    }

    /**
     * @return string
     */
    public function getPerformer()
    {
        return $this->performer;
    }

    /**
     * @param string $performer
     */
    public function setPerformer($performer)
    {
        $this->performer = $performer;
    }

    /**
     * @return string
     */
    public function getPerformerType()
    {
        return $this->performerType;
    }

    /**
     * @param string $performerType
     */
    public function setPerformerType($performerType)
    {
        $this->performerType = $performerType;
    }

    /**
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param string $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return string
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    /**
     * @param string $modifiedBy
     */
    public function setModifiedBy($modifiedBy)
    {
        $this->modifiedBy = $modifiedBy;
    }
}
