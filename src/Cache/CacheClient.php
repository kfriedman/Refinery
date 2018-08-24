<?php
namespace NYPL\Refinery\Cache;

use NYPL\Refinery\DIManager;
use Redis;
use NYPL\Refinery\Exception\RefineryException;

/**
 * Class CacheClient.
 *
 * Class for interfacing with Redis. Using a persistent data store, cached items
 * go into a hash with data and timestamp fields. A separate refresh method can be
 * used to check against an interval when the cache requires new data.
 *
 * @package NYPL\Refinery
 */
class CacheClient
{
    /**
     * @var Redis The object that interfaces with Redis cache and store.
     */
    protected static $redis;

    /**
     * Hostname of the cache server.
     *
     * @var string
     */
    protected static $host = '';

    /**
     * Initializes Redis connection.
     *
     * @throws RefineryException
     */
    protected static function initializeRedis()
    {
        try {
            /**
             * @var Redis $redis
             */
            $redis = DIManager::get('Redis');

            $redis->pconnect(self::getHost());

            $redis->setOption($redis::OPT_SERIALIZER, $redis::SERIALIZER_PHP);
            $redis->setOption($redis::OPT_PREFIX, DIManager::getConfig()->getItem('Cache.Prefix'));

            self::setRedis($redis);
        } catch (\RedisException $exception) {
            throw new RefineryException('Error initializing Redis: ' . $exception->getMessage());
        }
    }

    /**
     * Returns data from cache store by key.
     *
     * @param string $key Lookup key for cache store.
     *
     * @return bool|string If key didn't exist, FALSE is returned. Otherwise, the value related to this key is returned.
     */
    public static function get($key = '')
    {
        return self::getRedis()->get($key);
    }

    /**
     * Returns the keys that match a certain pattern.
     *
     * @param string $pattern
     * @param bool   $doNotRemovePrefix
     *
     * @return array The keys that match a certain pattern.
     */
    public static function keys($pattern = '', $doNotRemovePrefix = false)
    {
        $keys = self::getRedis()->keys($pattern);

        if (!$doNotRemovePrefix) {
            // Remove the prefix from the keys
            if ($keys && DIManager::getConfig()->getItem('Cache.Prefix')) {
                foreach ($keys as &$key) {
                    $key = str_replace(DIManager::getConfig()->getItem('Cache.Prefix'), '', $key);
                }
            }
        }

        return $keys;
    }

    /**
     * Sets cache data and timeout by key in cache store.
     *
     * @param  string $key     Cache store key, blank if none is specified.
     * @param  null   $data    Refinery data, null if none is specified.
     * @param  int    $timeout Time interval for when to refresh cache in seconds. 0 if none is specified.
     *
     * @return bool TRUE if the command is successful.
     * @link   http://redis.io/commands/set
     */
    public static function set($key = '', $data = null, $timeout = 0)
    {
        if ($timeout) {
            return self::getRedis()->set($key, $data, (int) $timeout);
        } else {
            return self::getRedis()->set($key, $data);
        }
    }

    /**
     * Deletes cache data by key in cache store.
     *
     * @param array|string $key
     *
     * @return int Number of keys deleted.
     */
    public static function del($key)
    {
        return self::getRedis()->del($key);
    }

    /**
     * Delete all the keys of all the existing databases, not just the currently
     * selected one. This command never fails.
     *
     * @return bool Always TRUE
     */
    public static function flushAll()
    {
        return self::getRedis()->flushAll();
    }

    /**
     * Returns Redis cache and store object.
     *
     * @return Redis
     */
    protected static function getRedis()
    {
        if (!self::$redis) {
            self::initializeRedis();
        }

        return self::$redis;
    }

    /**
     * Sets Redis cache and store object.
     *
     * @param Redis $redis
     */
    protected static function setRedis(Redis $redis)
    {
        self::$redis = $redis;
    }

    /**
     * Check the current connection status. +PONG on success.
     *
     * @return string
     * @throws RefineryException
     */
    public static function ping()
    {
        try {
            return self::getRedis()->ping();
        } catch (\RedisException $exception) {
            throw new RefineryException($exception->getMessage());
        }
    }

    /**
     * Getter for the hostname of the cache server.
     *
     * @return string
     */
    public static function getHost()
    {
        if (!self::$host) {
            self::setHost(DIManager::getConfig()->getItem('Cache.Host'));
        }

        return self::$host;
    }

    /**
     * Setter for the hostname of the cache server.
     *
     * @param string $host
     */
    public static function setHost($host)
    {
        self::$host = $host;
    }

    /**
     * Disconnect and clear the Redis object
     */
    public static function disconnect()
    {
        if (self::$redis) {
            self::$redis->close();
        }

        self::$redis = null;
    }

    /**
     * Get the TTL for a key
     *
     * @param string $key
     *
     * @return int
     */
    public static function getTTL($key = '')
    {
        return self::$redis->ttl($key);
    }

    /**
     * @return string
     * @throws RefineryException
     */
    public static function getInfo()
    {
        if ($info = self::getRedis()->info()) {
            return $info;
        }

        throw new RefineryException('There was an error connecting to Redis');
    }
}
