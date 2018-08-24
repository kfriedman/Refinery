<?php
namespace NYPL\Refinery\NDO\Search;

use NYPL\Refinery\NDO;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class SearchMeta extends NDO
{
    public $totalResults = 0;

    public $count = 0;

    public $correctedQuery = '';

    public $htmlCorrectedQuery = '';

    /**
     * @return int
     */
    public function getTotalResults()
    {
        return $this->totalResults;
    }

    /**
     * @param int $totalResults
     */
    public function setTotalResults($totalResults)
    {
        $this->totalResults = (int) $totalResults;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount($count)
    {
        $this->count = (int) $count;
    }

    /**
     * @return string
     */
    public function getCorrectedQuery()
    {
        return $this->correctedQuery;
    }

    /**
     * @param string $correctedQuery
     */
    public function setCorrectedQuery($correctedQuery)
    {
        $this->correctedQuery = $correctedQuery;
    }

    /**
     * @return string
     */
    public function getHtmlCorrectedQuery()
    {
        return $this->htmlCorrectedQuery;
    }

    /**
     * @param string $htmlCorrectedQuery
     */
    public function setHtmlCorrectedQuery($htmlCorrectedQuery)
    {
        $this->htmlCorrectedQuery = $htmlCorrectedQuery;
    }
}
