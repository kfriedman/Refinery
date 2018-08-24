<?php
namespace NYPL\Refinery\Config;

use Configula;
use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\StaticCache\StaticConfig;

/**
 * Static class used for Refinery Config Manager
 *
 * @package NYPL\Refinery\Config
 */
class Config
{
    /**
     * Default environment if no environment variable is set
     */
    const DEFAULT_ENVIRONMENT = 'development';

    /**
     * @var Configula\Config Stores the Configula object
     */
    private static $configulaObject;

    /**
     * @var string The environment specified
     */
    private static $environment = '';

    /**
     * @var array Array of override items
     */
    private static $overrideItems = array();

    /**
     * Initializing the environment using Configula object.
     *
     * @param Configula\Config $configulaObject
     * @param string           $environmentName Default is 'development'
     *
     * @throws RefineryException
     */
    public static function initialize(Configula\Config $configulaObject, $environmentName = '')
    {
        self::$configulaObject = $configulaObject;

        if (!$environmentName) {
            $environmentName = self::$configulaObject->getItem('Environment', self::DEFAULT_ENVIRONMENT);
        }

        self::setEnvironment($environmentName);

        if (!is_array(self::$configulaObject->getItem(self::getEnvironment()))) {
            throw new RefineryException('Environment (' . self::getEnvironment() . ') not found in configuration file');
        }
    }

    /**
     * Returns value of a configuration item
     *
     * @param string $item          The configuration item to retrieve
     * @param null   $defaultValue  The default value to return for a configuration item if no configuration item exists
     * @param bool   $noEnvironment If true, returns default value to return for a configuration item
     * @param string $environment   If specified, used this as the active environment
     *
     * @return mixed
     * @throws RefineryException
     */
    public static function getItem($item = '', $defaultValue = null, $noEnvironment = false, $environment = '')
    {
        if (!self::$configulaObject) {
            throw new RefineryException('Configula object has not been initialized.');
        }

        if (!$noEnvironment) {
            if ($environment) {
                $item = $environment . '.' . $item;
            } else {
                $item = self::getEnvironment() . '.' . $item;
            }
        }

        if (self::getOverrideItem($item) !== null) {
            return self::getOverrideItem($item);
        }

        if ($staticCache = StaticConfig::read($item)) {
            return $staticCache;
        } else {
            $value = self::$configulaObject->getItem($item, $defaultValue);

            if ($value === null) {
                $value = false;
            }

            StaticConfig::save($item, $value);

            return $value;
        }
    }

    /**
     * Gets the configuration environment
     *
     * @return string Configuration environment
     */
    public static function getEnvironment()
    {
        return self::$environment;
    }

    /**
     * Sets the configuration environment
     *
     * @param string $environment Configuration environment, default is blank
     */
    protected static function setEnvironment($environment = '')
    {
        self::$environment = $environment;
    }

    /**
     * Checks if given extension is running in environment. Throws RefineryException with
     * error message saying the extension is not running.
     *
     * @param string $extensionName
     *
     * @throws RefineryException
     */
    public static function checkExtension($extensionName = '')
    {
        if (!extension_loaded(Config::getItem('Extensions.' . $extensionName, null, true))) {
            throw new RefineryException('Required ' . $extensionName . ' extension (' . Config::getItem('Extensions.' . $extensionName, null, true) . ') is not running');
        }
    }

    /**
     * Reset the Configula object
     */
    public static function reset()
    {
        self::$configulaObject = null;
        self::$environment = '';
        self::$overrideItems = array();
    }

    /**
     * Add a configuration item to override
     *
     * @param string $item
     * @param mixed  $value
     * @param bool   $noEnvironment
     */
    public static function addOverrideItem($item = '', $value = null, $noEnvironment = false)
    {
        if (!$noEnvironment) {
            $item = self::getEnvironment() . '.' . $item;
        }

        self::$overrideItems[$item] = $value;
    }

    /**
     * Get an configuration item that was overridden
     *
     * @param string $item
     *
     * @return mixed
     */
    public static function getOverrideItem($item = '')
    {
        if (isset(self::$overrideItems[$item])) {
            return self::$overrideItems[$item];
        } else {
            return null;
        }
    }
}