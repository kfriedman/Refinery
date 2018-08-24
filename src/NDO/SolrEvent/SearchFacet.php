<?php
namespace NYPL\Refinery\NDO\SolrEvent;

use NYPL\Refinery\NDO;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class SearchFacet extends NDO
{
    /**
     * @var array
     */
    public $items;

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }
}
