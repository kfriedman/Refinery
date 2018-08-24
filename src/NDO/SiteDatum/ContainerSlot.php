<?php
namespace NYPL\Refinery\NDO\SiteDatum;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI\D7RefineryServerNew;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class ContainerSlot extends NDO
{
    /**
     * @var Container
     */
    public $container;

    /**
     * @var int
     */
    public $sortOrder = 0;

    /**
     * @var FeaturedItem
     */
    public $currentItem;

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new D7RefineryServerNew());
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
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
     * @return FeaturedItem
     */
    public function getCurrentItem()
    {
        return $this->currentItem;
    }

    /**
     * @param FeaturedItem $currentItem
     */
    public function setCurrentItem(FeaturedItem $currentItem)
    {
        $this->currentItem = $currentItem;
    }
}
