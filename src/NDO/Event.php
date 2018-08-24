<?php
namespace NYPL\Refinery\NDO;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 */
class Event extends NDO
{
    /**
     * @var int
     */
    public $eventID = 0;

    /**
     * @var int|string
     */
    public $uuid;

    /**
     * @var LocalDateTime
     */
    public $dateCreated;

    /**
     * @var LocalDateTime
     */
    public $dateModified;

    /**
     * @var LocalDateTime
     */
    public $startDate;

    /**
     * @var LocalDateTime
     */
    public $endDate;

    /**
     * @var string
     */
    public $name = '';

    /**
     * @var string
     */
    public $descriptionFull = '';

    /**
     * @var string
     */
    public $descriptionShort = '';

    /**
     * @var URI
     */
    public $uri;

    /**
     * @var NDO\Content\Image
     */
    public $image;

    /**
     * @var string
     */
    public $eventStatus = '';

    /**
     * @var string
     */
    public $registrationType = '';

    /**
     * @var LocalDateTime
     */
    public $registrationOpen;

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new RESTAPI\D7RefineryServerCurrent());
    }

    /**
     * @return int
     */
    public function getEventID()
    {
        return $this->eventID;
    }

    /**
     * @param int $eventID
     */
    public function setEventID($eventID)
    {
        $this->eventID = (int) $eventID;
    }

    /**
     * @return int|string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param int|string $uuid
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return LocalDateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param LocalDateTime $dateCreated
     */
    public function setDateCreated(LocalDateTime $dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return LocalDateTime
     */
    public function getDateModified()
    {
        return $this->dateModified;
    }

    /**
     * @param LocalDateTime $dateModified
     */
    public function setDateModified(LocalDateTime $dateModified)
    {
        $this->dateModified = $dateModified;
    }

    /**
     * @return LocalDateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param LocalDateTime $startDate
     */
    public function setStartDate(LocalDateTime $startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return LocalDateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param LocalDateTime $endDate
     */
    public function setEndDate(LocalDateTime $endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return URI
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param URI $uri
     */
    public function setUri(URI $uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return string
     */
    public function getDescriptionFull()
    {
        return $this->descriptionFull;
    }

    /**
     * @param string $descriptionFull
     */
    public function setDescriptionFull($descriptionFull)
    {
        $this->descriptionFull = $descriptionFull;
    }

    /**
     * @return string
     */
    public function getDescriptionShort()
    {
        return $this->descriptionShort;
    }

    /**
     * @param string $descriptionShort
     */
    public function setDescriptionShort($descriptionShort)
    {
        $this->descriptionShort = $descriptionShort;
    }

    /**
     * @return NDO\Content\Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param NDO\Content\Image $image
     */
    public function setImage(NDO\Content\Image $image)
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getEventStatus()
    {
        return $this->eventStatus;
    }

    /**
     * @param string $eventStatus
     */
    public function setEventStatus($eventStatus)
    {
        $this->eventStatus = $eventStatus;
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
     * @return LocalDateTime
     */
    public function getRegistrationOpen()
    {
        return $this->registrationOpen;
    }

    /**
     * @param LocalDateTime $registrationOpen
     */
    public function setRegistrationOpen(LocalDateTime $registrationOpen)
    {
        $this->registrationOpen = $registrationOpen;
    }
}