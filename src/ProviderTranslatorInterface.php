<?php
namespace NYPL\Refinery;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider;

/**
 * Interface for all ProviderTranslator objects.
 *
 * ProviderTranslators do the heavy lifting in the Refinery. They determine HOW
 * to get data from a Provider and also HOW to translate data from a Provider's
 * proprietary data format to an NDO.
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
interface ProviderTranslatorInterface
{
    /**
     * Method used to translate from a Provider's proprietary data format in a
     * ProviderRawData object to an NDO.
     *
     * @param ProviderRawData $providerRawData
     *
     * @return NDO
     */
    public function translate(ProviderRawData $providerRawData);

    /**
     * Method used to get the timezone setting from a Provider for date/time
     * calculations.
     *
     * @return string
     */
    public function getTimeZone();
}