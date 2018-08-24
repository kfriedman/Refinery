<?php
namespace NYPL\Refinery\NDO;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class DCItem extends NDO
{
    public $imageID = 0;

    public $imageLink = '';

    public $itemLink = '';

    public $title = '';

    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new RESTAPI\DC());
    }

    /**
     * @return string
     */
    public function getImageLink()
    {
        return $this->imageLink;
    }

    /**
     * @param string $imageLink
     */
    public function setImageLink($imageLink)
    {
        $this->imageLink = $imageLink;
    }

    /**
     * @return int
     */
    public function getImageID()
    {
        return $this->imageID;
    }

    /**
     * @param int $imageID
     */
    public function setImageID($imageID)
    {
        $this->imageID = $imageID;
    }

    /**
     * @return string
     */
    public function getItemLink()
    {
        return $this->itemLink;
    }

    /**
     * @param string $itemLink
     */
    public function setItemLink($itemLink)
    {
        $this->itemLink = $itemLink;
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
}