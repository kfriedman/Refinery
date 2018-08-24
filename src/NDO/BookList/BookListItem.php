<?php
namespace NYPL\Refinery\NDO\BookList;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI\BookListServer;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class BookListItem extends NDO
{
    /**
     * @var BookItem
     */
    public $item;

    /**
     * @var int
     */
    public $sortOrder = 0;

    /**
     * @var string
     */
    public $annotation = '';

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new BookListServer());
    }

    /**
     * @return BookItem
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param BookItem $item
     */
    public function setItem(BookItem $item)
    {
        $this->item = $item;
    }

    /**
     * @return int
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param int $sortOrder
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = (int) $sortOrder;
    }

    /**
     * @return string
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }

    /**
     * @param string $annotation
     */
    public function setAnnotation($annotation)
    {
        $this->annotation = (string) $annotation;
    }
}
