<?php
namespace NYPL\Refinery\ProviderTranslator\RemoteJSONTranslator;

use NYPL\Refinery\NDO;
use NYPL\Refinery\ProviderTranslatorInterface;

/**
 * RemoteJSONTranslator Interface to support UPDATE operations for NDOs.
 *
 * @package NYPL\Refinery
 */
abstract class StaffPicksServerTranslator implements ProviderTranslatorInterface
{
    /**
     * Name of key that contains enhanced data.
     */
    const ENHANCED_DATA = '_enhanced';

    /**
     * Timezone to use for update operations.
     */
    const TIMEZONE = 'America/New_York';

    /**
     * Getter for the timezone constant.
     *
     * @return string
     */
    public function getTimeZone()
    {
        return self::TIMEZONE;
    }
}