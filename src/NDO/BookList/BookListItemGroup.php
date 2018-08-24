<?php
namespace NYPL\Refinery\NDO\BookList;

use NYPL\Refinery\NDOGroup;
use NYPL\Refinery\Provider\RESTAPI\BookListServer;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class BookListItemGroup extends NDOGroup
{
    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new BookListServer());
    }
}