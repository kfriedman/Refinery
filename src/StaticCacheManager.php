<?php
namespace NYPL\Refinery;

use NYPL\Refinery\Helpers\TextHelper;

/**
 * Class used to manage the Static Cache operations.
 *
 * @package NYPL\Refinery
 */
class StaticCacheManager
{
    protected static $staticCache = array();

    /**
     * @param string $type
     * @param string $key
     *
     * @return bool
     */
    public static function read($type = '', $key = '')
    {
        if (is_array($key)) {
            $key = self::transformArrayKey($key);
        }

        if (isset(self::$staticCache[$type][$key])) {
            return self::$staticCache[$type][$key];
        } else {
            return false;
        }
    }

    /**
     * @param string $type
     * @param string $key
     * @param null   $data
     *
     * @return bool
     */
    public static function save($type = '', $key = '', $data = null)
    {
        if (is_array($key)) {
            $key = self::transformArrayKey($key);
        }

        self::$staticCache[$type][$key] = $data;

        return true;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public static function clear($type = '')
    {
        if ($type) {
            unset(self::$staticCache[$type]);
        } else {
            foreach (array_keys(self::$staticCache) as $type) {
                unset(self::$staticCache[$type]);
            }
        }

        Server::setManualCacheTtl();

        return true;
    }

    /**
     * @param StaticCache $staticCache
     */
    public static function clearSpecificType(StaticCache $staticCache)
    {
        self::clear(get_class($staticCache));
    }

    /**
     * @param array $key
     *
     * @return string
     */
    protected static function transformArrayKey(array $key = array())
    {
        return implode('/', TextHelper::arrayFlatten($key));
    }
}
