<?php
namespace NYPL\Refinery\NDO\Location;

use NYPL\Refinery\NDO;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class Library extends NDO\Location
{
    /**
     * @var NDO\Content\Image
     */
    public $exteriorImage;

    /**
     * @var NDO\Content\Image
     */
    public $interiorImage;

    /**
     * @return NDO\Content\Image
     */
    public function getExteriorImage()
    {
        return $this->exteriorImage;
    }

    /**
     * @param NDO\Content\Image $exteriorImage
     */
    public function setExteriorImage(NDO\Content\Image $exteriorImage)
    {
        $this->exteriorImage = $exteriorImage;
    }

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
}