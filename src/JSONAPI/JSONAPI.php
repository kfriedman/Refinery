<?php
namespace NYPL\Refinery\JSONAPI;

use NYPL\Refinery\Exception\RefineryException;

/**
 * Class for JSON API resources.
 *
 * @package NYPL\Refinery
 *
 * @see http://jsonapi.org/
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class JSONAPI
{
    /**
     * The type of the resource.
     *
     * @var string
     */
    public $type = '';

    /**
     * The ID of the resource.
     *
     * @var string
     */
    public $id = '';

    /**
     * An array of the resource attributes.
     *
     * @var array
     */
    public $attributes = array();

    /**
     * JSONAPIRelationshipGroup object representing the resource relationships.
     *
     * @var JSONAPIRelationshipGroup[]
     */
    public $relationships = array();

    /**
     * JSONAPIGroup representing the included JSON API resources.
     *
     * @var JSONAPIGroup
     */
    public $included;

    /**
     * @var JSONAPILinks
     */
    public $links;

    /**
     * Getter for the type of the resource.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Setter for the type of the resource.
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Setter for the ID of the resource.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Getter for the ID of the resource.
     *
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Add an attribute to the resource attributes.
     *
     * @param string $name      Name of the attribute.
     * @param null   $value     Value of the attribute.
     * @param bool   $isArray   If the attribute is an array.
     * @param string $itemIndex The index name if the array is not numerically indexed.
     * @param string $itemType  The "type" of the array; this is a quasi-type.
     *
     * @throws RefineryException
     */
    public function addAttribute($name = '', $value = null, $isArray = false, $itemIndex = '', $itemType = '')
    {
        if ($isArray) {
            if ($itemIndex) {
                if ($itemType) {
                    $this->attributes[$name]['type'] = $itemType;
                }

                $this->attributes[$name][$itemIndex] = $value;
            } else {
                if ($itemType) {
                    throw new RefineryException('Can not specify resource type (' . $itemType . ') for numerically indexed arrays (' . $name . ')');
                }

                $this->attributes[$name][] = $value;
            }
        } else {
            $this->attributes[$name] = $value;
        }
    }

    /**
     * Getter for the array of the resource attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Setter for the array of the resource attributes.
     *
     * @param array $attributes
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Getter for the JSONAPIRelationshipGroup object representing the resource relationships.
     *
     * @return JSONAPIRelationshipGroup[]
     */
    public function getRelationships()
    {
        return $this->relationships;
    }

    /**
     * Setter for the JSONAPIRelationshipGroup object representing the resource relationships.
     *
     * @param JSONAPIRelationshipGroup[] $relationships
     */
    public function setRelationships($relationships)
    {
        $this->relationships = $relationships;
    }

    /**
     * Add a link to the JSONAPIRelationshipGroup object representing the resource relationships.
     *
     * @param string  $groupName The name/type of the JSONAPIRelationshipGroup object.
     * @param JSONAPI $jsonAPI   The JSONAPI object for the relationship.
     * @param string  $self      The "self" link for the relationship.
     * @param string  $related   The "related" link for the relationship.
     * @param bool    $isMulti   Whether the relationship is one-to-one or one-to-many.
     */
    public function addRelationship($groupName = '', JSONAPI $jsonAPI = null, $self = '', $related = '', $isMulti = false)
    {
        if (!isset($this->relationships[$groupName])) {
            $this->relationships[$groupName] = new JSONAPIRelationshipGroup($groupName, $self, $related, $isMulti);
        }

        $this->relationships[$groupName]->addJSONAPILink($jsonAPI);
    }

    /**
     * Getter for the included JSON API resources.
     *
     * @return JSONAPIGroup
     */
    public function getIncluded()
    {
        return $this->included;
    }

    /**
     * Setter for the included JSON API resources.
     *
     * @param JSONAPIGroup $included
     */
    public function setIncluded($included)
    {
        $this->included = $included;
    }

    /**
     * Add an include to the included JSON API resources.
     *
     * @param JSONAPI $jsonAPI
     */
    public function addInclude(JSONAPI $jsonAPI = null)
    {
        if (!$this->included) {
            $this->setIncluded(new JSONAPIGroup());
        }

        $this->getIncluded()->addJSONAPI($jsonAPI);
    }

    /**
     * Check to see if an include already exists for the JSONAPI object.
     *
     * @param JSONAPI $jsonAPI
     *
     * @return bool
     * @throws RefineryException
     */
    public function checkIncludeExists(JSONAPI $jsonAPI)
    {
        if (isset($this->getIncluded()->tracking[$jsonAPI->getType()][$jsonAPI->getId()])) {
            return true;
        } else {
            return false;
        }
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
