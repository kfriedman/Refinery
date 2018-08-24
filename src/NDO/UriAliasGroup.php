<?php
namespace NYPL\Refinery\NDO;

use NYPL\Refinery\NDOGroup;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class UriAliasGroup extends NDOGroup
{
    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new RESTAPI\D7RefineryServerNew());
        $this->addSupportedReadProvider(new RESTAPI\D7RefineryServerCurrent());
    }
}
