<?php
namespace NYPL\Refinery\NDO\Search;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI\GoogleSearch;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
abstract class Search extends NDO
{
    const FACET_PREFIX = 'more:';

    public $q = '';

    public $searchType = '';

    public $sort = '';

    public $start = 0;

    public $size = 0;

    protected $cache = '';

    /**
     * @var SearchMeta
     */
    public $meta;

    /**
     * @var SearchFacetGroup
     */
    public $facets;

    /**
     * @var SearchItemGroup
     */
    public $items;

    public $activeFacet = '';

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new GoogleSearch());
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
     * @return string
     */
    public function getSearchType()
    {
        return $this->searchType;
    }

    /**
     * @param string $searchType
     */
    public function setSearchType($searchType)
    {
        $this->searchType = $searchType;
    }

    /**
     * @return string
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param string $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
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
     * @return SearchItemGroup
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param SearchItemGroup $items
     */
    public function setItems(SearchItemGroup $items)
    {
        $this->items = $items;
    }

    /**
     * @return SearchFacetGroup
     */
    public function getFacets()
    {
        return $this->facets;
    }

    /**
     * @param SearchFacetGroup $facets
     */
    public function setFacets(SearchFacetGroup $facets)
    {
        $this->facets = $facets;

        $this->searchActiveFacet($this->getQ());
    }

    /**
     * @return boolean
     */
    public function isCacheable()
    {
        return false;
    }

    /**
     * @return SearchMeta
     */
    public function getMeta()
    {
        if (!$this->meta) {
            $this->setMeta(new SearchMeta());
        }

        return $this->meta;
    }

    /**
     * @param SearchMeta $meta
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;
    }

    /**
     * @return string
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param string $cache
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize($size)
    {
        $this->size = (int) $size;
    }

    /**
     * @return string
     */
    public function getActiveFacet()
    {
        return $this->activeFacet;
    }

    /**
     * @param string $activeFacet
     */
    public function setActiveFacet($activeFacet)
    {
        $this->activeFacet = $activeFacet;
    }

    /**
     * @param string $q
     */
    protected function searchActiveFacet($q = '')
    {
        if (strpos($q, self::FACET_PREFIX) !== false) {
            $qArray = explode(' ', $q);

            $activeFacet = current(
                array_filter($qArray, function ($currentQ) use ($qArray) {
                    return strpos($currentQ, self::FACET_PREFIX) !== false;
                })
            );

            if ($activeFacet && $this->getFacets()->searchItemsByIndex('label_with_op', $activeFacet)->valid()) {
                $this->setActiveFacet($activeFacet);
            }
        }
    }
}
