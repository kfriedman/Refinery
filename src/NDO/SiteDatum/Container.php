<?php
namespace NYPL\Refinery\NDO\SiteDatum;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI\D7RefineryServerNew;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class Container extends NDO
{
    /**
     * @var NDO\TextGroup
     */
    public $name;

    /**
     * @var FeaturedItemTypeGroup
     */
    public $relatedFeaturedItemTypes;

    /**
     * @var ContainerSlotGroup
     */
    public $slots;

    /**
     * @var int
     */
    public $sortOrder = 0;

    /**
     * @var NDO\URI
     */
    public $link;

    /**
     * @var string
     */
    public $slug = '';

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
     * @return FeaturedItemTypeGroup
     */
    public function getRelatedFeaturedItemTypes()
    {
        return $this->relatedFeaturedItemTypes;
    }

    /**
     * @param FeaturedItemTypeGroup $relatedFeaturedItemTypes
     */
    public function setRelatedFeaturedItemTypes(FeaturedItemTypeGroup $relatedFeaturedItemTypes)
    {
        $this->relatedFeaturedItemTypes = $relatedFeaturedItemTypes;
    }

    /**
     * @return ContainerSlotGroup
     */
    public function getSlots()
    {
        return $this->slots;
    }

    /**
     * @param ContainerSlotGroup $slots
     */
    public function setSlots(ContainerSlotGroup $slots)
    {
        $this->slots = $slots;
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
     * @return NDO\URI
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param NDO\URI $link
     */
    public function setLink(NDO\URI $link)
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
}
