<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerNewTranslator;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerTranslator;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class MegaMenuPaneGroupTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
        if ($ndoFilter->getFilterID()) {
            $urlFilter = new NDOFilter();
            $urlFilter->addQueryParameter('filter[uuid]', implode('|', $ndoFilter->getFilterID()));

            return $provider->clientGet('taxonomy/term/containers_slots', null, $urlFilter, $allowEmptyResults);
        } else {
            if ($ndoFilter->getQueryParameter('filter')->getValue()) {
                $ndoFilter = $this->translateFilter($ndoFilter);
            }

            return $provider->clientGet('taxonomy/term/containers_slots', null, $ndoFilter, $allowEmptyResults);
        }
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\SiteDatum\MegaMenuPaneGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        return $this->getNDOGroupFromRawData(
            $providerRawData,
            new NDO\SiteDatum\MegaMenuPaneGroup(),
            new MegaMenuPaneTranslator()
        );
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

        if (isset($filter['field_rel_container'])) {
            $translatedFilter->addQueryParameter('filter[field_rel_container]', $filter['field_rel_container']);
        }

        return $translatedFilter;
    }
}
