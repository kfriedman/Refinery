<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Interface UpdateInterface
 *
 * @package NYPL\Refinery\ProviderTranslator\RESTAPI
 */
interface UpdateInterface
{
    /**
     * @param RESTAPI   $provider
     * @param NDO       $ndo
     * @param NDOFilter $ndoFilter
     *
     * @return mixed
     */
    public function update(RESTAPI $provider, NDO $ndo, NDOFilter $ndoFilter);
}