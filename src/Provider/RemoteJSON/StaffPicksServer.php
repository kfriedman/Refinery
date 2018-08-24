<?php
namespace NYPL\Refinery\Provider\RemoteJSON;

use GuzzleHttp\Client;
use NYPL\Refinery\Helpers\TextHelper;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider\RemoteJSON;

/**
 * Class to create a D7RefineryServerCurrent Provider
 *
 * @package NYPL\Refinery\NDO
 */
class StaffPicksServer extends RemoteJSON
{
    /**
     * @param array     $manifestFiles
     * @param NDOFilter $ndoFilter
     *
     * @return array
     */
    protected function processManifestFiles(array $manifestFiles, NDOFilter $ndoFilter = null)
    {
        $rawData = array();

        foreach ($manifestFiles as $fileArray) {
            $fullURL = $this->buildFullURL($fileArray['json-file']);

            $client = new Client();

            $jsonArray = json_decode((string) $client->get($fullURL)->getBody(), true);

            $listType = $fileArray['type'];
            $listID = TextHelper::slugify($listType . ' ' . $fileArray['date']);

            if ($ndoFilter->getQueryParameter('filter')->getValue()) {
                $queryFilter = $ndoFilter->getQueryParameter('filter')->getValue();

                $includeList = false;

                if (isset($queryFilter['id'])) {
                    if ($queryFilter['id'] == $listID) {
                        $includeList = true;
                    }
                }
                if (isset($queryFilter['list.id'])) {
                    if ($queryFilter['list.id'] == $listID) {
                        $includeList = true;
                    }
                }
                if (isset($queryFilter['list-type'])) {
                    if ($queryFilter['list-type'] == $listType) {
                        $includeList = true;
                    }
                }
            } else {
                $includeList = true;
            }

            if ($includeList) {
                if (!$rawData) {
                    $rawData['lists'] = array();
                    $rawData['author'] = array();
                    $rawData['tags'] = array();
                    $rawData['items'] = array();
                    $rawData['picks'] = array();
                    $rawData['ages'] = array();
                }

                $rawData['lists'][$listID] = array(
                    'id' => $listID,
                    'type' => $listType,
                    'date' => $fileArray['date']
                );

                foreach ($jsonArray['picks'] as $pick) {
                    $includePick = true;

                    if ($ndoFilter->getQueryParameter('filter')->getValue()) {
                        if (isset($queryFilter['relationships'])) {
                            $includePick = false;

                            $relationshipKey = key($queryFilter['relationships']);
                            $relationshipValue = current($queryFilter['relationships']);

                            switch ($relationshipKey) {
                                case 'list':
                                    if ($relationshipValue == $listID) {
                                        $includePick = true;
                                    }
                                    break;
                            }
                        }
                    }

                    if ($includePick) {
                        $authorID = TextHelper::slugify($pick['author']);
                        $itemID = $pick['slug'];
                        $pickID = md5($listID . $itemID);

                        if (isset($pick['age'])) {
                            $ageID = TextHelper::slugify($pick['age']);
                        } else {
                            unset($ageID);
                        }

                        $rawData['picks'][$pickID] = array(
                            'id' => $pickID,
                            'text' => ((isset($pick['text'])) ? $pick['text'] : ''),
                            'name' => ((isset($pick['name'])) ? $pick['name'] : ''),
                            'location' => ((isset($pick['location'])) ? $pick['location'] : ''),
                            'sort' => ((isset($pick['sort'])) ? $pick['sort'] : ''),
                            'feature' =>((isset($pick['feature'])) ? $pick['feature'] : '')
                        );

                        $rawData['picks'][$pickID]['_relationships']['list'] = $listID;
                        $rawData['picks'][$pickID]['_relationships']['author'] = $authorID;
                        $rawData['picks'][$pickID]['_relationships']['item'] = $itemID;

                        if (isset($ageID)) {
                            $rawData['picks'][$pickID]['_relationships']['age'] = $ageID;
                        }

                        $rawData['lists'][$listID]['_relationships']['picks'][] = $pickID;

                        if ($pick['feature']) {
                            $rawData['lists'][$listID]['_relationships']['features'][] = $pickID;
                        }

                        $pick['_relationships']['lists'][] = $listID;

                        if (!isset($rawData['author'][$authorID])) {
                            $rawData['author'][$authorID] = array(
                                'id' => $authorID,
                                'name' => $pick['author']
                            );
                        }

                        $rawData['author'][$authorID]['_relationships']['items'][] = $itemID;
                        $rawData['author'][$authorID]['_relationships']['picks'][] = $pickID;

                        if (isset($ageID)) {
                            if (!isset($rawData['ages'][$ageID])) {
                                $rawData['ages'][$ageID] = array(
                                    'id' => $ageID,
                                    'age' => $pick['age']
                                );
                            }

                            $rawData['ages'][$ageID]['_relationships']['picks'][] = $pickID;
                        }

                        if (!isset($rawData['items'][$itemID])) {
                            $rawData['items'][$itemID] = array(
                                'id' => $itemID,
                                'title' => ((isset($pick['title'])) ? $pick['title'] : ''),
                                'author' => ((isset($pick['author'])) ? $pick['author'] : ''),
                                'catalog_uri' => ((isset($pick['catalog_uri'])) ? $pick['catalog_uri'] : ''),
                                'image_uri' => ((isset($pick['image_uri'])) ? $pick['image_uri'] : ''),
                                'ebook_uri' => ((isset($pick['ebook_uri'])) ? $pick['ebook_uri'] : ''),
                                'tags' => ((isset($pick['tags'])) ? $pick['tags'] : '')
                            );
                        }

                        $rawData['items'][$itemID]['_relationships']['picks'][] = $pickID;

                        if (isset($ageID)) {
                            $rawData['items'][$itemID]['_relationships']['age'] = $ageID;
                        }

                        if (isset($pick['tags'])) {
                            foreach ($pick['tags'] as $tag) {
                                if (isset($tag['id'])) {
                                    $tagID = $tag['id'];

                                    if ($tagID) {
                                        if (!isset($rawData['tags'][$tagID])) {
                                            $rawData['tags'][$tagID] = $tag;
                                        }

                                        $rawData['tags'][$tagID]['_relationships']['picks'][] = $pickID;

                                        $rawData['items'][$itemID]['_relationships']['tags'][] = $tagID;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if (isset($rawData['lists'])) {
            $listDates = array_column($rawData['lists'], 'date');
            array_multisort($listDates, SORT_DESC, $rawData['lists']);

            $lists = array();

            foreach ($rawData['lists'] as $listID => $list) {
                $lists[$list['type']][] = $listID;
            }

            $count = [];

            foreach ($rawData['lists'] as &$list) {
                $listType = $list['type'];

                if (!isset($count[$listType])) {
                    $count[$listType] = 0;
                }

                $listCount = $count[$listType];

                if (isset($lists[$listType][$listCount - 1])) {
                    $list['_next'] = $lists[$listType][$listCount - 1];
                }
                if (isset($lists[$listType][$listCount + 1])) {
                    $list['_previous'] = $lists[$listType][$listCount + 1];
                }

                ++$count[$listType];
            }
        }

        return $rawData;
    }
}
