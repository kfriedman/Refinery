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
class BookItemTranslator extends BookListServerTranslator implements
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
        $item = $provider->clientGet('items/' . $ndoFilter->getFilterID() . '.json', null, null, $allowEmptyResults);

        if (is_array($item)) {
            $item = $item['title'];
        } else {
            $item = json_decode($item, true);
            $item = $item['title'];
        }

        $item['library_bib_id'] = $ndoFilter->getFilterID();

        return $item;
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\SiteDatum\MegaMenuPaneGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\BookList\BookItem($this->getValueFromRawData($providerRawData, 'library_bib_id'));

        $ndo->setRead(true);

        $ndo->setTitle($this->getValueFromRawData($providerRawData, 'title'));

        $ndo->setSubTitle($this->getValueFromRawData($providerRawData, 'sub_title'));

        $ndo->setNote($this->getValueFromRawData($providerRawData, 'user_annotation'));

        $ndo->setPublicationDate($this->getValueFromRawData($providerRawData, 'publication_date'));

        if ($format = $this->getValueFromRawData($providerRawData, 'format')) {
            $ndo->setFormat($format['name']);
        }

        if ($authors = $this->getValueFromRawData($providerRawData, 'authors')) {
            $ndo->setAuthors(array_column($authors, 'name'));
        }

        if ($upcs = $this->getValueFromRawData($providerRawData, 'upcs')) {
            $ndo->setUpcs($upcs);
        }

        if ($isbns = $this->getValueFromRawData($providerRawData, 'isbns')) {
            $ndo->setIsbns($isbns);
        }

        return $ndo;
    }
}
