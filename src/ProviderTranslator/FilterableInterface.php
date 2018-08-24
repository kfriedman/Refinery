<?php
namespace NYPL\Refinery\ProviderTranslator;

use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderRawData;

/**
 * Interface for ProviderTranslators that support filtering of raw data after
 * is it retrieved.
 *
 * @package NYPL\Refinery
 */
interface FilterableInterface
{
    /**
     * Method to call to apply filter to raw data.
     *
     * @param NDOFilter       $ndoFilter
     * @param ProviderRawData $providerRawData
     */
    public function applyFilter(NDOFilter $ndoFilter, ProviderRawData $providerRawData);
}