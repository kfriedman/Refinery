<?php
namespace NYPL\Refinery\Cache;

use NYPL\Refinery\DIManager;
use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\Helpers\ClassNameHelper;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\QueueManager;
use NYPL\Refinery\QueueMessage\RefreshCache;
use NYPL\Refinery\QueueMessage\ProviderRefresh;
use NYPL\Refinery\Serializer;
use NYPL\Refinery\Server;
use NYPL\Refinery\StaticCache\StaticCacheData;
use NYPL\Refinery\StaticCache\StaticRefreshCache;

/**
 * Class CacheData.
 *
 * Creates cache entries for Refinery data. Time interval is set to define how often
 * the cache should be refreshed. Key-data value pair is created, ready to be stored
 * in cache store such as Redis.
 *
 * @package NYPL\Refinery\Cache
 */
abstract class CacheData
{
    const NAMESPACE_SEPARATOR = ':';
    const NAMESPACE_SEPARATOR_REPLACE = '#';

    const COMPRESSION_ENABLED = false;
    const COMPRESSION_THRESHOLD = 100000;

    /**
     * @var string CacheData type.
     */
    protected $cacheType = '';

    /**
     * @var string Key used for cache data store.
     */
    protected $key = '';

    /**
     * @var string URI to retrieve fresh copy of data.
     */
    protected $refreshURI = '';

    /**
     * @var mixed The cache data.
     */
    protected $data = false;

    /**
     * @var \DateTime Date cache added.
     */
    protected $dateAdded;

    /**
     * @var null|int Data encoding format.
     */
    protected $encodingFormat = null;

    /**
     * Force refreshing of cache data.
     *
     * @var bool
     */
    protected $forceRefresh = false;

    /**
     * Priority that should be used for refreshing of cache data.
     *
     * @var int
     */
    protected $refreshPriority = 0;

    /**
     * Refresh the cache data if it is stale.
     *
     * @var bool
     */
    protected $refreshIfStale = false;

    /**
     * Whether the cache key has been read or not.
     *
     * @var bool
     */
    protected $read = false;

    /**
     * Creates new instance of CacheData.
     *
     * @param string $key        Key to be used in cache store.
     * @param null   $refreshURI Data endpoint URI used to refresh cache data.
     * @param bool   $skipRead   To skip reading of existing cache data.
     *
     * @throws RefineryException
     */
    public function __construct($key = '', $refreshURI = null, $skipRead = false)
    {
        if (!$key) {
            throw new RefineryException('CacheData key was not specified');
        }

        $this->setCacheType();

        $this->setKey($key);

        if ($refreshURI) {
            if (!filter_var($refreshURI, FILTER_VALIDATE_URL)) {
                throw new RefineryException('CacheData refreshURI specified (' . $refreshURI . ') is not valid');
            }
        }

        if ($refreshURI) {
            $this->setRefreshURI($refreshURI);
        }

        if (!$skipRead) {
            $this->read();
        }
    }

    /**
     * Gets the cache data.
     *
     * @return mixed
     */
    public function getData()
    {
        if (!$this->isRead()) {
            $this->read();
        }

        return $this->data;
    }

    /**
     * Sets the cache data.
     *
     * @param mixed $data
     */
    protected function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Gets cache store key.
     *
     * @return string
     */
    protected function getKey()
    {
        return $this->key;
    }

    /**
     * Sets cache store key.
     *
     * @param string $key
     */
    protected function setKey($key)
    {
        $this->key = $this->formatKey($key);
    }

    /**
     * Sets key in cache store compliant format.
     *
     * @param array|string $key
     *
     * @return mixed
     */
    protected function formatKey($key)
    {
        if (is_array($key)) {
            array_walk($key, 'self::removeNamespaceSeparatorFromKey');

            return implode(self::NAMESPACE_SEPARATOR, $key);
        } else {
            $this->removeNamespaceSeparatorFromKey($key);

            return $key;
        }
    }

    /**
     * Returns key in cache store compliant format.
     *
     * @param string $key
     *
     * @return string
     */
    protected function removeNamespaceSeparatorFromKey(&$key = '')
    {
        if (is_array($key)) {
            sort($key);

            foreach ($key as $keyName => &$keyValue) {
                if (is_array($keyValue)) {
                    $keyValue = $keyName . ',' . implode(',', $keyValue);
                }
            }

            $key = implode(',', $key);
        }

        $key = str_replace(self::NAMESPACE_SEPARATOR, self::NAMESPACE_SEPARATOR_REPLACE, $key);
    }

    /**
     * Gets cache key.
     *
     * @return string In CacheType:CacheKey format
     */
    protected function getCacheKey()
    {
        return $this->getCacheType() . self::NAMESPACE_SEPARATOR . $this->getKey();
    }

    /**
     * Reads cache data from cache store, refreshes cache data if needed.
     */
    protected function read()
    {
        if (DIManager::getCacheManager()->isEnabled()) {
            $this->setRead(true);

            // Removing static caching of data to prevent recursive loops
            // $staticCache = StaticCacheData::read($this->getCacheKey());
            $staticCache = false;

            if ($staticCache !== false) {
                $redisData = $staticCache;
            } else {
                $redisData = DIManager::getCacheClient()->get($this->getCacheKey());
            }

            if ($redisData !== false) {
                if ($redisData['data']) {
                    if ($staticCache !== false) {
                        $this->setData($redisData['data']);
                    } else {
                        if (isset($redisData['isCompressed'])) {
                            $redisData['data'] = gzuncompress($redisData['data']);
                        }

                        if (isset($redisData['isString'])) {
                            $this->setData($redisData['data']);
                        } else {
                            $this->setData(Serializer::decode($redisData['data'], $this->getEncodingFormat()));
                        }
                    }
                } else {
                    $this->setData(null);
                }

                if (isset($redisData['dateAdded'])) {
                    $this->setDateAdded(new \DateTime($redisData['dateAdded']));
                }
            }
        }
    }

    /**
     * Process refresh of cache data.
     *
     * @param bool      $useProviderRefresh
     * @param string    $refreshURI
     * @param Provider  $provider
     * @param NDOFilter $ndoFilter
     */
    public function processRefresh($useProviderRefresh = false, $refreshURI = '', Provider $provider  = null, NDOFilter $ndoFilter = null)
    {
        $this->setForceRefresh(Server::isForceRefresh());
        $this->setRefreshPriority(Server::getForceRefreshPriority());
        $this->setRefreshIfStale(Server::isRefreshIfStale());

        if (
            ($this->isForceRefresh() || ($this->isRefreshIfStale() && !$this->isFresh()))
            && $this->getRefreshURI()
        ) {
            if ($useProviderRefresh) {
                $this->setRefreshURI($refreshURI);
            } else {
                $this->setRefreshURI($this->getRefreshURI());
            }

            $staticCacheName = $useProviderRefresh . ':' . $this->getRefreshURI();

            if (!StaticRefreshCache::read($staticCacheName)) {
                if ($useProviderRefresh) {
                    QueueManager::add(
                        new ProviderRefresh(null, $this->getRefreshURI(), $provider, $ndoFilter),
                        true,
                        0,
                        $this->getRefreshPriority()
                    );
                } else {
                    QueueManager::add(
                        new RefreshCache(null, $this->getRefreshURI()),
                        true,
                        0,
                        $this->getRefreshPriority()
                    );
                }

                StaticRefreshCache::save($staticCacheName, true);
            }
        }
    }

    /**
     * Checks to see if cache is fresh. Flag to trigger refreshing of API cache.
     *
     * @return bool
     */
    protected function isFresh()
    {
        if ($this->isExists()) {
            if ($this->getDateAdded()->add(new \DateInterval(DIManager::getConfig()->getItem('Cache.FreshInterval'))) > new \DateTime()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determines existence of cache data.
     *
     * @return bool
     */
    public function isExists()
    {
        if ($this->getData() === false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Saves API data into cache, sets ttl (time-to-live), the time interval to tell when to refresh cache data.
     *
     * @param mixed    $data
     * @param bool|int $ttlSeconds Specify TTL settings for data. If none specified, use the default TTL specified by the configuration.
     *
     * @throws RefineryException
     */
    public function save($data, $ttlSeconds = false)
    {
        if (DIManager::getCacheManager()->isEnabled() && !StaticCacheData::read($this->getCacheKey())) {
            $cacheValue = array();

            $cacheValue['dateAdded'] = date('c');

            if ($this->getRefreshURI()) {
                $cacheValue['refreshURI'] = $this->getRefreshURI();
            }

            if ($ttlSeconds === false && $this->getDefaultTTLSeconds()) {
                $ttlSeconds = $this->getDefaultTTLSeconds();
            }

            $cacheValue['data'] = $data;

            // Removing storage of actual data since we're not using it anymore
            // StaticCacheData::save($this->getCacheKey(), $cacheValue);
            StaticCacheData::save($this->getCacheKey(), true);

            if (!$this->isFresh() || Server::isForceRefresh() || Server::isRefreshIfStale()) {
                if ($data) {
                    if (is_string($data)) {
                        $cacheValue['isString'] = true;
                    } else {
                        $cacheValue['data'] = Serializer::encode($data, $this->getEncodingFormat());
                    }

                    if (strlen($cacheValue['data']) > self::COMPRESSION_THRESHOLD && self::COMPRESSION_ENABLED) {
                        $cacheValue['isCompressed'] = true;

                        $cacheValue['data'] = gzcompress($cacheValue['data']);
                    }
                }

                DIManager::getCacheClient()->set($this->getCacheKey(), $cacheValue, $ttlSeconds);
            }
        }
    }

    /**
     * Deletes cache data by cache key.
     */
    public function delete()
    {
        DIManager::getCacheClient()->del($this->getCacheKey());
    }

    /**
     * Gets date of CacheData added
     *
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Sets date CacheData added
     *
     * @param \DateTime $dateAdded
     */
    protected function setDateAdded(\DateTime $dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * Gets cache type.
     *
     * @return string
     */
    protected function getCacheType()
    {
        return $this->cacheType;
    }

    /**
     * Sets cache type.
     *
     * @throws RefineryException
     */
    protected function setCacheType()
    {
        $this->cacheType = ClassNameHelper::getNameWithoutNamespace($this);
    }

    /**
     * Gets RefreshURI to retrieve new data to refresh cache.
     *
     * @return string
     */
    protected function getRefreshURI()
    {
        return $this->refreshURI;
    }

    /**
     * Sets RefreshURI to retrieve new data to refresh cache.
     *
     * @param string $refreshURI
     */
    protected function setRefreshURI($refreshURI)
    {
        $this->refreshURI = $refreshURI;
    }

    /**
     * Gets CacheData encoding format.
     *
     * @return int|null
     * @throws RefineryException
     */
    protected function getEncodingFormat()
    {
        if ($this->encodingFormat === null) {
            throw new RefineryException('Encoding format was not specified.');
        }

        return $this->encodingFormat;
    }

    /**
     * Gets default TTL (time-to-live, time interval to refresh cache) in seconds,
     * derived from configuration file. Returns 0 if no default TTL is set
     * for the cache type.
     *
     * @return int  0 if no default TTL is set for cache type
     * @throws RefineryException
     */
    protected function getDefaultTTLSeconds()
    {
        if (Server::getManualCacheTtl()) {
            $configTTL = Server::getManualCacheTtl();
        } else {
            $configTTL = DIManager::getConfig()->getItem('Cache.' . $this->getCacheType() . '.DefaultTTL');
        }

        if ($configTTL !== false) {
            $refreshInterval = new \DateInterval($configTTL);

            if ($refreshInterval->s) {
                return (int) $refreshInterval->s;
            }
            if ($refreshInterval->i) {
                return (int) $refreshInterval->i * 60;
            }

            throw new RefineryException('Refresh interval specified (' . $configTTL . ') is not recognized');
        }

        return 0;
    }

    /**
     * Getter for force refreshing of cache data.
     *
     * @return boolean
     */
    protected function isForceRefresh()
    {
        return $this->forceRefresh;
    }

    /**
     * Setter for force refreshing of cache data.
     *
     * @param boolean $forceRefresh
     */
    protected function setForceRefresh($forceRefresh)
    {
        $this->forceRefresh = $forceRefresh;
    }

    /**
     * Getter for priority that should be used for refreshing of cache data.
     *
     * @return int
     */
    protected function getRefreshPriority()
    {
        return $this->refreshPriority;
    }

    /**
     * Setter for priority that should be used for refreshing of cache data.
     *
     * @param int $refreshPriority
     */
    protected function setRefreshPriority($refreshPriority)
    {
        $this->refreshPriority = $refreshPriority;
    }

    /**
     * Getter for refreshing of cache data if it is stale.
     *
     * @return boolean
     */
    protected function isRefreshIfStale()
    {
        return $this->refreshIfStale;
    }

    /**
     * Setter for refreshing of cache data if it is stale.
     *
     * @param boolean $refreshIfStale
     */
    protected function setRefreshIfStale($refreshIfStale)
    {
        $this->refreshIfStale = $refreshIfStale;
    }

    /**
     * @return boolean
     */
    protected function isRead()
    {
        return $this->read;
    }

    /**
     * @param boolean $read
     */
    protected function setRead($read)
    {
        $this->read = (bool) $read;
    }
}
