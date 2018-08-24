<?php
namespace NYPL\Refinery\NDO;

use NYPL\Refinery\NDOGroup;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class DCItemGroup extends NDOGroup
{
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new RESTAPI\DC());
    }
}