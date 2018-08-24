<?php
namespace NYPL\Refinery\NDO\SolrEvent;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class Search extends NDO
{
    /**
     * @var string
     */
    public $q = '*:*';

    /**
     * @var array
     */
    public $fq = ['date_time_start: [NOW-1HOUR TO *]', 'status:1'];

    /**
     * @var array
     */
    public $sort = [
        'date_time_start asc',
        'library_name asc'
    ];

    /**
     * @var int
     */
    public $start = 0;

    /**
     * @var int
     */
    public $rows = RESTAPI\SolrEvent::DEFAULT_ROWS_PER_PAGE;

    /**
     * @var array
     */
    public $facetFields = [
        'library_name',
        'audience',
        'support_audience',
        'event_type',
        'event_topic',
        'series',
        'city'
    ];

    /**
     * @var NDO\SolrEvent\SearchMeta
     */
    public $meta;

    /**
     * @var NDO\SolrEvent\EventGroup
     */
    public $events;

    /**
     * @var NDO\SolrEvent\SearchFacetGroup
     */
    public $facets;

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new RESTAPI\SolrEvent());
    }

    /**
     * @return string
     */
    public function getQ()
    {
        return $this->q;
    }

    /**
     * @param string $q
     */
    public function setQ($q)
    {
        $this->q = $q;
    }

    /**
     * @return array
     */
    public function getFq()
    {
        return $this->fq;
    }

    /**
     * @param array $fq
     */
    public function setFq($fq)
    {
        if (is_string($fq)) {
            $this->fq = array_filter(explode(',', $fq), 'trim');
        } else {
            $this->fq = $fq;
        }
    }

    /**
     * @return array
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param array $sort
     */
    public function setSort($sort)
    {
        if (is_string($sort)) {
            $this->sort = array_filter(explode(',', $sort), 'trim');
        } else {
            $this->sort = $sort;
        }
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

    /**
     * @return int
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param int $rows
     */
    public function setRows($rows)
    {
        $this->rows = (int) $rows;
    }

    /**
     * @return array
     */
    public function getFacetFields()
    {
        return $this->facetFields;
    }

    /**
     * @param array $facetFields
     */
    public function setFacetFields($facetFields)
    {
        if (is_string($facetFields)) {
            $this->facetFields = array_filter(explode(',', $facetFields), 'trim');
        } else {
            $this->facetFields = $facetFields;
        }
    }

    /**
     * @return NDO\SolrEvent\SearchMeta
     */
    public function getMeta()
    {
        if (!$this->meta) {
            $this->setMeta(new NDO\SolrEvent\SearchMeta());
        }

        return $this->meta;
    }

    /**
     * @param NDO\SolrEvent\SearchMeta $meta
     */
    public function setMeta(NDO\SolrEvent\SearchMeta $meta)
    {
        $this->meta = $meta;
    }

    /**
     * @return NDO\SolrEvent\EventGroup
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param NDO\SolrEvent\EventGroup $events
     */
    public function setEvents(NDO\SolrEvent\EventGroup $events)
    {
        $this->events = $events;
    }

    /**
     * @return NDO\SolrEvent\SearchFacetGroup
     */
    public function getFacets()
    {
        return $this->facets;
    }

    /**
     * @param NDO\SolrEvent\SearchFacetGroup $facets
     */
    public function setFacets(NDO\SolrEvent\SearchFacetGroup $facets)
    {
        $this->facets = $facets;
    }

    /**
     * @return boolean
     */
    public function isCacheable()
    {
        return false;
    }
}
