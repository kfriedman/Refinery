<?php
namespace NYPL\Refinery\NDO\Content;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class Image extends NDO\Content
{
    /**
     * @var NDO\URI
     */
    public $uri;

    /**
     * @var int|null
     */
    public $width;

    /**
     * @var int|null
     */
    public $height;

    /**
     * @var int
     */
    public $fileSize = 0;

    /**
     * @var NDO\TextGroup
     */
    public $altText;

    /**
     * @var string
     */
    public $mimeType = '';

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new RESTAPI\D7RefineryServerCurrent());
        $this->addSupportedReadProvider(new RESTAPI\D7RefineryServerNew());
    }

    /**
     * @param string             $ndoID
     * @param NDO\URI|null       $uri
     * @param NDO\TextGroup|null $altTextGroup
     */
    public function __construct($ndoID = '', NDO\URI $uri = null, NDO\TextGroup $altTextGroup = null)
    {
        if ($ndoID) {
            $this->setNdoID($ndoID);
        }

        if ($uri) {
            $this->setUri($uri);
        }

        if ($altTextGroup) {
            $this->setAltText($altTextGroup);
        }

        parent::__construct();
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
     * @return int|null
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int|null $width
     */
    public function setWidth($width)
    {
        if ($width !== null) {
            $width = (int) $width;
        }

        $this->width = $width;
    }

    /**
     * @return int|null
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int|null $height
     */
    public function setHeight($height)
    {
        if ($height !== null) {
            $height = (int) $height;
        }

        $this->height = $height;
    }

    /**
     * @return NDO\TextGroup
     */
    public function getAltText()
    {
        return $this->altText;
    }

    /**
     * @param NDO\TextGroup $altText
     */
    public function setAltText(NDO\TextGroup $altText)
    {
        $this->altText = $altText;
    }

    /**
     * @return int
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * @param int $fileSize
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = (int) $fileSize;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param string $mimeType
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    }
}
