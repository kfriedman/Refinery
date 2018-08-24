<?php
namespace NYPL\Refinery\NDO\SiteDatum;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI\D7RefineryServerNew;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class HeaderItem extends NDO
{
    /**
     * @var NDO\TextGroup
     */
    public $name;

    /**
     * @var NDO\TextGroup
     */
    public $link;

    /**
     * @var int
     */
    public $sort = 0;

    /**
     * @var MegaMenuPaneGroup
     */
    public $relatedMegaMenuPanes;

    /**
     * @var ContainerSlotGroup
     */
    public $relatedContainerSlots;

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new D7RefineryServerNew());
    }

    /**
     * @return NDO\TextGroup
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param NDO\TextGroup $name
     */
    public function setName(NDO\TextGroup $name)
    {
        $this->name = $name;
    }

    /**
     * @return NDO\TextGroup
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param NDO\TextGroup $link
     */
    public function setLink(NDO\TextGroup $link)
    {
        $this->link = $link;
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
     * @return MegaMenuPaneGroup
     */
    public function getRelatedMegaMenuPanes()
    {
        return $this->relatedMegaMenuPanes;
    }

    /**
     * @param MegaMenuPaneGroup $relatedMegaMenuPanes
     */
    public function setRelatedMegaMenuPanes(MegaMenuPaneGroup $relatedMegaMenuPanes)
    {
        $this->relatedMegaMenuPanes = $relatedMegaMenuPanes;
    }

    /**
     * @return ContainerSlotGroup
     */
    public function getRelatedContainerSlots()
    {
        return $this->relatedContainerSlots;
    }

    /**
     * @param ContainerSlotGroup $relatedContainerSlots
     */
    public function setRelatedContainerSlots(ContainerSlotGroup $relatedContainerSlots)
    {
        $this->relatedContainerSlots = $relatedContainerSlots;
    }
}
