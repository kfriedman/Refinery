<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerNewTranslator;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class ScheduledFeaturedItemGroupTranslator extends FeaturedItemGroupTranslator implements RESTAPITranslator\ReadInterface
{
    /**
     * @param RESTAPI   $provider
     * @param NDOFilter $ndoFilter
     * @param bool      $allowEmptyResults
     *
     * @return string
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    public function read(RESTAPI $provider, NDOFilter $ndoFilter, $allowEmptyResults = false)
    {
        if ($ndoFilter->getQueryParameter('filter')->getValue()) {
            $ndoFilter = $this->translateFilter($ndoFilter);
        }

        $ndoFilter->addQueryParameter('filter[_enhanced][pre][current]', 1);

        return $provider->clientGet('node/scheduled_featured_item', null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\SiteDatum\ScheduledFeaturedItemGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        return $this->getNDOGroupFromRawData($providerRawData, new NDO\SiteDatum\ScheduledFeaturedItemGroup(), new ScheduledFeaturedItemTranslator());
    }

    /**
     * @param NDOFilter $ndoFilter
     *
     * @return NDOFilter
     */
    protected function translateFilter(NDOFilter $ndoFilter)
    {
        $translatedFilter = new NDOFilter();

        $filter = $ndoFilter->getQueryParameter('filter')->getValue();

        if (isset($filter['current-schedules']['slot-uuid'])) {
            $translatedFilter->addQueryParameter('filter[_enhanced][pre][slot]', $filter['current-schedules']['slot-uuid']);
        }

        return $translatedFilter;
    }
}
