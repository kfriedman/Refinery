<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\BookListServerTranslator;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\FilterableInterface;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\BookListServerTranslator;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class BookListGroupTranslator extends BookListServerTranslator implements
    RESTAPITranslator\ReadInterface, FilterableInterface
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
        /**
         * @var RESTAPI\BookListServer $provider
         */

        $rawData = $provider->clientGet('lists/index.json', null, null, $allowEmptyResults);

        return $rawData;
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\SiteDatum\MegaMenuPaneGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        return $this->getNDOGroupFromRawData($providerRawData, new NDO\BookListGroup(), new BookListTranslator());
    }

    /**
     * @param NDOFilter       $ndoFilter
     * @param ProviderRawData $providerRawData
     */
    public function applyFilter(NDOFilter $ndoFilter, ProviderRawData $providerRawData)
    {
        if (!$ndoFilter->getPerPage()) {
            $ndoFilter->setPerPage(25);
        }

        if (!$ndoFilter->getFilterID()) {
            $filteredData = $providerRawData->getRawDataArray();
        } else {
            $filteredData = array();

            $filterIDArray = array_flip($ndoFilter->getFilterID());

            foreach ($providerRawData->getRawDataArray() as $rawData) {
                if (!boolval($rawData['is_private']) && isset($filterIDArray[$rawData['list_id']])) {
                    $filteredData[] = $rawData;
                }
            }

            array_multisort(array_column($filteredData, 'created_date'), SORT_DESC, $filteredData);
        }

        $providerRawData->setRawDataArray($providerRawData->getProvider()->filterRawData($filteredData, $ndoFilter));
    }

}
