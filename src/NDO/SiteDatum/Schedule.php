<?php
namespace NYPL\Refinery\NDO\SiteDatum;

use NYPL\Refinery\NDO;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class Schedule extends NDO
{
    /**
     * @var string
     */
    public $slotUUID = '';

    /**
     * @var NDO\LocalDateTime
     */
    public $beginDateTime;

    /**
     * @var NDO\LocalDateTime
     */
    public $endDateTime;

    /**
     * @return string
     */
    public function getSlotUUID()
    {
        return $this->slotUUID;
    }

    /**
     * @param string $slotUUID
     */
    public function setSlotUUID($slotUUID)
    {
        $this->slotUUID = $slotUUID;
    }

    /**
     * @return NDO\LocalDateTime
     */
    public function getBeginDateTime()
    {
        return $this->beginDateTime;
    }

    /**
     * @param NDO\LocalDateTime $beginDateTime
     */
    public function setBeginDateTime(NDO\LocalDateTime $beginDateTime)
    {
        $this->beginDateTime = $beginDateTime;
    }

    /**
     * @return NDO\LocalDateTime
     */
    public function getEndDateTime()
    {
        return $this->endDateTime;
    }

    /**
     * @param NDO\LocalDateTime $endDateTime
     */
    public function setEndDateTime(NDO\LocalDateTime $endDateTime)
    {
        $this->endDateTime = $endDateTime;
    }
}
