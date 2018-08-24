<?php
namespace NYPL\Refinery\NDO\StaffPick;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RemoteJSON\StaffPicksServer;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class StaffPickList extends NDO
{
    /**
     * @var string
     */
    public $listDate = '';

    /**
     * @var string
     */
    public $listType = '';

    /**
     * @var NDO\StaffPickGroup
     */
    public $picks;

    /**
     * @var NDO\StaffPickGroup
     */
    public $features;

    /**
     * @var StaffPickList
     */
    public $previousList;

    /**
     * @var StaffPickList
     */
    public $nextList;

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
    public function getListDate()
    {
        return $this->listDate;
    }

    /**
     * @param string $listDate
     */
    public function setListDate($listDate)
    {
        $this->listDate = $listDate;
    }

    /**
     * @return string
     */
    public function getListType()
    {
        return $this->listType;
    }

    /**
     * @param string $listType
     */
    public function setListType($listType)
    {
        $this->listType = $listType;
    }

    /**
     * @return NDO\StaffPickGroup
     */
    public function getPicks()
    {
        return $this->picks;
    }

    /**
     * @param NDO\StaffPickGroup $picks
     */
    public function setPicks(NDO\StaffPickGroup $picks)
    {
        $this->picks = $picks;
    }

    /**
     * @param NDO\StaffPick $pick
     */
    public function addPick(NDO\StaffPick $pick)
    {
        if (!$this->picks) {
            $this->setPicks(new NDO\StaffPickGroup());
        }

        $this->getPicks()->append($pick);
    }

    /**
     * @return NDO\StaffPickGroup
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * @param NDO\StaffPickGroup $features
     */
    public function setFeatures(NDO\StaffPickGroup $features)
    {
        $this->features = $features;
    }

    /**
     * @param NDO\StaffPick $pick
     */
    public function addFeature(NDO\StaffPick $pick)
    {
        if (!$this->features) {
            $this->setFeatures(new NDO\StaffPickGroup());
        }

        $this->getFeatures()->append($pick);
    }

    /**
     * @return StaffPickList
     */
    public function getPreviousList()
    {
        return $this->previousList;
    }

    /**
     * @param StaffPickList $previousList
     */
    public function setPreviousList(StaffPickList $previousList)
    {
        $this->previousList = $previousList;
    }

    /**
     * @return StaffPickList
     */
    public function getNextList()
    {
        return $this->nextList;
    }

    /**
     * @param StaffPickList $nextList
     */
    public function setNextList(StaffPickList $nextList)
    {
        $this->nextList = $nextList;
    }
}