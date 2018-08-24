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
class UriAliasGroupTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
            $urlFilter->addQueryParameter('filter[pid]', implode('|', $ndoFilter->getFilterID()));

            return $provider->clientGet('admin/url_aliases', null, $urlFilter, $allowEmptyResults);

        } else {
            if ($ndoFilter->getQueryParameter('filter')->getValue()) {
                $ndoFilter = $this->translateFilter($ndoFilter);
            }

            return $provider->clientGet('admin/url_aliases', null, $ndoFilter, $allowEmptyResults);
        }
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\UriAliasGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        return $this->getNDOGroupFromRawData($providerRawData, new NDO\UriAliasGroup(), new UriAliasTranslator());
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

        if (isset($filter['relationships']['related-page'])) {
            $translatedFilter->addQueryParameter('filter[_enhanced][node]', $filter['relationships']['related-page']);
        }

        if (isset($filter['alias'])) {
            $translatedFilter->addQueryParameter('filter[alias]', $filter['alias']);
        }

        if (isset($filter['source'])) {
            $translatedFilter->addQueryParameter('filter[source]', $filter['source']);
        }

        return $translatedFilter;
    }
}
