<?php
namespace NYPL\Refinery\NDO\SiteDatum;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI\D7RefineryServerNew;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class MegaMenuPane extends NDO
{
    public $sort = 0;

    /**
     * @var HeaderItem
     */
    public $relatedHeaderItem;

    /**
     * @var MegaMenuItem
     */
    public $currentMegaMenuItem;

    /**
     * @var MegaMenuItem
     */
    public $defaultMegaMenuItem;

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new D7RefineryServerNew());
    }

    /**
     * @return HeaderItem
     */
    public function getRelatedHeaderItem()
    {
        return $this->relatedHeaderItem;
    }

    /**
     * @param HeaderItem $relatedHeaderItem
     */
    public function setRelatedHeaderItem(HeaderItem $relatedHeaderItem)
    {
        $this->relatedHeaderItem = $relatedHeaderItem;
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
     * @return MegaMenuItem
     */
    public function getCurrentMegaMenuItem()
    {
        return $this->currentMegaMenuItem;
    }

    /**
     * @param MegaMenuItem $currentMegaMenuItem
     */
    public function setCurrentMegaMenuItem(MegaMenuItem $currentMegaMenuItem)
    {
        $this->currentMegaMenuItem = $currentMegaMenuItem;
    }

    /**
     * @return MegaMenuItem
     */
    public function getDefaultMegaMenuItem()
    {
        return $this->defaultMegaMenuItem;
    }

    /**
     * @param MegaMenuItem $defaultMegaMenuItem
     */
    public function setDefaultMegaMenuItem(MegaMenuItem $defaultMegaMenuItem)
    {
        $this->defaultMegaMenuItem = $defaultMegaMenuItem;
    }
}