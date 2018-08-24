<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * RESTAPITranslator Interface to support CREATE operations for NDOs.
 *
 * @package NYPL\Refinery
 */
interface CreateInterface
{
    /**
     * Method to call to create a NDO.
     *
     * @param RESTAPI $provider The Provider to create NDO on.
     * @param NDO     $ndo      The NDO that you want to create.
     *
     * @return mixed
     */
    public function create(RESTAPI $provider, NDO $ndo);
}