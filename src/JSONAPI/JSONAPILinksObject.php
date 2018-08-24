<?php
namespace NYPL\Refinery\JSONAPI;

/**
 * Class for JSON API resources.
 *
 * @package NYPL\Refinery
 *
 * @see http://jsonapi.org/
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class JSONAPILinksObject
{
    /**
     * @var string
     */
    private $href = '';

    /**
     * @var array
     */
    private $meta = array();

    /**
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * @param string $href
     */
    public function setHref($href)
    {
        $this->href = $href;
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @param array $meta
     */
    public function setMeta(array $meta)
    {
        $this->meta = $meta;
    }
}