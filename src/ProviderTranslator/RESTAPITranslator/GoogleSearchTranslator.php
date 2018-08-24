<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator;

use NYPL\Refinery\Config\Config;
use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\ProviderTranslatorInterface;
use NYPL\Refinery\NDO\Search\Search;

/**
 * RESTAPITranslator Interface to support UPDATE operations for NDOs.
 *
 * @package NYPL\Refinery
 */
abstract class GoogleSearchTranslator implements ProviderTranslatorInterface
{
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

    public function getUrlFromFilter(NDOFilter $ndoFilter)
    {
        $parameters = $ndoFilter->getQueryParameter('filter')->getValue();

        $reflectedClass = new \ReflectionClass(Search::class);

        foreach ($parameters as $name => $value) {
            $setterName = 'set' . $name;

            if (!$reflectedClass->hasMethod($setterName)) {
                throw new RefineryException('Filter parameter (filter[' . $name . ']) specified is not valid');
            }
        }

        $parameters['cx'] = Config::getItem('DefaultProviders.GoogleSearch.EngineId');
        $parameters['key'] = Config::getItem('DefaultProviders.GoogleSearch.Key');

        if (isset($parameters['start'])) {
            $parameters['start']++;
        }

        if (isset($parameters['size'])) {
            unset($parameters['size']);
        }

        if (isset($parameters['num'])) {
            unset($parameters['num']);
        }

        $parameters['filter'] = 1;

        return '?' . http_build_query($parameters);
    }
}
