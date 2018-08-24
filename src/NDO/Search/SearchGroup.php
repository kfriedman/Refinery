<?php
namespace NYPL\Refinery\NDO\Search;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOGroup;
use NYPL\Refinery\Provider\RESTAPI\GoogleSearch;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
abstract class SearchGroup extends NDOGroup
{
    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new GoogleSearch());
    }
}
