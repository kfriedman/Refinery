<?php
namespace NYPL\Refinery\NDO\Location;

use NYPL\Refinery\NDO;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class Division extends NDO\Location
{
    /**
     * @var NDO\Content\Image
     */
    public $collectionsImage;

    /**
     * @var NDO\Content\Image
     */
    public $interiorImage;

    /**
     * @var string
     */
    public $parentLocationSymbol = '';

    /**
     * @var int
     */
    public $parentLocationID = 0;

    /**
     * @var Library
     */
    public $parentLocation;

    /**
     * @var int
     */
    public $sortOrder = 0;

    /**
     * @return NDO\Content\Image
     */
    public function getInteriorImage()
    {
        return $this->interiorImage;
    }

    /**
     * @param NDO\Content\Image $interiorImage
     */
    public function setInteriorImage(NDO\Content\Image $interiorImage)
    {
        $this->interiorImage = $interiorImage;
    }

    /**
     * @return NDO\Content\Image
     */
    public function getCollectionsImage()
    {
        return $this->collectionsImage;
    }

    /**
     * @param NDO\Content\Image $collectionsImage
     */
    public function setCollectionsImage(NDO\Content\Image $collectionsImage)
    {
        $this->collectionsImage = $collectionsImage;
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
    public function getParentLocationSymbol()
    {
        return $this->parentLocationSymbol;
    }

    /**
     * @param string $parentLocationSymbol
     */
    public function setParentLocationSymbol($parentLocationSymbol)
    {
        $this->parentLocationSymbol = $parentLocationSymbol;
    }

    /**
     * @return int
     */
    public function getParentLocationID()
    {
        return $this->parentLocationID;
    }

    /**
     * @param int $parentLocationID
     */
    public function setParentLocationID($parentLocationID)
    {
        $this->parentLocationID = (int) $parentLocationID;
    }

    /**
     * @return Library
     */
    public function getParentLocation()
    {
        return $this->parentLocation;
    }

    /**
     * @param Library $parentLocation
     */
    public function setParentLocation(Library $parentLocation)
    {
        $this->parentLocation = $parentLocation;
    }
}