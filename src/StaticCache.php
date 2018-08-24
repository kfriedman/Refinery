<?php
namespace NYPL\Refinery;

/**
 * Abstract class for Static Cache objects.
 *
 * @package NYPL\Refinery
 */
abstract class StaticCache
{
    /**
     * @var string
     */
    protected static $key = '';

    /**
     * @var mixed
     */
    protected static $data;

    /**
     * @param string $name
     *
     * @return mixed
     */
    public static function read($name = '')
    {
        return StaticCacheManager::read(get_called_class(), $name);
    }

    /**
     * @param string $name
     * @param mixed  $data
     *
     * @return bool
     */
    public static function save($name = '', $data = null)
    {
        return StaticCacheManager::save(get_called_class(), $name, $data);
    }

    /**
     * @return bool
     */
    public static function clear()
    {
        return StaticCacheManager::clear(get_called_class());
    }
}