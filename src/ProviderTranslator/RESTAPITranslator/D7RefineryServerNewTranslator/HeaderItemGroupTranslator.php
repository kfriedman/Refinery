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
class HeaderItemGroupTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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

            return $provider->clientGet('taxonomy/term/header_nav', null, $urlFilter, $allowEmptyResults);
        } else {
            if ($ndoFilter->getQueryParameter('filter')->getValue()) {
                $ndoFilter = $this->translateFilter($ndoFilter);
            }

            return $provider->clientGet('taxonomy/term/header_nav', null, $ndoFilter, $allowEmptyResults);
        }
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\SiteDatum\HeaderItemGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        return $this->getNDOGroupFromRawData(
            $providerRawData,
            new NDO\SiteDatum\HeaderItemGroup(),
            new HeaderItemTranslator()
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

        if (isset($filter['relationships']['parent'])) {
            $translatedFilter->addQueryParameter('filter[_enhanced][parent_uuid]', $filter['relationships']['parent']);
        }

        if (isset($filter['relationships']['landing-page'])) {
            $translatedFilter->addQueryParameter('filter[field_ers_landing_page]', $filter['relationships']['landing-page']);
        }

        return $translatedFilter;
    }
}
