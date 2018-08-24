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
class JSONAPILinks
{
    /**
     * @var string
     */
    private $self = '';

    /**
     * @var string|JSONAPILinksObject
     */
    private $related;

    /**
     * @var array
     */
    private $meta = array();

    /**
     * @return string
     */
    public function getSelf()
    {
        return $this->self;
    }

    /**
     * @param string $self
     */
    public function setSelf($self)
    {
        $this->self = $self;
    }

    /**
     * @return string|JSONAPILinksObject
     */
    public function getRelated()
    {
        return $this->related;
    }

    /**
     * @param string|JSONAPILinksObject $related
     */
    public function setRelated($related)
    {
        $this->related = $related;
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
    public function setMeta($meta)
    {
        $this->meta = $meta;
    }

    /**
     * @param array $meta
     */
    public function addMeta(array $meta)
    {
        $this->meta += $meta;
    }
}