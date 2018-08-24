<?php
namespace NYPL\Refinery\ProviderHandler;

use NYPL\Refinery\Cache\CacheData;
use NYPL\Refinery\Cache\CacheData\CachedNDO;
use NYPL\Refinery\Config\Config;
use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\Helpers\ClassNameHelper;
use NYPL\Refinery\JSONAPI\JSONAPIBuilder;
use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\NDOGroup;
use NYPL\Refinery\ProviderHandler;
use NYPL\Refinery\Provider;
use NYPL\Refinery\ProviderInitializer;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\Server;

/**
 * Handler to read an NDO from a Provider based on a filter.
 *
 * @package NYPL\Refinery\ProviderHandler
 */
class NDOReader extends ProviderHandler
{
    /**
     * Read an NDO from a Provider.
     *
     * @param Provider  $provider          The Provider to read an NDO from.
     * @param NDO       $ndoToRead         The NDO that you want to read.
     * @param NDOFilter $ndoFilter         The filter used to query raw data on the Provider.
     * @param null      $rawData           Raw data to parse instead of reading from Provider.
     * @param bool      $allowEmptyResults Whether empty results is consider an error or not.
     * @param bool      $processIncludes   Whether to process includes for the NDO (used by JSON API).
     * @param bool      $isBaseNDO         Whether the current NDO being read is the base (parent) NDO.
     * @param bool      $enableCache       Whether to enable the NDO cache or not.
     *
     * @return NDO|null                    NDO built from the Provider. NULL if no raw data was found.
     * @throws RefineryException
     */
    public static function readNDO(Provider $provider, NDO $ndoToRead, NDOFilter $ndoFilter, $rawData = null, $allowEmptyResults = false, $processIncludes = false, $isBaseNDO = false, $enableCache = false)
    {
        if ((bool) Config::getItem('Cache.CachedNDO.Enabled') && $enableCache && $ndoToRead->isCacheable()) {
            $cachedNDO = self::getCache($ndoToRead, $ndoFilter);
            $cachedNDOExists = $cachedNDO->isExists();
        } else {
            $cachedNDO = false;
            $cachedNDOExists = false;
        }

        if ($cachedNDOExists && !Server::isForceRefresh() && !Server::isRefreshIfStale()) {
            $ndo = $cachedNDO->getData();
        } else {
            if ($ndoToRead->getProviderName()) {
                $providerName = $ndoToRead->getProviderName();
                $provider = new $providerName;

                ProviderInitializer::initializeProvider($provider, $ndoToRead->getEnvironmentName());
            }

            // Check to make sure the Provider supports reading this NDO.
            self::checkSupportedProvider($provider, $ndoToRead);

            // If raw data is provided, initialize the Provider with the ProviderRawData object built from this raw data.
            if ($rawData) {
                $provider->setProviderRawData(new ProviderRawData($rawData));
            } else {
                // Reset the raw data on the Provider in case it used more than once.
                $provider->setProviderRawData(null);

                // If you are reading an NDO and not an NDOGroup, make sure that you specify a filter.
                if (!$ndoToRead instanceof NDOGroup) {
                    self::checkFilterIsSpecified($ndoToRead, $ndoFilter);
                }
            }

            // Read the NDO from the Provider.
            $ndo = $provider->readNDO($ndoToRead, $ndoFilter, $allowEmptyResults);
        }

        // If an NDO was read/returned, do validation on the NDO.
        if ($ndo) {
            try {
                // Check to make sure the Provider returned the expected NDO.
                self::checkNDO($ndo, $ndoToRead);
            } catch (\Exception $exception) {
                throw new RefineryException('Provider did not return a valid NDO: ' . $exception->getMessage());
            }

            $ndo->setRead(true);

            // You must cache the NDO before adding includes or else includes will get cached
            if ($cachedNDO) {
                self::saveCache($cachedNDO, $ndo);
            }

            if ($processIncludes) {
                if (!$isBaseNDO) {
                    if ($ndo instanceof NDOGroup) {
                        /**
                         * @var NDO $itemNDO
                         */
                        foreach ($ndo->items as $itemNDO) {
                            self::processIncludes($itemNDO, $ndoFilter->getIncludeArray(), $provider);
                        }

                        $ndo->items->rewind();
                    } else {
                        self::processIncludes($ndo, $ndoFilter->getIncludeArray(), $provider);
                    }
                }
            }
        }

        return $ndo;
    }

    /**
     * @param NDO       $ndo
     * @param NDOFilter $ndoFilter
     *
     * @return CachedNDO
     */
    protected static function getCache(NDO $ndo, NDOFilter $ndoFilter)
    {
        $cacheKey = array();

        $cacheKey[] = $ndo->getNdoType();

        if ($ndo instanceof NDOGroup) {
            if ($ndoFilter->getFilterID()) {
                $cacheKey[] = $ndoFilter->getFilterID();
            }

            if ($ndoFilter->getQueryParameter('filter')->getValue()) {
                $cacheKey[] = $ndoFilter->getQueryParameter('filter')->getValue();
            }

            if ($ndoFilter->getPerPage()) {
                $cacheKey[] = $ndoFilter->getPerPage();
            }

            if ($ndoFilter->getPage() > 1) {
                $cacheKey[] = $ndoFilter->getPage();
            }
        } else {
            $cacheKey[] = $ndoFilter->getFilterID();
        }

        return new CachedNDO($cacheKey);
    }

    /**
     * Save the NDO (or NDOGroup) to the cache.
     *
     * @param CacheData $cachedNDO
     * @param NDO       $ndo
     */
    protected static function saveCache(CacheData $cachedNDO, NDO $ndo)
    {
        $cachedNDO->save($ndo);

        if ($ndo instanceof NDOGroup) {
            /**
             * @var NDO $item
             */
            foreach ($ndo->items as $item) {
                $itemCache = new CachedNDO(array($item->getNdoType(), $item->getNdoID()));
                $itemCache->save($item);
            }
        }
    }

    /**
     * Process the includes for the NDO.
     *
     * @param NDO      $ndo
     * @param array    $includeArray
     * @param Provider $provider
     *
     * @throws RefineryException
     */
    protected static function processIncludes(NDO $ndo, array $includeArray, Provider $provider)
    {
        foreach ($includeArray as $includeName) {
            if (strstr($includeName, '.')) {
                $count = 0;

                $includeNameArray = new \ArrayIterator(explode('.', $includeName));

                $currentNDO = $ndo;

                while ($includeNameArray->valid()) {
                    ++$count;

                    $includeName = $includeNameArray->current();

                    if ($currentNDO) {
                        if ($currentNDO instanceof NDOGroup) {
                            /**
                             * @var NDO $itemNDO
                             */
                            foreach ($currentNDO->items as $itemNDO) {
                                self::setInclude($itemNDO, $provider, $includeName, $includeName);
                            }

                            $currentNDO->items->rewind();
                        } else {
                            self::setInclude($currentNDO, $provider, $includeName, $includeName);
                        }
                    }

                    $includeNameArray->next();

                    if ($includeNameArray->valid()) {
                        if ($currentNDO instanceof NDOGroup) {
                            $getMethodName = 'get' . JSONAPIBuilder::nameToProperty($includeName, true, true);

                            // Since NDO is an NDO group, we need to build a new NDOGroup will all the related items
                            foreach ($currentNDO->items as $itemNDO) {
                                if (!method_exists($itemNDO, $getMethodName)) {
                                    throw new RefineryException('Relationship (' . $includeName . ') does not exist on parent item  (' . JSONAPIBuilder::propertyToName(ClassNameHelper::getNameWithoutNamespace($itemNDO)) . ')');
                                }

                                if ($itemNDO->$getMethodName()) {
                                    /**
                                     * @var NDOGroup $newNDO
                                     */
                                    if (!isset($newNDO)) {
                                        $newNDO = new NDOGroup();
                                    }

                                    if ($itemNDO->$getMethodName() instanceof NDOGroup) {
                                        foreach ($itemNDO->$getMethodName()->items as $relatedItemNDO) {
                                            $newNDO->append($relatedItemNDO);
                                        }
                                    } else {
                                        $newNDO->append($itemNDO->$getMethodName());
                                    }
                                }
                            }

                            if (isset($newNDO)) {
                                $currentNDO = $newNDO;
                            } else {
                                $currentNDO = null;
                            }

                            unset($newNDO);
                        } else {
                            if ($currentNDO) {
                                $getMethodName = 'get' . JSONAPIBuilder::nameToProperty($includeName, true, true);

                                if (!method_exists($currentNDO, $getMethodName)) {
                                    throw new RefineryException('Relationship (' . $includeName . ') does not exist on parent item  (' . JSONAPIBuilder::propertyToName(ClassNameHelper::getNameWithoutNamespace($currentNDO)) . ')');
                                }

                                $currentNDO = $currentNDO->$getMethodName();
                            }
                        }
                    }
                }
            } else {
                self::setInclude($ndo, $provider, $includeName, $includeName);
            }
        }
    }

    /**
     * Setter for the include for the NDO. Will "get" and set" the include.
     *
     * @param NDO      $ndo
     * @param Provider $provider
     * @param string   $includeName
     *
     * @throws RefineryException
     */
    protected static function setInclude(NDO $ndo, Provider $provider, $includeName = '')
    {
        $propertyName = JSONAPIBuilder::nameToProperty($includeName, false, true);

        $getMethodName = 'get' . JSONAPIBuilder::nameToProperty($includeName, true, true);
        $setMethodName = 'set' . JSONAPIBuilder::nameToProperty($includeName, true, true);

        if (method_exists($ndo, $getMethodName)) {
            if ($ndo->$getMethodName()) {
                if ($ndo->$getMethodName()->isRead()) {
                    if ($ndo->$getMethodName() instanceof NDOGroup) {
                        /**
                         * @var NDO $includedNDO
                         */
                        foreach ($ndo->$getMethodName()->items as &$includedNDO) {
                            $includedNDO->setInclude(true);
                        }
                    } else {
                        $ndo->$getMethodName()->setInclude(true);
                    }

                    $ndo->setInclude(true);
                } else {
                    try {
                        if ($ndo->$getMethodName() instanceof NDOGroup) {
                            /**
                             * @var NDO $includedNDO
                             */
                            foreach ($ndo->$getMethodName()->items as &$includedNDO) {
                                $includedNDO = self::readNDO($provider, $includedNDO, new NDOFilter($includedNDO->getNdoID()), null, false, false, false, true);
                                $includedNDO->setInclude(true);
                            }
                            $ndo->$getMethodName()->setRead(true);
                        } elseif ($ndo->$getMethodName() instanceof NDO) {
                            $ndo->$setMethodName(self::readNDO($provider, $ndo->$getMethodName(), new NDOFilter($ndo->$getMethodName()->getNdoID()), null, false, false, false, true));
                            $ndo->$getMethodName()->setInclude(true);
                        }

                        $ndo->setRead(true);
                        $ndo->setInclude(true);
                    } catch (RefineryException $exception) {
                        $provider->addMetaNotice('Error reading "' . $propertyName . '" from "' . JSONAPIBuilder::propertyToName($ndo->getNdoType()) . '" - "' . $ndo->getNdoID(), $exception->getMessage());
                    }
                }
            }
        }
    }
}
