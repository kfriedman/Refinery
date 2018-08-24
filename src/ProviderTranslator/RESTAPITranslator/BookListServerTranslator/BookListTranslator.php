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
class BookListTranslator extends BookListServerTranslator implements
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
        if (stristr($ndoFilter->getFilterID(), 'id-') !== false) {
            $ndoFilter->setFilterID(substr($ndoFilter->getFilterID(), 3));
        }

        $rawData = $provider->clientGet('lists/' . $ndoFilter->getFilterID() . '.json', null, null, $allowEmptyResults);

        return $rawData;
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\SiteDatum\MegaMenuPaneGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\BookList($this->getValueFromRawData($providerRawData, 'list_id'));

        $ndo->setRead(true);

        $ndo->setListName($this->getValueFromRawData($providerRawData, 'list_name'));

        if ($description = $this->getValueFromRawData($providerRawData, 'description')) {
            if ($description != '(null)') {
                $ndo->setListDescription($description);
            }
        }

        $ndo->setDateCreated(new NDO\LocalDateTime($this->getValueFromRawData($providerRawData, 'created_date')));

        if ($listType = $this->getValueFromRawData($providerRawData, 'list_purpose')) {
            if ($listType != '(null)') {
                $ndo->setListType($this->getValueFromRawData($providerRawData, 'list_purpose'));
            }
        }

        if ($userID = $this->getValueFromRawData($providerRawData, 'username')) {
            $ndo->setUser(new NDO\BookList\BookListUser($userID));
        }

        if ($bookListItems = $this->getValueFromRawData($providerRawData, 'items')) {
            $bookListItemsGroup = new NDO\BookList\BookListItemGroup();

            array_multisort(array_column($bookListItems, 'index'), SORT_DESC, $bookListItems);

            foreach ($bookListItems as $bookListItemID) {
                $bookListItem = new NDO\BookList\BookListItem($ndo->getNdoID() . '-' . $bookListItemID['library_bib_id']);
                $bookListItemsGroup->append($bookListItem);
            }

            $ndo->setListItems($bookListItemsGroup);
        }

        return $ndo;
    }
}
