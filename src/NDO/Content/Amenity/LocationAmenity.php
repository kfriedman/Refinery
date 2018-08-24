<?php
namespace NYPL\Refinery\NDO\Content\Amenity;

use NYPL\Refinery\NDO;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class LocationAmenity extends NDO\Content\Amenity
{
    /**
     * @var int
     */
    public $locationSortOrder = 0;

    /**
     * @var string
     */
    public $accessibilityNote = '';

    /**
     * @var bool|string
     */
    public $accessible = '';

    /**
     * @var bool
     */
    public $staffAssistanceRequired = false;

    /**
     * @return int
     */
    public function getLocationSortOrder()
    {
        return $this->locationSortOrder;
    }

    /**
     * @param int $locationSortOrder
     */
    public function setLocationSortOrder($locationSortOrder)
    {
        $this->locationSortOrder = (int) $locationSortOrder;
    }

    /**
     * @return string
     */
    public function getAccessibilityNote()
    {
        return $this->accessibilityNote;
    }

    /**
     * @param string $accessibilityNote
     */
    public function setAccessibilityNote($accessibilityNote)
    {
        $this->accessibilityNote = (string) $accessibilityNote;
    }

    /**
     * @return boolean
     */
    public function isStaffAssistanceRequired()
    {
        return $this->staffAssistanceRequired;
    }

    /**
     * @param boolean $staffAssistanceRequired
     */
    public function setStaffAssistanceRequired($staffAssistanceRequired)
    {
        $this->staffAssistanceRequired = $staffAssistanceRequired;
    }

    /**
     * @return bool|string
     */
    public function getAccessible()
    {
        return $this->accessible;
    }

    /**
     * @param bool|string $accessible
     */
    public function setAccessible($accessible)
    {
        $this->accessible = $accessible;
    }
}