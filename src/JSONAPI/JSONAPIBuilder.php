<?php
namespace NYPL\Refinery\JSONAPI;

use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\Helpers\TextHelper;
use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOGroup;
use NYPL\Refinery\Server\Endpoint;
use NYPL\Refinery\StaticCache\StaticJSONAPI;
use NYPL\Refinery\StaticCache\StaticJSONAPIName;
use NYPL\Refinery\StaticCache\StaticJSONAPIRelationship;

/**
 * Class used to build JSON API from an object.
 *
 * @package NYPL\Refinery
 */
class JSONAPIBuilder
{
    /**
     * An array of reserved keys as specified by the JSON API specification.
     *
     * @var array
     */
    protected static $reservedKeys = array('links', 'relationships');

    /**
     * An array of keys to not pluralize
     *
     * @var array
     */
    public static $pluralizeExceptions = array('Content', 'Admin', 'Staff');

    /**
     * Transform a NDOGroup into JSON API.
     *
     * @param Endpoint $endpoint
     * @param NDOGroup $ndoGroup
     * @param string   $baseURL
     *
     * @return JSONAPIGroup
     */
    public static function transformNDOGroupToJSONAPI(Endpoint $endpoint, NDOGroup $ndoGroup, $baseURL = '')
    {
        $jsonAPIGroup = new JSONAPIGroup();

        /**
         * @var NDO $ndo
         */
        foreach ($ndoGroup->items as $ndo) {
            $jsonAPIGroup->addJSONAPI(self::transformNDOtoJSONAPI($endpoint, $ndo));
        }

        if (isset($ndo)) {
            if ($baseURL) {
                $jsonAPIGroup->getLinks()->setSelf($endpoint->getFullURL() . '/' . $baseURL);
            } else {
                $jsonAPIGroup->getLinks()->setSelf(self::getNDORelationshipURL($endpoint, $ndo));
            }
        }

        return $jsonAPIGroup;
    }

    /**
     * Transform a NDO into JSON API.
     *
     * @param Endpoint $endpoint
     * @param NDO      $ndo
     * @param JSONAPI  $parentJSONAPI
     *
     * @return JSONAPI
     * @throws RefineryException
     */
    public static function transformNDOtoJSONAPI(Endpoint $endpoint, NDO $ndo, JSONAPI $parentJSONAPI = null)
    {
        $staticCacheName = $ndo->getNdoType() . ':' . $ndo->getNdoID() . ':' . $ndo->isRead() . ':' . $ndo->isInclude();

        if ($jsonAPI = StaticJSONAPI::read($staticCacheName)) {
            return $jsonAPI;
        } else {
            $jsonAPI = new JSONAPI();

            $jsonAPI->setId(self::getNdoID($ndo));
            $jsonAPI->setType(self::getNDOType($ndo));

            $jsonAPI->getLinks()->setSelf(self::getNDORelationshipURL($endpoint, $ndo, $jsonAPI->getId()));

            foreach (self::transformNDOToArray($ndo) as $name => $value) {
                if (self::isRelationship($value)) {
                    if ($value instanceof NDOGroup) {
                        self::setJSONAPIForNDOGroup($endpoint, $jsonAPI, $value, $name, $parentJSONAPI);
                    } elseif ($value instanceof NDO) {
                        $staticRelationName = $value->getNdoType() . ':' . $value->getNdoID();

                        if (!StaticJSONAPIRelationship::read($staticRelationName)) {
                            StaticJSONAPIRelationship::save($staticRelationName, true);

                            self::addNDORelationship($endpoint, $name, $value, $jsonAPI, $parentJSONAPI);

                            StaticJSONAPIRelationship::clear();
                        }
                    }
                } else {
                    if ($value instanceof NDO) {
                        if ($value instanceof NDO\LocalDateTime) {
                            $jsonAPI->addAttribute($name, $value->getDate());
                        } else {
                            $jsonAPI->addAttribute($name, self::transformNDOToArray($value, true));
                        }
                    } else {
                        $jsonAPI->addAttribute($name, $value);
                    }
                }
            }

            StaticJSONAPI::save($staticCacheName, $jsonAPI);

            return $jsonAPI;
        }
    }

    /**
     * Setter for transforming an NDOGroup into JSON API.
     *
     * @param Endpoint $endpoint
     * @param JSONAPI  $parentJSONAPI
     * @param JSONAPI  $jsonAPI
     * @param NDOGroup $value
     * @param string   $name
     *
     * @throws RefineryException
     */
    protected static function setJSONAPIForNDOGroup(Endpoint $endpoint, JSONAPI $jsonAPI, NDOGroup $value, $name = '', JSONAPI $parentJSONAPI = null)
    {
        if ($value->items->count()) {
            /**
             * @var NDO $childNDO
             */
            foreach ($value->items as $childNDO) {
                if ($childNDO->getNdoID()) {
                    self::addNDORelationship($endpoint, $name, $childNDO, $jsonAPI, $parentJSONAPI, true);
                } else {
                    if ($value->getItemIndex()) {
                        $itemIndexGetter = 'get' . $value->getItemIndex();

                        if (!method_exists($childNDO, $itemIndexGetter)) {
                            throw new RefineryException('Item index getter (' . $itemIndexGetter . ') does not exist on NDO (' . get_class($childNDO) . ')');
                        } else {
                            $jsonAPI->addAttribute($name, self::transformNDOToArray($childNDO, true), true, $childNDO->$itemIndexGetter(), self::getNDOType($value));
                        }
                    } else {
                        $jsonAPI->addAttribute($name, self::transformNDOToArray($childNDO, true), true);
                    }
                }
            }
        } else {
            $jsonAPI->addAttribute($name, null);
        }
    }

    /**
     * Transform a NDO into an array. This is an intermediary step for
     * transforming an NDO into JSON API.
     *
     * @param NDO  $ndo
     * @param bool $addType
     *
     * @return array
     * @throws RefineryException
     */
    protected static function transformNDOToArray(NDO $ndo, $addType = false)
    {
        $arrayFromNDO = array();

        if ($addType) {
            $arrayFromNDO['type'] = self::getNDOType($ndo);
        }

        if ($ndo->getParent()) {
            $arrayFromNDO['parent'] = $ndo->getParent();
        }

        if ($ndo->getChildren()) {
            $arrayFromNDO['children'] = $ndo->getChildren();
        }

        foreach (get_object_vars($ndo) as $property => $value) {
            $name = self::propertyToName($property);

            if ($value instanceof NDO\LocalDateTime) {
                $value = $value->getDate();
            }

            $arrayFromNDO[$name] = $value;
        }

        return $arrayFromNDO;
    }

    /**
     * Convert an object's property name into JSON API format.
     *
     * @param string $property
     *
     * @return string
     */
    public static function propertyToName($property = '')
    {
        if ($staticCache = StaticJSONAPIName::read($property)) {
            return $staticCache;
        } else {
            $name = strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $property));

            StaticJSONAPIName::save($property, $name);

            return $name;
        }
    }

    /**
     * Convert JSON API name into an object property or object name.
     *
     * @param string $name             The JSON API name name to transform.
     * @param bool   $isObject         Whether you are transforming into an object name.
     * @param bool   $isNotSingularize Whether you should not singularize name.
     *
     * @return string
     */
    public static function nameToProperty($name = '', $isObject = false, $isNotSingularize = false)
    {
        if (strstr($name, '-')) {
            $propertyName = str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));

            if (!$isNotSingularize) {
                $propertyName = TextHelper::singularize($propertyName);
            }

            if ($isObject) {
                return $propertyName;
            } else {
                return lcfirst($propertyName);
            }
        } else {
            if (!$isNotSingularize) {
                $name = TextHelper::singularize($name);
            }

            if ($isObject) {
                return ucfirst($name);
            } else {
                return $name;
            }
        }
    }


    /**
     * Whether the item should be made into a relationship.
     *
     * @param string|object $item
     *
     * @return bool
     */
    protected static function isRelationship($item)
    {
        if (is_object($item)) {
            if ($item instanceof NDO && $item->getNdoID()) {
                return true;
            }
            if ($item instanceof NDOGroup) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add a relationship for the NDO.
     *
     * @param Endpoint $endpoint
     * @param string   $name
     * @param NDO      $ndo
     * @param JSONAPI  $jsonAPI
     * @param JSONAPI  $parentJSONAPI
     * @param bool     $isMulti
     */
    protected static function addNDORelationship(Endpoint $endpoint, $name = '', NDO $ndo = null, JSONAPI $jsonAPI = null, JSONAPI $parentJSONAPI = null, $isMulti = false)
    {
        if ($ndo->getNdoID()) {
            if ($parentJSONAPI) {
                $linkedJSONAPI = self::transformNDOtoJSONAPI($endpoint, $ndo, $parentJSONAPI);
            } else {
                $linkedJSONAPI = self::transformNDOtoJSONAPI($endpoint, $ndo, $jsonAPI);
            }

            if ($ndo->isRead() && $ndo->isInclude()) {
                if ($parentJSONAPI) {
                    $parentJSONAPI->addInclude($linkedJSONAPI);
                } else {
                    $jsonAPI->addInclude($linkedJSONAPI);
                }
            }

            $jsonAPI->addRelationship($name, $linkedJSONAPI, $jsonAPI->getLinks()->getSelf() . '/relationships/' . $name, $jsonAPI->getLinks()->getSelf() . '/' . $name, $isMulti);
        }
    }

    /**
     * Get the URL for the NDO relationship.
     *
     * @param Endpoint $endpoint
     * @param NDO      $ndo
     * @param string   $urlID
     *
     * @return mixed|string
     */
    public static function getNDORelationshipURL(Endpoint $endpoint, NDO $ndo, $urlID = '')
    {
        $url = get_class($ndo);

        $urlArray = explode('\\', $url);

        $count = 0;

        foreach ($urlArray as &$urlArrayPart) {
            if ($count > 2 && !in_array($urlArrayPart, self::$pluralizeExceptions)) {
                $urlArrayPart = TextHelper::pluralize($urlArrayPart);
            }

            ++$count;
        }

        $url = implode('\\', $urlArray);

        $url = str_replace('NYPL\\Refinery\\NDO\\', $endpoint->getFullURL() . '/', $url);

        $url = str_replace('\\', '/', $url);

        $url = JSONAPIBuilder::propertyToName($url);

        if ($urlID) {
            $url .= '/' . $urlID;
        }

        return $url;
    }

    /**
     * Get the "type" of the NDO.
     *
     * @param NDO $ndo
     *
     * @return string
     */
    public static function getNDOType(NDO $ndo)
    {
        return self::propertyToName($ndo->getNdoType());
    }

    /**
     * Get the "id" of the NDO.
     *
     * @param NDO $ndo
     *
     * @return string
     */
    protected static function getNdoID(NDO $ndo)
    {
        return $ndo->getNdoID();
    }
}
