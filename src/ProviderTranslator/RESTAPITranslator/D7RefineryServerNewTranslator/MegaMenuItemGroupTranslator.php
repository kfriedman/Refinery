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
class MegaMenuItemGroupTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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

            return $provider->clientGet('node/scheduled_featured_item', null, $urlFilter, $allowEmptyResults);
        } else {
            if ($ndoFilter->getQueryParameter('filter')->getValue()) {
                $ndoFilter = $this->translateFilter($ndoFilter);
            }

            $ndoFilter->addQueryParameter('filter[_enhanced][pre][current]', 1);

            return $provider->clientGet('node/scheduled_featured_item', null, $ndoFilter, $allowEmptyResults);
        }
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\SiteDatum\MegaMenuItemGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        return $this->getNDOGroupFromRawData(
            $providerRawData,
            new NDO\SiteDatum\MegaMenuItemGroup(),
            new MegaMenuItemTranslator()
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

        $filters = $ndoFilter->getQueryParameter('filter')->getValue();

        foreach ($filters as $type => $filter) {
            switch ($type) {
                case 'container-slot':
                    $translatedFilter->addQueryParameter('filter[_enhanced][pre][slot]', $filter);
                    break;
                default:
                    $translatedFilter->addQueryParameter('filter[' . $type . ']', $filter);
                    break;
            }
        }

        return $translatedFilter;
    }
}
