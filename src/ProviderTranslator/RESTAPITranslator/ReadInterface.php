<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator;

use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * RESTAPITranslator Interface to support READ operations for NDOs.
 *
 * @package NYPL\Refinery
 */
interface ReadInterface
{
    /**
     * Method to call to create to read an NDO.
     *
     * @param RESTAPI   $provider          The Provider to read NDO from.
     * @param NDOFilter $ndofilter         The filter used to query the raw data to read.
     * @param bool      $allowEmptyResults Whether empty results are considered an error or not.
     *
     * @return mixed
     */
    public function read(RESTAPI $provider, NDOFilter $ndofilter, $allowEmptyResults = false);
}