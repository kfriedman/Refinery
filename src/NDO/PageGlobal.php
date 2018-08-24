<?php
namespace NYPL\Refinery\NDO;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Abstract class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class PageGlobal extends NDO
{
    /**
     * @var NDO\SiteDatum\HeaderItemGroup
     */
    public $topLevelHeaderItems;

    /**
     * @var NDO\SiteDatum\FooterItemGroup
     */
    public $topLevelFooterItems;

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new RESTAPI\D7RefineryServerNew());
    }

    /**
     * @return NDO\SiteDatum\HeaderItemGroup
     */
    public function getTopLevelHeaderItems()
    {
        return $this->topLevelHeaderItems;
    }

    /**
     * @param NDO\SiteDatum\HeaderItemGroup $topLevelHeaderItems
     */
    public function setTopLevelHeaderItems(NDO\SiteDatum\HeaderItemGroup $topLevelHeaderItems)
    {
        $this->topLevelHeaderItems = $topLevelHeaderItems;
    }

    /**
     * @return NDO\SiteDatum\FooterItemGroup
     */
    public function getTopLevelFooterItems()
    {
        return $this->topLevelFooterItems;
    }

    /**
     * @param NDO\SiteDatum\FooterItemGroup $topLevelFooterItems
     */
    public function setTopLevelFooterItems(NDO\SiteDatum\FooterItemGroup $topLevelFooterItems)
    {
        $this->topLevelFooterItems = $topLevelFooterItems;
    }
}
