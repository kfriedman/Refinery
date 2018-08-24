<?php
namespace NYPL\Refinery\NDO\HybridPage;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI\D7RefineryServerNew;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
abstract class HybridPage extends NDO
{
    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new D7RefineryServerNew());
    }
}
