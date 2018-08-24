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
class BookItemGroupTranslator extends BookListServerTranslator implements
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
        $data = array();

        if (!$ndoFilter->getFilterID()) {
            $itemIndex = $provider->clientGet('items/index.json', null, null, true);
            $itemIndex = array_slice(array_column($itemIndex, 'library_bib_id'), 0, self::DEFAULT_ITEM_LIMIT);

            $ndoFilter->setFilterID($itemIndex);
        }

        foreach ($ndoFilter->getFilterID() as $id) {
            $item = $provider->clientGet('items/' . $id . '.json', null, null, true);

            if (is_array($item)) {
                $item = $item['title'];
            } else {
                $item = json_decode($item, true);
                $item = $item['title'];
            }

            $item['library_bib_id'] = $id;

            $data[] = $item;
        }

        /**
         * @var RESTAPI\BookListServer $provider
         */

        return $provider->filterRawData($data, $ndoFilter);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\SiteDatum\MegaMenuPaneGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        return $this->getNDOGroupFromRawData($providerRawData, new NDO\BookList\BookItemGroup(), new BookItemTranslator());
    }
}
