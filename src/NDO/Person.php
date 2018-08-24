<?php
namespace NYPL\Refinery\NDO;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class for a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class Person extends NDO
{
    public $firstName = '';

    public $lastName = '';

    public $fullName = '';

    public $title = '';

    public $phone = '';

    public $email = '';

    /**
     * @var NDO\Content\Image
     */
    public $headshot;

    /**
     * @var string
     */
    public $unit = '';

    /**
     * @var NDO\Location\Library
     */
    public $nyplLocation;

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new RESTAPI\D7RefineryServerCurrent());
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        if (!$this->fullName && $this->lastName) {
            $this->setFullName($firstName . ' ' . $this->lastName);
        }

        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        if (!$this->fullName && $this->firstName) {
            $this->setFullName($this->firstName . ' ' . $lastName);
        }

        $this->lastName = $lastName;
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
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }

    /**
     * @return Content\Image
     */
    public function getHeadshot()
    {
        return $this->headshot;
    }

    /**
     * @param Content\Image $headshot
     */
    public function setHeadshot(Content\Image $headshot)
    {
        $this->headshot = $headshot;
    }

    /**
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    }

    /**
     * @return Location\Library
     */
    public function getNyplLocation()
    {
        return $this->nyplLocation;
    }

    /**
     * @param Location\Library $nyplLocation
     */
    public function setNyplLocation($nyplLocation)
    {
        $this->nyplLocation = $nyplLocation;
    }
}
