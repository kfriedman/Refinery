<?php
namespace NYPL\Refinery\JSONAPI;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Server\Endpoint;

/**
 * Class used to output JSON API.
 *
 * @package NYPL\Refinery
 */
class JSONAPIOutputter
{
    /**
     * Output a JSON API group into an ordered array of JSON API resources.
     *
     * @param JSONAPIGroup $jsonAPIGroup
     * @param array        $fields
     * @param bool         $includeRelationships
     *
     * @return array
     */
    public static function outputJSONAPIGroup(JSONAPIGroup $jsonAPIGroup, array $fields = array(), $includeRelationships = false)
    {
        $json = array();

        foreach ($jsonAPIGroup->getJSONAPI() as $jsonAPI) {
            $json[] = self::outputJSONAPI($jsonAPI, $fields, $includeRelationships, true);
        }

        return $json;
    }

    /**
     * Output a JSON API object into an formatted JSON API resources.
     *
     * @param JSONAPI $jsonAPI
     * @param array   $fields
     * @param bool    $includeRelationships
     * @param bool    $includeLinks
     *
     * @return array
     */
    public static function outputJSONAPI(JSONAPI $jsonAPI, array $fields = array(), $includeRelationships = false, $includeLinks = false)
    {
        $json = array();

        $json['type'] = $jsonAPI->getType();
        $json['id'] = $jsonAPI->getId();

        if (isset($fields[$jsonAPI->getType()])) {
            $showFields = explode(',', $fields[$jsonAPI->getType()]);
        } else {
            $showFields = array();
        }

        foreach ($jsonAPI->getAttributes() as $name => $value) {
            $showAttribute = true;

            if (isset($fields[$jsonAPI->getType()])) {
                if (!in_array($name, $showFields)) {
                    $showAttribute = false;
                }
            }

            if ($showAttribute) {
                $json['attributes'][$name] = $value;
            }
        }

        if ($jsonAPI->getRelationships()) {
            foreach ($jsonAPI->getRelationships() as $jsonAPILinkGroup) {
                $showRelationship = true;

                if (isset($fields[$jsonAPI->getType()])) {
                    if (!in_array($jsonAPILinkGroup->getGroupName(), $showFields)) {
                        $showRelationship = false;
                    }
                }

                if ($showRelationship || $includeRelationships) {
                    $link = array();

                    if ($jsonAPILinkGroup->isMulti()) {
                        foreach ($jsonAPILinkGroup->getJSONAPIRelationships() as $linkage) {
                            $link['data'][] = array(
                                'type' => $linkage->getType(),
                                'id' => $linkage->getId()
                            );
                        }
                    } else {
                        $linkage = current($jsonAPILinkGroup->getJSONAPIRelationships());

                        $link['data'] = array(
                            'type' => $linkage->getType(),
                            'id' => $linkage->getId()
                        );
                    }

                    $link += JSONAPIOutputter::outputJSONAPILinks($jsonAPILinkGroup->getLinks());

                    $json['relationships'][$jsonAPILinkGroup->getGroupName()] = $link;
                }
            }
        }

        if ($jsonAPI->getLinks() && $includeLinks) {
            $json += self::outputJSONAPILinks($jsonAPI->getLinks());
        }

        return $json;
    }

    /**
     * @param JSONAPILinks      $jsonAPILinks
     * @param Endpoint\Response $response
     *
     * @return array
     */
    public static function outputJSONAPILinks(JSONAPILinks $jsonAPILinks, Endpoint\Response $response = null)
    {
        $links = array();

        $self = $jsonAPILinks->getSelf();

        if ($response) {
            self::addParameterToURL($self, 'page[size]', $response->getPerPage());
            if ($response->getPage() > 1) {
                self::addParameterToURL($self, 'page[number]', $response->getPage());
            }
        }

        if ($jsonAPILinks->getMeta()) {
            $links['self']['href'] = $self;
            $links['self']['meta'] = $jsonAPILinks->getMeta();
        } else {
            $links['self'] = $self;
        }

        if ($response) {
            if ($response->getTotalPages() > 1) {
                $links['first'] = $jsonAPILinks->getSelf();
                self::addParameterToURL($links['first'], 'page[size]', $response->getPerPage());

                $links['last'] = $jsonAPILinks->getSelf();
                self::addParameterToURL($links['last'], 'page[size]', $response->getPerPage());
                self::addParameterToURL($links['last'], 'page[number]', $response->getTotalPages());

                if ($response->getPage() > 1) {
                    if ($response->getPage() == 2) {
                        $links['prev'] = $jsonAPILinks->getSelf();
                        self::addParameterToURL($links['prev'], 'page[size]', $response->getPerPage());
                    } else {
                        $links['prev'] = $jsonAPILinks->getSelf();
                        self::addParameterToURL($links['prev'], 'page[size]', $response->getPerPage());
                        self::addParameterToURL($links['prev'], 'page[number]', $response->getPage() - 1);
                    }
                }

                if ($response->getPage() != $response->getTotalPages()) {
                    $links['next'] = $jsonAPILinks->getSelf();
                    self::addParameterToURL($links['next'], 'page[size]', $response->getPerPage());
                    self::addParameterToURL($links['next'], 'page[number]', $response->getPage() + 1);
                }
            }
        }

        return array('links' => $links);
    }

    /**
     * @param string $url
     * @param string $parameterName
     * @param null   $parameterValue
     */
    public static function addParameterToURL(&$url = '', $parameterName = '', $parameterValue = null)
    {
        if ($url && $parameterValue) {
            if (strpos($url, '?') === false) {
                $url .= '?';
            } else {
                $url .= '&';
            }

            $url .= http_build_query(array($parameterName => $parameterValue));
        }
    }
}
