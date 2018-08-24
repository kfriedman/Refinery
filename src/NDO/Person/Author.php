<?php
namespace NYPL\Refinery\NDO\Person;

use NYPL\Refinery\NDO;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class Author extends NDO\Person
{
    /**
     * @var string
     */
    public $displayName = '';

    /**
     * @var string
     */
    public $location = '';

    /**
     * @var NDO\StaffProfile
     */
    public $staffProfile;

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return NDO\StaffProfile
     */
    public function getStaffProfile()
    {
        return $this->staffProfile;
    }

    /**
     * @param NDO\StaffProfile $staffProfile
     */
    public function setStaffProfile(NDO\StaffProfile $staffProfile)
    {
        $this->staffProfile = $staffProfile;
    }
}