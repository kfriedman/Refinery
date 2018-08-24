<?php
namespace NYPL\Refinery\NDO\Content\Node;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\NDO\LocalDateTime;

/**
 * Class to create a NDO
 */
class EventExhibition extends NDO\Content\Node
{
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
     * @var NDO\TextGroup
     */
    public $name;

    /**
     * @var NDO\TextGroup
     */
    public $description;

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
     * @var NDO\Location
     */
    public $location;

    /**
     * @var string
     */
    public $spaceName = '';

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
     * @return NDO\TextGroup
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param NDO\TextGroup $name
     */
    public function setName(NDO\TextGroup $name)
    {
        $this->name = $name;
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

    /**
     * @return NDO\TextGroup
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param NDO\TextGroup $description
     */
    public function setDescription(NDO\TextGroup $description)
    {
        $this->description = $description;
    }

    /**
     * @return NDO\Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param NDO\Location $location
     */
    public function setLocation(NDO\Location $location)
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getSpaceName()
    {
        return $this->spaceName;
    }

    /**
     * @param string $spaceName
     */
    public function setSpaceName($spaceName)
    {
        $this->spaceName = $spaceName;
    }
}