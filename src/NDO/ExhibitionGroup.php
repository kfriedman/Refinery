<?php
namespace NYPL\Refinery\NDO;

use NYPL\Refinery\NDOGroup;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 */
class ExhibitionGroup extends NDOGroup
{
    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new RESTAPI\D7RefineryServerCurrent());
    }
}