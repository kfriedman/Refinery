<?php
namespace NYPL\Refinery\NDO\Content;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class Feature extends NDO\Content
{
    /**
     * @var int
     */
    public $featureID = 0;

    /**
     * @var string
     */
    public $title = '';

    /**
     * @var string
     */
    public $body = '';

    /**
     * @var Image
     */
    public $image;

    /**
     * @var NDO\URI
     */
    public $uri;

    /**
     * @var int
     */
    public $sortOrder = 0;

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
    public function getFeatureID()
    {
        return $this->featureID;
    }

    /**
     * @param int $featureID
     */
    public function setFeatureID($featureID)
    {
        $this->featureID = $featureID;
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
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param Image $image
     */
    public function setImage(Image $image)
    {
        $this->image = $image;
    }

    /**
     * @return NDO\URI
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param NDO\URI $uri
     */
    public function setUri(NDO\URI $uri)
    {
        $this->uri = $uri;
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
}
