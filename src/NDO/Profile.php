<?php
namespace NYPL\Refinery\NDO;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
abstract class Profile extends NDO
{
    /**
     * @var string
     */
    public $profileSlug = '';

    /**
     * @var NDO\Content\Image
     */
    public $headshot;

    /**
     * @var Location
     */
    public $location;

    /**
     * @var NDO\Location\Division
     */
    public $division;

    /**
     * @var SubjectGroup
     */
    public $subjects;

    /**
     * @var URIGroup
     */
    public $profileLinks;

    /**
     * @var bool
     */
    public $active = true;

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new RESTAPI\D7RefineryServerCurrent());
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
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param Location $location
     */
    public function setLocation(Location $location)
    {
        $this->location = $location;
    }

    /**
     * @return SubjectGroup
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * @param SubjectGroup $subjects
     */
    public function setSubjects(SubjectGroup $subjects)
    {
        $this->subjects = $subjects;
    }

    /**
     * @return string
     */
    public function getProfileSlug()
    {
        return $this->profileSlug;
    }

    /**
     * @param string $profileSlug
     */
    public function setProfileSlug($profileSlug)
    {
        $this->profileSlug = $profileSlug;
    }

    /**
     * @return Location\Division
     */
    public function getDivision()
    {
        return $this->division;
    }

    /**
     * @param Location\Division $division
     */
    public function setDivision(NDO\Location\Division $division)
    {
        $this->division = $division;
    }

    /**
     * @return URIGroup
     */
    public function getProfileLinks()
    {
        return $this->profileLinks;
    }

    /**
     * @param URIGroup $profileLinks
     */
    public function setProfileLinks(URIGroup $profileLinks)
    {
        $this->profileLinks = $profileLinks;
    }

    /**
     * @param URI $link
     */
    public function addLink(URI $link)
    {
        if (!$this->profileLinks) {
            $this->setProfileLinks(new URIGroup());
        }

        $this->profileLinks->append($link);
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = (bool) $active;
    }
}
