<?php
namespace NYPL\Refinery\NDO\SiteDatum;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI\D7RefineryServerNew;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class ScheduledFeaturedItem extends FeaturedItem
{
    /**
     * @var FeaturedItem
     */
    public $featuredItem;

    /**
     * @var ScheduleGroup
     */
    public $currentSchedules;

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new D7RefineryServerNew());
    }


    /**
     * @return FeaturedItem
     */
    public function getFeaturedItem()
    {
        return $this->featuredItem;
    }

    /**
     * @param FeaturedItem $featuredItem
     */
    public function setFeaturedItem(FeaturedItem $featuredItem)
    {
        $this->featuredItem = $featuredItem;
    }

    /**
     * @return ScheduleGroup
     */
    public function getCurrentSchedules()
    {
        return $this->currentSchedules;
    }

    /**
     * @param ScheduleGroup $currentSchedules
     */
    public function setCurrentSchedules(ScheduleGroup $currentSchedules)
    {
        $this->currentSchedules = $currentSchedules;
    }
}
