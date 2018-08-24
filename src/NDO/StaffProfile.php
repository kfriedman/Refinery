<?php
namespace NYPL\Refinery\NDO;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class StaffProfile extends Profile
{
    /**
     * @var TextGroup
     */
    public $profileText;

    /**
     * @var bool
     */
    public $useContactInfo = false;

    /**
     * @var Person
     */
    public $person;

    /**
     * @return TextGroup
     */
    public function getProfileText()
    {
        return $this->profileText;
    }

    /**
     * @param TextGroup $profileText
     */
    public function setProfileText(TextGroup $profileText)
    {
        $this->profileText = $profileText;
    }

    /**
     * @return boolean
     */
    public function isUseContactInfo()
    {
        return $this->useContactInfo;
    }

    /**
     * @param boolean $useContactInfo
     */
    public function setUseContactInfo($useContactInfo)
    {
        $this->useContactInfo = (bool) $useContactInfo;
    }
    
    /**
     * @return Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param Person $person
     */
    public function setPerson(Person $person)
    {
        $this->person = $person;
    }
}
