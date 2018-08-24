<?php
namespace NYPL\Refinery;

use Aura\Di\Container;
use Aura\Di\Exception\ServiceNotFound;
use Aura\Di\Factory;
use NYPL\Refinery\Cache\CacheClient;
use NYPL\Refinery\Config\Config;
use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\ProviderHandler\NDOReader;

/**
 * Class used for dependency management in the Refinery.
 *
 * @package NYPL\Refinery
 */
class DIManager
{
    /**
     * Default name for a container if none is specified
     */
    const DEFAULT_CONTAINER_NAME = 'default';

    /**
     * An array of containers.
     *
     * @var Container[]
     */
    protected static $container = array();

    /**
     * Whether DIManager is running in testing mode
     *
     * @var bool
     */
    protected static $testingMode = false;

    /**
     * Reset the container.
     *
     * @param string $containerName
     *
     * @throws \Aura\Di\Exception\ContainerLocked
     */
    static public function resetContainer($containerName = self::DEFAULT_CONTAINER_NAME)
    {
        self::setContainer($containerName, new Container(new Factory));

        self::getContainer($containerName)->setAutoResolve(false);
    }

    /**
     * Initialize the container and set the default services.
     *
     * @param string $containerName
     */
    static protected function initializeContainer($containerName)
    {
        self::resetContainer($containerName);

        self::setDefaultServices($containerName);
    }

    /**
     * Set the default services for the container.
     *
     * @param string $containerName
     */
    static protected function setDefaultServices($containerName = '')
    {
        if (!self::isTestingMode()) {
            foreach (self::getDefaultServices() as $serviceName => $service) {
                self::set($serviceName, $service, false, $containerName);
            }
        }
    }

    /**
     * Get the container.
     *
     * @param string $containerName
     *
     * @return Container
     */
    protected static function getContainer($containerName = '')
    {
        return self::$container[$containerName];
    }

    /**
     * Set the container.
     *
     * @param string    $containerName
     * @param Container $container
     */
    protected static function setContainer($containerName, Container $container)
    {
        self::$container[$containerName] = $container;
    }

    /**
     * Set default services for the container.
     *
     * @return array
     * @throws RefineryException
     */
    protected static function getDefaultServices()
    {
        if (!self::isTestingMode()) {
            return self::getConfig()->getItem('DI.Services', null, true);
        } else {
            return array();
        }
    }

    /**
     * Get a service from the container.
     *
     * @param string $serviceName
     * @param array  $parameters
     * @param bool   $initializeContainer
     * @param string $containerName
     *
     * @return object
     * @throws RefineryException
     * @throws \Aura\Di\Exception\ServiceNotFound
     */
    public static function get($serviceName = '', array $parameters = null, $initializeContainer = false, $containerName = self::DEFAULT_CONTAINER_NAME)
    {
        if (!self::isContainerExists($containerName) || $initializeContainer) {
            self::initializeContainer($containerName);
        }

        if ($parameters) {
            self::setParameters($containerName, $serviceName, $parameters);
        }

        try {
            return self::getContainer($containerName)->get($serviceName);
        } catch (ServiceNotFound $exception) {
            throw new RefineryException('Service specified (' . $serviceName . ') was not found');
        }
    }

    /**
     * Set a service in the container.
     *
     * @param string        $serviceName
     * @param string|object $service
     * @param bool          $initializeContainer
     * @param string        $containerName
     *
     * @return $this
     * @throws \Aura\Di\Exception\ContainerLocked
     * @throws \Aura\Di\Exception\ServiceNotObject
     */
    public static function set($serviceName = '', $service = '', $initializeContainer = false, $containerName = self::DEFAULT_CONTAINER_NAME)
    {
        if (!self::isContainerExists($containerName) || $initializeContainer) {
            $initializeContainer = true;

            self::resetContainer($containerName);
        }

        if (is_object($service)) {
            self::getContainer($containerName)->set($serviceName, $service);
        } else {
            self::getContainer($containerName)->set($serviceName, self::getContainer($containerName)->lazyNew($service));
        }

        if ($initializeContainer) {
            self::setDefaultServices($containerName);
        }
    }

    /**
     * Sets parameters for a service.
     *
     * @param string $containerName
     * @param string $serviceName
     * @param array  $parameters
     *
     * @throws RefineryException
     */
    protected static function setParameters($containerName = '', $serviceName = '', array $parameters = array())
    {
        foreach ($parameters as $parameterName => $parameterValue) {
            self::getContainer($containerName)->params[self::translateToParamServiceName($serviceName)][$parameterName] = $parameterValue;
        }
    }

    /**
     * Translate the service name to the name used for parameters.
     *
     * @param string $serviceName
     *
     * @return string
     */
    protected static function translateToParamServiceName($serviceName = '')
    {
        $services = self::getDefaultServices();

        if (isset($services[$serviceName])) {
            return $services[$serviceName];
        } else {
            return $serviceName;
        }
    }

    /**
     * Getter for whether DIManager is running in testing mode
     *
     * @return boolean
     */
    public static function isTestingMode()
    {
        return self::$testingMode;
    }

    /**
     * Getter for whether DIManager is running in testing mode
     *
     * @param boolean $testingMode
     */
    public static function setTestingMode($testingMode)
    {
        self::$testingMode = $testingMode;
    }

    /**
     * Checks if a container exists.
     *
     * @param string $containerName
     *
     * @return bool
     */
    protected static function isContainerExists($containerName= '')
    {
        if (isset(self::$container[$containerName])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return Config service.
     *
     * @return Config
     * @throws RefineryException
     */
    public static function getConfig()
    {
        if (self::$container && !self::getContainer(self::DEFAULT_CONTAINER_NAME)->has('Config')) {
            self::set('Config', 'NYPL\Refinery\Config\Config');
        }

        return self::get('Config');
    }

    /**
     * Return CacheManager service.
     *
     * @return CacheManager
     * @throws RefineryException
     */
    public static function getCacheManager()
    {
        return self::get('CacheManager');
    }

    /**
     * Return CacheClient service.
     *
     * @return CacheClient
     * @throws RefineryException
     */
    public static function getCacheClient()
    {
        return self::get('CacheClient');
    }

    /**
     * Return QueueManager service.
     *
     * @return QueueManager
     * @throws RefineryException
     */
    public static function getQueueManager()
    {
        return self::get('QueueManager');
    }

    /**
     * Return NDOReader service.
     *
     * @return NDOReader
     * @throws RefineryException
     */
    public static function getNDOReader()
    {
        return self::get('NDOReader');
    }
}