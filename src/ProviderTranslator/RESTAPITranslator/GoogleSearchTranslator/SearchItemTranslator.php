<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\GoogleSearchTranslator;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\GoogleSearchTranslator;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class SearchItemTranslator extends GoogleSearchTranslator implements RESTAPITranslator\ReadInterface
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
        return null;
    }

    /**
     * @param string                $bibId
     * @param NDO\Search\SearchItem $ndo
     */
    protected function setBookUrl($bibId = '', NDO\Search\SearchItem $ndo)
    {
        $url = 'https://browse.nypl.org/iii/encore/record/C__Rb' . $bibId;

        $ndo->setLink($url);
        $ndo->setDisplayLink($url);

        $ndo->setFormattedUrl($url);
        $ndo->setHtmlFormattedUrl($url);
    }

    /**
     * @param array     $book
     * @param NDO\Search\SearchItem $ndo
     * @param array     $rawData
     */
    protected function updateBook(array $book, NDO\Search\SearchItem $ndo, array $rawData)
    {
        $attribute = $book[0];

        if (isset($attribute['isbn'])) {
            $thumbnailUrl = 'https://images.btol.com/ContentCafe/Jacket.aspx?&userID=NYPL49807&password=CC68707&Value=' .
                $attribute['isbn'] . '&content=M&Return=1&Type=M';

            $ndo->setThumbnailUrl($thumbnailUrl);
        }

        $url = (string) $rawData['link'];

        $bibIdArray = explode('/', current(explode('-', $url)));
        $bibId = array_pop($bibIdArray);

        $this->setBookUrl($bibId, $ndo);
    }

    /**
     * @param array     $metaTags
     * @param NDO\Search\SearchItem $ndo
     */
    protected function updateMetaTags(array $metaTags, NDO\Search\SearchItem $ndo)
    {
        $attribute = $metaTags[0];

        if (isset($attribute['og:title'])) {
            $ndo->setOpenGraphTitle($attribute['og:title']);
        }

        if (isset($attribute['og:description'])) {
            $ndo->setSnippet($attribute['og:description']);

            $ndo->setOpenGraphDescription($attribute['og:description']);
        }

        if (isset($attribute['og:image'])) {
            $ndo->setOpenGraphImageUrl($attribute['og:image']);

            $ndo->setThumbnailUrl($attribute['og:image']);
        }

//            if ($attribute->attributes()['name'] == 'nypl:bibid') {
//                $this->setBookUrl($value, $ndo);
//            }
    }

    /**
     * @param array     $rawData
     * @param NDO\Search\SearchItem $ndo
     */
    protected function updateItem(array $rawData, NDO\Search\SearchItem $ndo)
    {
        if (isset($rawData['pagemap'])) {
            foreach ($rawData['pagemap'] as $type => $pageMap) {
                if ($type == 'book') {
                    $this->updateBook($pageMap, $ndo, $rawData);
                }

                if ($type == 'metatags') {
                    $this->updateMetaTags($pageMap, $ndo);
                }

                if ($type == 'cse_thumbnail') {
                    $ndo->setThumbnailUrl($pageMap[0]['src']);
                }

                if ($type == 'cse_image' && !$ndo->getThumbnailUrl()) {
                    $ndo->setThumbnailUrl($pageMap[0]['src']);
                }
            }
        }
    }

    /**
     * @param string $link
     *
     * @return string
     */
    protected function getBibIdFromUrl($link = '')
    {
        $bibId = explode('/', $link);
        $bibId = array_pop($bibId);
        $bibId = explode('-', $bibId);
        $bibId = current($bibId);

        return $bibId;
    }

    /**
     * @param string                $bibId
     * @param NDO\Search\SearchItem $ndo
     */
    protected function setBookCoverWithoutMeta($bibId = '', NDO\Search\SearchItem $ndo)
    {
        try {
            $bibData = json_decode(file_get_contents('http://10.229.7.115:8983/solr/bibItems/select?q=_id:' . $bibId . '&wt=json&indent=true'), true);

            if (isset($bibData['response']['docs'][0]['varField_020_x_a_ss'])) {
                $isbn = current($bibData['response']['docs'][0]['varField_020_x_a_ss']);

                $thumbnailUrl = 'https://images.btol.com/ContentCafe/Jacket.aspx?&userID=NYPL49807&password=CC68707&Value=' .
                    $isbn . '&content=M&Return=1&Type=M';

                $ndo->setThumbnailUrl($thumbnailUrl);
            }
        } catch (\Exception $exception) {
        }
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\Search\SearchItem
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $facets = $providerRawData->getRawDataArray()['facets'];

        $result = $providerRawData->getRawDataArray()['result'];

        $ndo = new NDO\Search\SearchItem(md5($result['link']));

        $ndo->setRead(true);

        $ndo->setTitle($result['title']);
        $ndo->setHtmlTitle($result['htmlTitle']);

        $link = $result['link'];

        $ndo->setLink($link);
        $ndo->setDisplayLink($link);
        $ndo->setFormattedUrl($link);
        $ndo->setHtmlFormattedUrl($link);

        $nyplData = array();

        if (strpos($link, 'betacatalogindex') !== false) {
            $bidId = $this->getBibIdFromUrl($link);

            $nyplData['bib']['id'] = $bidId;

            if (!isset($result['pagemap'])) {
                $this->setBookUrl($bidId, $ndo);
                $this->setBookCoverWithoutMeta($bidId, $ndo);
            }
        }

        $ndo->setSnippet($result['snippet']);
        $ndo->setHtmlSnippet($result['htmlSnippet']);

//        if ($result['pagemap']) {
//            $translatedPageMapArray = array();
//
//            /**
//             * @var \SimpleXMLElement $itemLabel
//             */
//            foreach ($result->PageMap as $pageMapArray) {
//                foreach ($pageMapArray as $pageMap) {
//                    $type = (string) $pageMap->attributes()['type'];
//
//                    if ($type != 'metatags') {
//                        $translatedPageMap = array();
//                        $translatedPageMapValues = array();
//
//                        foreach ($pageMap->Attribute as $attribute) {
//                            $translatedPageMapValues[(string) $attribute['name']] = (string) $attribute['value'];
//                        }
//
//                        $translatedPageMap[(string) $pageMap->attributes()['type']] = $translatedPageMapValues;
//
//                        $translatedPageMapArray[] = $translatedPageMap;
//                    }
//                }
//            }
//
//            $ndo->setPageMap($translatedPageMapArray);
//        }

        if (isset($result['labels'])) {
            $labels = array();

            foreach ($result['labels'] as $itemLabel) {
                $displayName = ucwords(str_replace('_', ' ', $itemLabel['name']));

                $label = array(
                    'name' => $itemLabel['name'],
                    'displayName' => $displayName,
                    'label_with_op' => $itemLabel['label_with_op']
                );

                $labels[] = $label;
            }

            $ndo->setLabels($labels);
        }

        $ndo->setNyplData($nyplData);

        $this->updateItem($result, $ndo);

        return $ndo;
    }
}
