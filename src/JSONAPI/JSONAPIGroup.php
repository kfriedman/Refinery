<?php
namespace NYPL\Refinery\JSONAPI;

/**
 * Class for JSON API groups.
 *
 * @package NYPL\Refinery
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class JSONAPIGroup
{
    /**
     * An array of JSONAPI objects.
     *
     * @var JSONAPI[]
     */
    public $jsonAPI = array();

    /**
     * @var JSONAPILinks
     */
    public $links;

    /**
     * Array used to track includes
     *
     * @var array
     */
    public $tracking = array();

    /**
     * Getter for JSONAPI objects.
     *
     * @return JSONAPI[]
     */
    public function getJSONAPI()
    {
        return $this->jsonAPI;
    }

    /**
     * Setter for JSONAPI objects.
     *
     * @param JSONAPI[] $jsonAPI
     */
    public function setJSONAPI($jsonAPI)
    {
        $this->jsonAPI = $jsonAPI;
    }

    /**
     * Add a JSONAPI object to the array.
     *
     * @param JSONAPI $jsonAPI
     */
    public function addJSONAPI(JSONAPI $jsonAPI)
    {
        if (!$this->checkTracking($jsonAPI)) {
            $this->jsonAPI[] = $jsonAPI;

            $this->addTracking($jsonAPI);
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

    /**
     * @param JSONAPI $jsonAPI
     */
    protected function addTracking(JSONAPI $jsonAPI)
    {
        $this->tracking[$jsonAPI->getType()][$jsonAPI->getId()] = true;
    }

    /**
     * @param JSONAPI $jsonAPI
     *
     * @return bool
     */
    protected function checkTracking(JSONAPI $jsonAPI)
    {
        if (isset($this->tracking[$jsonAPI->getType()][$jsonAPI->getId()])) {
            return true;
        } else {
            return false;
        }
    }
}