<?php
namespace NYPL\Refinery\NDO\Content;

use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\NDO;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class LocationHours extends NDO\Content
{
    /**
     * @var NDO\LocalDateTime
     */
    public $startDateTime;

    /**
     * @var NDO\LocalDateTime
     */
    public $endDateTime;

    /**
     * @var int
     */
    public $startDay = 0;

    /**
     * @var int
     */
    public $startHour = 0;

    /**
     * @var string
     */
    public $startMinute = '';

    /**
     * @var int
     */
    public $endDay = 0;

    /**
     * @var int
     */
    public $endHour = 0;

    /**
     * @var string
     */
    public $endMinute = '';

    /**
     * @return int
     */
    public function getStartDay()
    {
        return $this->startDay;
    }

    /**
     * @param int $startDay
     */
    public function setStartDay($startDay)
    {
        $this->startDay = (int) $startDay;
    }

    /**
     * @return int
     */
    public function getStartHour()
    {
        return $this->startHour;
    }

    /**
     * @param int $startHour
     */
    public function setStartHour($startHour)
    {
        $this->startHour = (int) $startHour;
    }

    /**
     * @return string
     */
    public function getStartMinute()
    {
        return $this->startMinute;
    }

    /**
     * @param string $startMinute
     */
    public function setStartMinute($startMinute = '')
    {
        $this->startMinute = $startMinute;
    }

    /**
     * @return int
     */
    public function getEndDay()
    {
        return $this->endDay;
    }

    /**
     * @param int $endDay
     */
    public function setEndDay($endDay)
    {
        $this->endDay = (int) $endDay;
    }

    /**
     * @return int
     */
    public function getEndHour()
    {
        return $this->endHour;
    }

    /**
     * @param int $endHour
     */
    public function setEndHour($endHour)
    {
        $this->endHour = (int) $endHour;
    }

    /**
     * @return string
     */
    public function getEndMinute()
    {
        return $this->endMinute;
    }

    /**
     * @param string $endMinute
     */
    public function setEndMinute($endMinute = '')
    {
        $this->endMinute = $endMinute;
    }

    /**
     * @return NDO\LocalDateTime
     */
    public function getStartDateTime()
    {
        return $this->startDateTime;
    }

    /**
     * @param NDO\LocalDateTime $startDateTime
     */
    public function setStartDateTime(NDO\LocalDateTime $startDateTime)
    {
        $this->startDateTime = $startDateTime;

        $this->setStartDay($this->getStartDateTime()->getDateTime()->format('w'));
        $this->setStartHour($this->getStartDateTime()->getDateTime()->format('G'));
        $this->setStartMinute($this->getStartDateTime()->getDateTime()->format('i'));
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

        $this->setEndDay($this->getEndDateTime()->getDateTime()->format('w'));
        $this->setEndHour($this->getEndDateTime()->getDateTime()->format('G'));
        $this->setEndMinute($this->getEndDateTime()->getDateTime()->format('i'));
    }

    /**
     * @param int $day
     *
     * @return string
     * @throws RefineryException
     */
    public function dayOfWeekToDayText($day = 0)
    {
        switch ($day) {
            case 0:
                $dayText = 'Sun.';
                break;
            case 1:
                $dayText = 'Mon.';
                break;
            case 2:
                $dayText = 'Tue.';
                break;
            case 3:
                $dayText = 'Wed.';
                break;
            case 4:
                $dayText = 'Thu.';
                break;
            case 5:
                $dayText = 'Fri.';
                break;
            case 6:
                $dayText = 'Sat.';
                break;
            default:
                throw new RefineryException('Day parameter is not valid');
                break;
        }

        return $dayText;
    }
}