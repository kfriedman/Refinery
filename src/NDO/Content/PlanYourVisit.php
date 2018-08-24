<?php
namespace NYPL\Refinery\NDO\Content;

use NYPL\Refinery\NDO;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class PlanYourVisit extends NDO\Content
{
    /**
     * @var string
     */
    public $label = '';

    /**
     * @var NDO\URI
     */
    public $uri;

    /**
     * @var int
     */
    public $sortOrder = 0;

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return NDO\URI
     */
    public function getURI()
    {
        return $this->uri;
    }

    /**
     * @param NDO\URI $uri
     */
    public function setURI(NDO\URI $uri)
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