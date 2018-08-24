<?php
namespace NYPL\Refinery;

use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\Helpers\ClassNameHelper;

/**
 * Class for all NDOGroup objects. This is also a child of the
 * primary NDO object.
 *
 * @package NYPL\Refinery
 */
class NDOGroup extends NDO
{
    /**
     * The ArrayIterator object that stores all NDOs for this NDOGroup.
     *
     * @var \ArrayIterator
     */
    public $items;

    /**
     * The name of the index for the NDOGroup.
     *
     * @var string
     */
    protected $itemIndex = '';

    /**
     * @var array
     */
    protected $ndoIDArray = array();

    protected $perPage = 0;

    protected $page = 0;

    protected $count = 0;

    /**
     * Constructor for NDOGroup class. Initializes the $items ArrayIterator
     * object with items from the passed array. Also runs the constructor on
     * the parent NDO object.
     *
     * @param array|null $itemsOrItem
     */
    public function __construct($itemsOrItem = null)
    {
        $this->initializeItems($itemsOrItem);

        parent::__construct();
    }

    /**
     * Initializes the $items property with an PHP SPL ArrayIterator object.
     * You can pass this an array and have the items appended to the
     * ArrayIterator object.
     *
     * @see http://php.net/manual/en/class.arrayiterator.php
     *
     * @param array|null $itemsOrItem
     */
    public function initializeItems($itemsOrItem = null)
    {
        if ($this->items === null) {
            $this->items = new \ArrayIterator();
        }

        if ($itemsOrItem) {
            if (is_array($itemsOrItem)) {
                foreach ($itemsOrItem as $item) {
                    if ($item instanceof NDO) {
                        /**
                         * @var NDO $item
                         */
                        $this->append($item);
                    } else {
                        $this->appendNDOFromID($item);
                    }
                }
            } else {
                if ($itemsOrItem instanceof NDO) {
                    $this->append($itemsOrItem);
                } else {
                    $this->appendNDOFromID($itemsOrItem);
                }
            }
        }
    }

    /**
     * @param string $ndoID
     *
     * @throws RefineryException
     */
    protected function appendNDOFromID($ndoID = '')
    {
        $itemType = $this->getItemType($this);

        if (!class_exists($itemType)) {
            throw new RefineryException('NDO (' . $itemType . ') for NDOGroup does not exist');
        }

        /**
         * @var NDO $ndo
         */
        $ndo = new $itemType($ndoID);

        $this->append($ndo);
    }

    /**
     * Append an NDO to the $items property.
     *
     * @param NDO    $item
     * @param bool   $allowDuplicates
     * @param string $languageCode
     *
     * @throws RefineryException
     */
    public function append(NDO $item, $allowDuplicates = false, $languageCode = '')
    {
        $this->initializeItems();

        $this->checkItemType($item);

        if ($allowDuplicates || !in_array($item->getNdoID(), $this->getNdoIDArray())) {
            $this->items->append($item);

            $this->addNdoID($item->getNdoID());
        }
    }

    /**
     * Get the type name of the NDOGroup.
     *
     * @param NDOGroup $ndoGroup
     *
     * @return string
     */
    protected function getItemType(NDOGroup $ndoGroup)
    {
        return substr(get_class($ndoGroup), 0, -5);
    }

    /**
     * Check that the NDO added to this NDOGroup is the right NDO. Compares
     * the class names to see if it is the proper type.
     *
     * @param NDO $item
     *
     * @throws RefineryException
     */
    protected function checkItemType(NDO $item)
    {
        $groupType = $this->getItemType($this);
        $groupTypeNoNamespace = ClassNameHelper::stripNamespace($this->getItemType($this));
        $itemNDOType = $item->getNdoType();

        if ($itemNDOType !== $groupTypeNoNamespace && !is_a($item, $groupType)) {
            throw new RefineryException('Item (' . $itemNDOType . ') appended to group not required type (' . $groupType . ')');
        }
    }

    /**
     * Search the NDOs in the NDOGroup by an index (field) for a particular
     * value. Returns an ArrayIterator object with the matched NDOs.
     *
     * @param string $searchIndex The index (field) to search in.
     * @param null   $searchValue The value to search for.
     *
     * @return \ArrayIterator     Contains the NDOs matching the search parameters.
     */
    public function searchItemsByIndex($searchIndex = '', $searchValue = null)
    {
        $matchedItems = new \ArrayIterator();

        foreach ($this->items as $item) {
            if (isset($item->$searchIndex)) {
                if ($item->$searchIndex == $searchValue || $searchValue === null) {
                    $matchedItems->append($item);
                }
            }
        }

        return $matchedItems;
    }

    /**
     * Checks whether items exist in $items.
     *
     * @see http://php.net/manual/en/arrayiterator.valid.php
     *
     * @return bool
     */
    public function itemsExist()
    {
        if ($this->items->valid()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Getter for the name of the index for the NDOGroup.
     *
     * @return string
     */
    public function getItemIndex()
    {
        return $this->itemIndex;
    }

    /**
     * Setter for the name of the index for the NDOGroup.
     *
     * @param string $itemIndex
     */
    public function setItemIndex($itemIndex)
    {
        $this->itemIndex = $itemIndex;
    }

    /**
     * Add an ID to NDO ID array.
     *
     * @param string $ndoID
     *
     * @throws RefineryException
     */
    public function addNdoID($ndoID = '')
    {
        if ($ndoID) {
            $this->ndoIDArray[] = $ndoID;
        }
    }

    /**
     * @return array
     */
    public function getNdoIDArray()
    {
        return $this->ndoIDArray;
    }

    /**
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     */
    public function setPerPage($perPage)
    {
        $this->perPage = (int) $perPage;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        $this->page = (int) $page;
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
