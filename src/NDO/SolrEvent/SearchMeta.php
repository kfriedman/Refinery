<?php
namespace NYPL\Refinery\NDO\SolrEvent;

use NYPL\Refinery\NDO;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class SearchMeta extends NDO
{
    /**
     * @var int
     */
    public $numFound = 0;

    /**
     * @var int
     */
    public $start = 0;

    /**
     * @return int
     */
    public function getNumFound()
    {
        return $this->numFound;
    }

    /**
     * @param int $numFound
     */
    public function setNumFound($numFound)
    {
        $this->numFound = (int) $numFound;
    }

    /**
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param int $start
     */
    public function setStart($start)
    {
        $this->start = (int) $start;
    }
}
