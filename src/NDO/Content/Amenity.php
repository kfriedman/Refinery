<?php
namespace NYPL\Refinery\NDO\Content;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class Amenity extends NDO\Content
{
    /**
     * @var int
     */
    public $amenityID = 0;

    /**
     * @var string
     */
    public $name = '';


    /**
     * @var string
     */
    public $actionName = '';

    /**
     * @var NDO\URI
     */
    public $actionURI;

    /**
     * @var string
     */
    public $infoLabel = '';

    /**
     * @var NDO\URI
     */
    public $infoURI;

    /**
     * @var int
     */
    public $sortOrder = 0;

    /**
     * @var string
     */
    public $parentName = '';

    /**
     * @var int
     */
    public $parentAmenityID = 0;

    /**
     * @var array
     */
    public $amenityLocationIDArray = array();

    /**
     * @var NDO\LocationGroup
     */
    public $locations;

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
    public function getAmenityID()
    {
        return $this->amenityID;
    }

    /**
     * @param int $amenityID
     */
    public function setAmenityID($amenityID)
    {
        $this->amenityID = (int) $amenityID;
    }


    /**
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * @param string $actionName
     */
    public function setActionName($actionName)
    {
        $this->actionName = $actionName;
    }

    /**
     * @return NDO\URI
     */
    public function getActionURI()
    {
        return $this->actionURI;
    }

    /**
     * @param NDO\URI $actionURI
     */
    public function setActionURI(NDO\URI $actionURI)
    {
        $this->actionURI = $actionURI;
    }

    /**
     * @return NDO\URI
     */
    public function getInfoURI()
    {
        return $this->infoURI;
    }

    /**
     * @param NDO\URI $infoURI
     */
    public function setInfoURI(NDO\URI $infoURI)
    {
        $this->infoURI = $infoURI;
    }

    /**
     * @return int
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param int $sortOrder
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = (int) $sortOrder;
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
     * @return string
     */
    public function getInfoLabel()
    {
        return $this->infoLabel;
    }

    /**
     * @param string $infoLabel
     */
    public function setInfoLabel($infoLabel)
    {
        $this->infoLabel = $infoLabel;
    }

    /**
     * @return string
     */
    public function getParentName()
    {
        return $this->parentName;
    }

    /**
     * @param string $parentName
     */
    public function setParentName($parentName)
    {
        $this->parentName = $parentName;
    }

    /**
     * @return int
     */
    public function getParentAmenityID()
    {
        return $this->parentAmenityID;
    }

    /**
     * @param int $parentAmenityID
     */
    public function setParentAmenityID($parentAmenityID)
    {
        $this->parentAmenityID = (int) $parentAmenityID;
    }

    /**
     * @return array
     */
    public function getAmenityLocationIDArray()
    {
        return $this->amenityLocationIDArray;
    }

    /**
     * @param array $amenityLocationIDArray
     */
    public function setAmenityLocationIDArray($amenityLocationIDArray)
    {
        $this->amenityLocationIDArray = $amenityLocationIDArray;
    }

    /**
     * @return NDO\LocationGroup
     */
    public function getLocations()
    {
        if (!$this->locations) {
            $this->setLocations(new NDO\LocationGroup());
        }

        return $this->locations;
    }

    /**
     * @param NDO\LocationGroup $locations
     */
    public function setLocations(NDO\LocationGroup $locations)
    {
        $this->locations = $locations;
    }

    /**
     * @param NDO\Location $location
     */
    public function addLocation(NDO\Location $location)
    {
        $this->getLocations()->append($location);
    }
}
