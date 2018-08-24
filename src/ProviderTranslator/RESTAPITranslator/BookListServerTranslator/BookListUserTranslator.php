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
class BookListUserTranslator extends BookListServerTranslator implements
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
        $rawData = $provider->clientGet('users/' . $ndoFilter->getFilterID() . '.json', null, null, $allowEmptyResults);

        return $rawData;
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\SiteDatum\MegaMenuPaneGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\BookList\BookListUser($this->getValueFromRawData($providerRawData, 'username'));

        $ndo->setRead(true);

        $ndo->setUsername($this->getValueFromRawData($providerRawData, 'username'));
        $ndo->setName($this->getValueFromRawData($providerRawData, 'name'));
        $ndo->setUserID($this->getValueFromRawData($providerRawData, 'id'));

        if ($lists = $this->getValueFromRawData($providerRawData, 'lists')) {
            $bookListNDO = new NDO\BookListGroup();

            array_multisort(array_column($lists, 'created_date'), SORT_DESC, $lists);

            foreach ($lists as $list) {
                $bookListNDO->append(new NDO\BookList($list['list_id']));
            }

            $ndo->setBookLists($bookListNDO);
        }

        return $ndo;
    }
}
