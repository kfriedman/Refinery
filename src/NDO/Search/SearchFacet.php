<?php
namespace NYPL\Refinery\NDO\Search;

use NYPL\Refinery\NDO;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class SearchFacet extends NDO
{
    public $label = '';

    public $anchor = '';

    public $label_with_op;

    public $count = 0;

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getAnchor()
    {
        return $this->anchor;
    }

    /**
     * @param string $anchor
     */
    public function setAnchor($anchor)
    {
        $this->anchor = $anchor;
    }

    /**
     * @return mixed
     */
    public function getLabelWithOp()
    {
        return $this->label_with_op;
    }

    /**
     * @param mixed $label_with_op
     */
    public function setLabelWithOp($label_with_op)
    {
        $this->label_with_op = $label_with_op;
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
}
