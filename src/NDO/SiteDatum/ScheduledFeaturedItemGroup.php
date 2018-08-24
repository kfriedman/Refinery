<?php
namespace NYPL\Refinery\NDO\SiteDatum;

use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class ScheduledFeaturedItemGroup extends FeaturedItemGroup
{
    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new RESTAPI\D7RefineryServerNew());
    }
}
