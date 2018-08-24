<?php
namespace NYPL\Refinery\JSONAPI;

/**
 * Class for JSON API Relationships.
 *
 * @package NYPL\Refinery
 */
class JSONAPIRelationship
{
    /**
     * JSON API representing the relationship.
     *
     * @var JSONAPI
     */
    public $jsonAPI;

    /**
     * Constructor for the JSON API relationship.
     *
     * @param JSONAPI $jsonAPI
     */
    public function __construct(JSONAPI $jsonAPI = null)
    {
        if ($jsonAPI) {
            $this->setJSONAPI($jsonAPI);
        }
    }

    /**
     * Getter for the JSON API representing the relationship.
     *
     * @return JSONAPI
     */
    public function getJSONAPI()
    {
        return $this->jsonAPI;
    }

    /**
     * Setter for the JSON API representing the relationship.
     *
     * @param JSONAPI $jsonAPI
     */
    public function setJSONAPI($jsonAPI)
    {
        $this->jsonAPI = $jsonAPI;
    }
}