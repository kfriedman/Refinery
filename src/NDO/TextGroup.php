<?php
namespace NYPL\Refinery\NDO;

use NYPL\Refinery\NDOGroup;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class TextGroup extends NDOGroup
{
    /**
     * @param null $itemsOrItem
     */
    public function __construct($itemsOrItem = null)
    {
        $this->setItemIndex('languageCode');

        parent::__construct($itemsOrItem);
    }

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new RESTAPI\D7RefineryServerCurrent());
    }
}