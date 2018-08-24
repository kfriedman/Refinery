<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\BookListServerTranslator;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderHandler\NDOReader;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\BookListServerTranslator;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class BookListItemTranslator extends BookListServerTranslator implements
    RESTAPITranslator\ReadInterface
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
        $filter = explode('-', $ndoFilter->getFilterID());

        $listID = $filter[0];
        $itemID = $filter[1];

        $rawData = $provider->clientGet('lists/' . $listID . '.json', null, null, $allowEmptyResults);

        if (!is_array($rawData)) {
            $rawData = json_decode($rawData, true);
        }

        $itemIDArray = array_column($rawData['items'], 'library_bib_id');

        $itemIDKey = array_search($itemID, $itemIDArray);

        if ($itemIDKey !== false) {
            $item = $rawData['items'][$itemIDKey];
            $item['list_id'] = $listID;

            return $item;
        }

        return null;
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\SiteDatum\MegaMenuPaneGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\BookList\BookListItem($this->getValueFromRawData($providerRawData, 'list_id') . '-' . $this->getValueFromRawData($providerRawData, 'library_bib_id'));

        $ndo->setRead(true);

        $ndo->setAnnotation($this->getValueFromRawData($providerRawData, 'user_annotation'));

        $ndo->setSortOrder($this->getValueFromRawData($providerRawData, 'index'));

        if ($itemID = $this->getValueFromRawData($providerRawData, 'library_bib_id')) {
            if ($item = $this->getValueFromRawData($providerRawData, 'item')) {
                $item['library_bib_id'] = $itemID;
                NDOReader::readNDO(new RESTAPI\BookListServer(), new NDO\BookList\BookItem(), new NDOFilter($itemID), $item, false, false, false, true);
            }

            $ndo->setItem(new NDO\BookList\BookItem($itemID));
        }

        return $ndo;
    }
}
