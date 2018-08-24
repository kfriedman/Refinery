<?php

namespace NYPL\Refinery\NDO\SolrEvent;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class MetricsSearch extends NDO
{
    /**
     * @var int
     */
    public $year;

    /**
     * @var int
     */
    public $month;

    /**
     * @var int
     */
    public $status;

    /**
     * @var EventMetricsGroup
     */
    public $eventMetrics;

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new RESTAPI\SolrEvent());
    }

    /**
     * @param int $year
     */
    public function setYear($year)
    {
        $this->year = (int) $year;
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param int $month
     */
    public function setMonth($month)
    {
        $this->month = (int) $month;
    }

    /**
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = (int) $status;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param EventMetricsGroup $eventMetrics
     */
    public function setEventMetrics(EventMetricsGroup $eventMetrics)
    {
        $this->eventMetrics = $eventMetrics;
    }

    /**
     * @return EventMetricsGroup
     */
    public function getEventMetrics()
    {
        return $this->eventMetrics;
    }

    /**
     * @return boolean
     */
    public function isCacheable()
    {
        return false;
    }
}
