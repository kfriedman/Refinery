<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\BookListServerTranslator;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\BookListServerTranslator;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class BookListItemGroupTranslator extends BookListServerTranslator implements
    RESTAPITranslator\ReadInterface
{
    const DEFAULT_ITEM_LIMIT = 25;

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
        if (!$ndoFilter->getFilterID()) {
            $listIndex = $provider->clientGet('lists/index.json', null, null, true);
            $listIndex = array_slice(array_column($listIndex, 'list_id'), 0, self::DEFAULT_ITEM_LIMIT);

            $ndoFilter->setFilterID($listIndex);
        }

        $listID = strtok(current($ndoFilter->getFilterID()), '-');

        $rawData = $provider->clientGet('lists/' . $listID . '.json', null, null, $allowEmptyResults);

        if (!is_array($rawData)) {
            $rawData = json_decode($rawData, true);
        }

        foreach ($rawData['items'] as &$item) {
            $item['list_id'] = $listID;
        }

        array_multisort(array_column($rawData['items'], 'index'), SORT_DESC, $rawData['items']);

        /**
         * @var RESTAPI\BookListServer $provider
         */

        return $provider->filterRawData($rawData['items'], $ndoFilter);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\SiteDatum\MegaMenuPaneGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        return $this->getNDOGroupFromRawData($providerRawData, new NDO\BookList\BookListItemGroup(), new BookListItemTranslator());
    }
}
