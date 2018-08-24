<?php
namespace NYPL\Refinery\JSONAPI;

/**
 * Class for JSON API Relationship objects.
 *
 * @package NYPL\Refinery
 */
class JSONAPIRelationshipGroup
{
    /**
     * The name of the JSON API relationship group.
     *
     * @var string
     */
    public $groupName = '';

    /**
     * @var JSONAPILinks
     */
    public $links;

    /**
     * Whether the relationship is one-to-one or one-to-many.
     *
     * @var bool
     */
    public $multi = false;

    /**
     * An array of JSON API representing the relationships.
     *
     * @var JSONAPI[]
     */
    public $jsonAPIRelationships = array();

    /**
     * Constructor for the JSON API Relationship groups.
     *
     * @param string                    $groupName
     * @param string                    $self
     * @param string|JSONAPILinksObject $related
     * @param bool                      $isMulti
     */
    public function __construct($groupName = '', $self = '', $related = null, $isMulti = false)
    {
        if ($groupName) {
            $this->setGroupName($groupName);
        }

        if ($self) {
            $this->getLinks()->setSelf($self);
        }

        if ($related) {
            $this->getLinks()->setRelated($related);
        }

        $this->setMulti($isMulti);
    }

    /**
     * Getter for the name of the JSON API relationship group.
     *
     * @return string
     */
    public function getGroupName()
    {
        return $this->groupName;
    }

    /**
     * Setter for the name of the JSON API relationship group.
     *
     * @param string $groupName
     */
    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;
    }

    /**
     * Getter for the array of JSON API representing the relationships.
     *
     * @return JSONAPI[]
     */
    public function getJSONAPIRelationships()
    {
        return $this->jsonAPIRelationships;
    }

    /**
     * Setter for the array of JSON API representing the relationships.
     *
     * @param JSONAPI[] $jsonAPIRelationships
     */
    public function setJSONAPIRelationships($jsonAPIRelationships)
    {
        $this->jsonAPIRelationships = $jsonAPIRelationships;
    }

    /**
     * Add a relationship.
     *
     * @param JSONAPI $jsonAPI
     */
    public function addJSONAPILink(JSONAPI $jsonAPI)
    {
        $this->jsonAPIRelationships[] = $jsonAPI;
    }

    /**
     * Getter for whether the relationship is one-to-one or one-to-many.
     *
     * @return boolean
     */
    public function isMulti()
    {
        return $this->multi;
    }

    /**
     * Setter for whether the relationship is one-to-one or one-to-many.
     *
     * @param boolean $multi
     */
    public function setMulti($multi)
    {
        $this->multi = (bool) $multi;
    }

    /**
     * @return JSONAPILinks
     */
    public function getLinks()
    {
        if (!$this->links) {
            $this->setLinks(new JSONAPILinks());
        }

        return $this->links;
    }

    /**
     * @param JSONAPILinks $links
     */
    public function setLinks(JSONAPILinks $links)
    {
        $this->links = $links;
    }
}