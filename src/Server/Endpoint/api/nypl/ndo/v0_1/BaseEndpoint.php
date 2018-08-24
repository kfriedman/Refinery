<?php
namespace NYPL\Refinery\Server\Endpoint\api\nypl\ndo\v0_1;

use NYPL\Refinery\DIManager;
use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\JSONAPI\JSONAPI;
use NYPL\Refinery\JSONAPI\JSONAPIBuilder;
use NYPL\Refinery\JSONAPI\JSONAPIOutputter;
use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\NDOGroup;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Server\Endpoint;

/**
 * API Endpoint
 *
 * @package NYPL\Refinery\Server\Endpoint
 * @SuppressWarnings(PHPMD.LongVariable)
 */
abstract class BaseEndpoint extends Endpoint implements
  Endpoint\GetInterface
{
    /**
     * @param NDOFilter $filter
     * @param string    $baseNDONameWithNamespace
     *
     * @return Provider
     */
    abstract public function getProvider(NDOFilter $filter, $baseNDONameWithNamespace = '');

    /**
     * @param NDOFilter $filter
     *
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    public function get(NDOFilter $filter)
    {
        $childFilter = new NDOFilter();
        $childLinkURL = null;

        $urlArray = $filter->getUrlArray();

        if (!isset($urlArray[0])) {
            throw new RefineryException('NDO was not specified', 400);
        }

        if (isset($urlArray[1])) {
            if ($urlArray[count($urlArray) - 2] == 'links' || $urlArray[count($urlArray) - 2] == 'relationships') {
                $childFilter->setPerPage($filter->getPerPage());
                $filter->setPerPage(null);
                $childFilter->setPage($filter->getPage());
                $filter->getPage(null);

                $childLinkURL = implode('/', $urlArray);

                $baseNDOArray = array();

                foreach (array_slice($urlArray, 0, count($urlArray) - 3) as $url) {
                    $baseNDOArray[] = JSONAPIBuilder::nameToProperty($url, true);
                }

                $baseNDOName = implode('\\', $baseNDOArray);
                $baseNDONameWithNamespace = 'NYPL\\Refinery\\NDO\\' . $baseNDOName;

                $filter->setFilterID($urlArray[count($urlArray) - 3]);
            } else {
                $baseNDOName = JSONAPIBuilder::nameToProperty($urlArray[count($urlArray) - 1], true);

                $baseNDONameWithNamespace = 'NYPL\\Refinery\\NDO\\' . $baseNDOName . 'Group';

                if (!class_exists($baseNDONameWithNamespace)) {
                    $baseNDOArray = array();

                    foreach ($urlArray as $url) {
                        $baseNDOArray[] = JSONAPIBuilder::nameToProperty($url, true);
                    }

                    $baseNDONameWithNamespace = 'NYPL\\Refinery\\NDO\\' . JSONAPIBuilder::nameToProperty(implode('\\', $baseNDOArray) . 'Group');
                }

                if (!class_exists($baseNDONameWithNamespace)) {
                    $baseNDOArray = array();

                    foreach (array_slice($urlArray, 0, count($urlArray) - 1) as $url) {
                        $baseNDOArray[] = JSONAPIBuilder::nameToProperty($url, true);
                    }

                    $baseNDOName = implode('\\', $baseNDOArray);
                    $baseNDONameWithNamespace = 'NYPL\\Refinery\\NDO\\' . $baseNDOName;

                    $filter->setFilterID($urlArray[count($urlArray) - 1]);
                }
            }

        } else {
            $baseNDOName = JSONAPIBuilder::nameToProperty($urlArray[0], true);
            $baseNDONameWithNamespace = 'NYPL\\Refinery\\NDO\\' . $baseNDOName . 'Group';
        }

        $provider = $this->getProvider($filter, $baseNDONameWithNamespace);

        if (!$provider) {
            throw new RefineryException('Provider was not specified');
        }

        if (!class_exists($baseNDONameWithNamespace)) {
            throw new RefineryException('Resource requested (' . $baseNDONameWithNamespace . ') does not exist', 404);
        }

        if (isset($urlArray[3]) && ($urlArray[count($urlArray) - 2] == 'links' || $urlArray[count($urlArray) - 2] == 'relationships')) {
            $baseNDO = DIManager::getNDOReader()->readNDO($provider, new $baseNDONameWithNamespace(), $filter, $this->getRawData(), false, true, true, true);

            $linkNDOName = JSONAPIBuilder::nameToProperty($urlArray[count($urlArray) - 1], true, true);
            $getterLinkNDO = 'get' . $linkNDOName;

            if (!method_exists($baseNDO, $getterLinkNDO)) {
                $ndo = $baseNDO;
            } else {
                if (!$baseNDO->$getterLinkNDO()) {
                    throw new RefineryException('Link requested (' . $urlArray[count($urlArray) - 1] . ') is not a valid resource', 404);
                }

                if ($baseNDO->$getterLinkNDO() instanceof NDOGroup) {
                    $childFilter->setFilterID($baseNDO->$getterLinkNDO()->getNdoIDArray());
                } else {
                    $childFilter->setFilterID($baseNDO->$getterLinkNDO()->getNdoID());
                }
                $childFilter->setIncludeArray($filter->getIncludeArray());

                $ndo = DIManager::getNDOReader()->readNDO($provider, $baseNDO->$getterLinkNDO(), $childFilter, $this->getRawData(), false, true, false, true);
            }
        } else {
            $reflectionClass = new \ReflectionClass($baseNDONameWithNamespace);

            if (!$reflectionClass->isInstantiable()) {
                throw new RefineryException('Resource requested (' . $baseNDONameWithNamespace . ') is not a valid resource', 404);
            }

            $ndo = DIManager::getNDOReader()->readNDO($provider, new $baseNDONameWithNamespace(), $filter, $this->getRawData(), false, true, false, true);
        }

        if ($provider->getProviderRawData()) {
            if ($this->isDebug()) {
                $this->getResponse()->setDebugArray($provider->getProviderRawData()->getRawDataArray());
            }

            $this->getResponse()->setCount($provider->getCount());
            $this->getResponse()->setPage($provider->getPage());
            $this->getResponse()->setPerPage($provider->getPerPage());
        } else {
            if ($ndo instanceof NDOGroup) {
                $this->getResponse()->setCount($ndo->getCount());
                $this->getResponse()->setPage($ndo->getPage());
                $this->getResponse()->setPerPage($ndo->getPerPage());
            }
        }

        $this->getResponse()->setStatusCode($provider->getStatusCode());
        $this->getResponse()->addMetaNotice($provider->getMetaNotices());

        $this->getResponse()->addOtherTopLevel(array('jsonapi' => array('version' => '1.0')));

        if ($ndo instanceof NDOGroup) {
            $jsonAPIGroup = JSONAPIBuilder::transformNDOGroupToJSONAPI($this, $ndo, $childLinkURL);

            $this->getResponse()->setData(JSONAPIOutputter::outputJSONAPIGroup($jsonAPIGroup, $filter->getFieldsArray()));

            $jsonAPIIncludeOutput = new JSONAPI();

            foreach ($jsonAPIGroup->getJSONAPI() as $jsonAPI) {
                if ($jsonAPI->getIncluded()) {
                    foreach ($jsonAPI->getIncluded()->getJSONAPI() as $included) {
                        $jsonAPIIncludeOutput->addInclude($included);
                    }
                }
            }

            if ($jsonAPIIncludeOutput->getIncluded()) {
                $this->getResponse()->setIncluded(JSONAPIOutputter::outputJSONAPIGroup($jsonAPIIncludeOutput->getIncluded(), $filter->getFieldsArray()));
            }

            if ($jsonAPIGroup->getLinks()) {
                $this->getResponse()->addOtherTopLevel(JSONAPIOutputter::outputJSONAPILinks($jsonAPIGroup->getLinks(), $this->getResponse()));
            }
        } else {
            $jsonAPI = JSONAPIBuilder::transformNDOtoJSONAPI($this, $ndo, null, $childLinkURL);

            $this->getResponse()->setData(JSONAPIOutputter::outputJSONAPI($jsonAPI, $filter->getFieldsArray()));

            if ($jsonAPI->getIncluded()) {
                $this->getResponse()->setIncluded(JSONAPIOutputter::outputJSONAPIGroup($jsonAPI->getIncluded(), $filter->getFieldsArray()));
            }

            if ($jsonAPI->getLinks()) {
                $this->getResponse()->addOtherTopLevel(JSONAPIOutputter::outputJSONAPILinks($jsonAPI->getLinks(), $this->getResponse()));
            }
        }
    }
}
