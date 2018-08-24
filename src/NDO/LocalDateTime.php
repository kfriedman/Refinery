<?php
namespace NYPL\Refinery\NDO;

use DateTimeZone;
use NYPL\Refinery\NDO;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class LocalDateTime extends NDO implements \Serializable
{
    const LOCAL_TIME_ZONE = 'America/New_York';

    /**
     * @var \DateTime
     */
    protected $dateTime;

    /**
     * @param string       $time
     * @param DateTimeZone $timezone
     */
    public function __construct($time = 'now', DateTimeZone $timezone = null)
    {
        if (!$this->dateTime) {
            $this->setDateTime(new \DateTime($time, $timezone));
        }

        $this->getDateTime()->setTimezone(new DateTimeZone(self::LOCAL_TIME_ZONE));

        parent::__construct();
    }

    public function serialize()
    {
        return $this->getDateTime()->format('c');
    }

    public function unserialize($serialized = '')
    {
        $this->setDateTime(new \DateTime($serialized));
    }

    /**
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param \DateTime $dateTime
     */
    public function setDateTime(\DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->getDateTime()->format('c');
    }

    /**
     * @param LocalDateTime $beginTime
     *
     * @return $this
     */
    public function fixDrupalEndTime(LocalDateTime $beginTime)
    {
        if ($this == $beginTime) {
            if ($this->getDateTime()->format('G') == 0) {
                $this->getDateTime()->add(new \DateInterval('P1D'));
            }
            if ($this->getDateTime()->format('G') != 0) {
                $this->getDateTime()->add(new \DateInterval('PT1H'));
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isCurrent()
    {
        if ($this->getDateTime() >= new \DateTime()) {
            return true;
        } else {
            return false;
        }
    }
}
