<?php
namespace NYPL\Refinery\NDO\SolrEvent;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class Event extends NDO
{
    /**
     * @var string
     */
    public $uuid;

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
    public $bodyFull;

    /**
     * @var string
     */
    public $bodyShort;

    /**
     * @var array
     */
    public $programImageUrl;

    /**
     * @var string
     */
    public $uri;

    /**
     * @var string
     */
    public $registrationType;

    /**
     * @var int
     */
    public $registrationStatus;

    /**
     * @var string
     */
    public $registrationUri;

    /**
     * @var NDO\LocalDateTime
     */
    public $registrationOpen;

    /**
     * @var NDO\LocalDateTime
     */
    public $registrationClose;

    /**
     * @var int
     */
    public $registrationCapacity;

    /**
     * @var string
     */
    public $registrationState;

    /**
     * @var int
     */
    public $registrationCount;

    /**
     * @var NDO\LocalDateTime
     */
    public $startDate;

    /**
     * @var NDO\LocalDateTime
     */
    public $endDate;

    /**
     * @var NDO\LocalDateTime
     */
    public $dateCreated;

    /**
     * @var NDO\LocalDateTime
     */
    public $dateModified;

    /**
     * @var array
     */
    public $location;

    /**
     * @var array
     */
    public $audience;

    /**
     * @var array
     */
    public $series;

    /**
     * @var array
     */
    public $eventType;

    /**
     * @var array
     */
    public $eventTopic;

    /**
     * @var string
     */
    public $sponsor;

    /**
     * @var string
     */
    public $funding;

    /**
     * @var string
     */
    public $cost;

    /**
     * @var bool
     */
    public $ticketRequired;

    /**
     * @var string
     */
    public $ticketUrl;

    /**
     * @var string
     */
    public $ticketDetails;

    /**
     * @var array
     */
    public $age;

    /**
     * @var string
     */
    public $prerequisite;

    /**
     * @var string
     */
    public $format;

    /**
     * @var bool
     */
    public $listeningDevice;

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new RESTAPI\SolrEvent());
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
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
    public function getBodyFull()
    {
        return $this->bodyFull;
    }

    /**
     * @param string $bodyFull
     */
    public function setBodyFull($bodyFull)
    {
        $this->bodyFull = $bodyFull;
    }

    /**
     * @return string
     */
    public function getBodyShort()
    {
        return $this->bodyShort;
    }

    /**
     * @param string $bodyShort
     */
    public function setBodyShort($bodyShort)
    {
        $this->bodyShort = $bodyShort;
    }

    /**
     * @return array
     */
    public function getProgramImageUrl()
    {
        return $this->programImageUrl;
    }

    /**
     * @param array $programImageUrl
     */
    public function setProgramImageUrl(array $programImageUrl)
    {
        $this->programImageUrl = $programImageUrl;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return string
     */
    public function getRegistrationType()
    {
        return $this->registrationType;
    }

    /**
     * @param string $registrationType
     */
    public function setRegistrationType($registrationType)
    {
        $this->registrationType = $registrationType;
    }

    /**
     * @return int
     */
    public function getRegistrationStatus()
    {
        return $this->registrationStatus;
    }

    /**
     * @param int $registrationStatus
     */
    public function setRegistrationStatus($registrationStatus)
    {
        $this->registrationStatus = (int) $registrationStatus;
    }

    /**
     * @return string
     */
    public function getRegistrationUri()
    {
        return $this->registrationUri;
    }

    /**
     * @param string $registrationUri
     */
    public function setRegistrationUri($registrationUri)
    {
        $this->registrationUri = $registrationUri;
    }

    /**
     * @return NDO\LocalDateTime
     */
    public function getRegistrationOpen()
    {
        return $this->registrationOpen;
    }

    /**
     * @param NDO\LocalDateTime $registrationOpen
     */
    public function setRegistrationOpen(NDO\LocalDateTime $registrationOpen)
    {
        $this->registrationOpen = $registrationOpen;
    }

    /**
     * @return NDO\LocalDateTime
     */
    public function getRegistrationClose()
    {
        return $this->registrationClose;
    }

    /**
     * @param NDO\LocalDateTime $registrationClose
     */
    public function setRegistrationClose(NDO\LocalDateTime $registrationClose)
    {
        $this->registrationClose = $registrationClose;
    }

    /**
     * @return int
     */
    public function getRegistrationCapacity()
    {
        return $this->registrationCapacity;
    }

    /**
     * @param int $registrationCapacity
     */
    public function setRegistrationCapacity($registrationCapacity)
    {
        $this->registrationCapacity = (int) $registrationCapacity;
    }

    /**
     * @return string
     */
    public function getRegistrationState()
    {
        return $this->registrationState;
    }

    /**
     * @param string $registrationState
     */
    public function setRegistrationState($registrationState)
    {
        $this->registrationState = $registrationState;
    }

    /**
     * @return int
     */
    public function getRegistrationCount()
    {
        return $this->registrationCount;
    }

    /**
     * @param int $registrationCount
     */
    public function setRegistrationCount($registrationCount)
    {
        $this->registrationCount = (int) $registrationCount;
    }

    /**
     * @return NDO\LocalDateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param NDO\LocalDateTime $startDate
     */
    public function setStartDate(NDO\LocalDateTime $startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return NDO\LocalDateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param NDO\LocalDateTime $endDate
     */
    public function setEndDate(NDO\LocalDateTime $endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * @return NDO\LocalDateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param NDO\LocalDateTime $dateCreated
     */
    public function setDateCreated(NDO\LocalDateTime $dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return NDO\LocalDateTime
     */
    public function getDateModified()
    {
        return $this->dateModified;
    }

    /**
     * @param NDO\LocalDateTime $dateModified
     */
    public function setDateModified(NDO\LocalDateTime $dateModified)
    {
        $this->dateModified = $dateModified;
    }

    /**
     * @return array
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param array $location
     */
    public function setLocation(array $location)
    {
        $this->location = $location;
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
    public function setAudience(array $audience)
    {
        $this->audience = $audience;
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
     * @return array
     */
    public function getEventTopic()
    {
        return $this->eventTopic;
    }

    /**
     * @param array $eventTopic
     */
    public function setEventTopic(array $eventTopic)
    {
        $this->eventTopic = $eventTopic;
    }

    /**
     * @return array
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * @param array $eventType
     */
    public function setEventType(array $eventType)
    {
        $this->eventType = $eventType;
    }

    /**
     * @return string
     */
    public function getSponsor()
    {
        return $this->sponsor;
    }

    /**
     * @param string $sponsor
     */
    public function setSponsor($sponsor)
    {
        $this->sponsor = $sponsor;
    }

    /**
     * @return string
     */
    public function getFunding()
    {
        return $this->funding;
    }

    /**
     * @param string $funding
     */
    public function setFunding($funding)
    {
        $this->funding = $funding;
    }

    /**
     * @return string
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param string $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    /**
     * @return bool
     */
    public function getTicketRequired()
    {
        return $this->ticketRequired;
    }

    /**
     * @param bool $ticketRequired
     */
    public function setTicketRequired($ticketRequired)
    {
        $this->ticketRequired = (bool) $ticketRequired;
    }

    /**
     * @return string
     */
    public function getTicketUrl()
    {
        return $this->ticketUrl;
    }

    /**
     * @param string $ticketUrl
     */
    public function setTicketUrl($ticketUrl)
    {
        $this->ticketUrl = $ticketUrl;
    }

    /**
     * @return string
     */
    public function getTicketDetails()
    {
        return $this->ticketDetails;
    }

    /**
     * @param string $ticketDetails
     */
    public function setTicketDetails($ticketDetails)
    {
        $this->ticketDetails = $ticketDetails;
    }

    /**
     * @return array
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param array $age
     */
    public function setAge(array $age)
    {
        $this->age = $age;
    }

    /**
     * @return string
     */
    public function getPrerequisite()
    {
        return $this->prerequisite;
    }

    /**
     * @param string $prerequisite
     */
    public function setPrerequisite($prerequisite)
    {
        $this->prerequisite = $prerequisite;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return bool
     */
    public function getListeningDevice()
    {
        return $this->listeningDevice;
    }

    /**
     * @param bool $listeningDevice
     */
    public function setListeningDevice($listeningDevice)
    {
        $this->listeningDevice = (bool) $listeningDevice;
    }
}
