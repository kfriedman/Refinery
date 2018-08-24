<?php
namespace NYPL\Refinery\NDO;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RemoteJSON\StaffPicksServer;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class StaffPick extends NDO
{
    /**
     * @var string
     */
    public $text = '';

    /**
     * @var string
     */
    public $location = '';

    /**
     * @var string
     */
    public $pickerName = '';

    /**
     * @var int
     */
    public $sort = 0;

    /**
     * @var StaffPick\StaffPickItem
     */
    public $item;

    /**
     * @var StaffPick\StaffPickList
     */
    public $list;

    /**
     * @var NDO\StaffPick\StaffPickAge
     */
    public $age;

    /**
     * @var bool
     */
    public $feature = false;

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new StaffPicksServer());
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getPickerName()
    {
        return $this->pickerName;
    }

    /**
     * @param string $pickerName
     */
    public function setPickerName($pickerName)
    {
        $this->pickerName = $pickerName;
    }

    /**
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     */
    public function setSort($sort)
    {
        $this->sort = (int) $sort;
    }

    /**
     * @return StaffPick\StaffPickItem
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param StaffPick\StaffPickItem $item
     */
    public function setItem(StaffPick\StaffPickItem $item)
    {
        $this->item = $item;
    }

    /**
     * @return StaffPick\StaffPickList
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @param StaffPick\StaffPickList $list
     */
    public function setList(StaffPick\StaffPickList $list)
    {
        $this->list = $list;
    }

    /**
     * @return StaffPick\StaffPickAge
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param StaffPick\StaffPickAge $age
     */
    public function setAge(StaffPick\StaffPickAge $age)
    {
        $this->age = $age;
    }

    /**
     * @return boolean
     */
    public function isFeature()
    {
        return $this->feature;
    }

    /**
     * @param boolean $feature
     */
    public function setFeature($feature)
    {
        $this->feature = (bool) $feature;
    }
}