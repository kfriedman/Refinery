<?php
namespace NYPL\Refinery;

use GuzzleHttp\Client;
use NYPL\Refinery\Cache\CacheData\RawData;
use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\Helpers\SystemHelper;
use NYPL\Refinery\QueueMessage\InitializeCache;
use Symfony\Component\Yaml\Yaml;

/**
 * Class used to manage the Refinery Cache operations.
 *
 * @package NYPL\Refinery
 */
class CacheManager
{
    /**
     * Whether the Cache is enabled or not.
     *
     * @var bool
     */
    protected static $enabled;

    /**
     * Check to see if the Cache is enabled by the configuration.
     *
     * @return boolean
     */
    public static function isEnabled()
    {
        if (self::$enabled === null) {
            self::setEnabled((bool) DIManager::getConfig()->getItem('Cache.Enabled'));
        }

        return self::$enabled;
    }

    /**
     * Setter for the enabled parameter.
     *
     * @param boolean $enabled
     */
    public static function setEnabled($enabled)
    {
        self::$enabled = $enabled;
    }

    /**
     * Initializes the Cache. Clears the Queue of any messages and seeds the
     * Cache with data from a list of URLs specified by the configuration
     * file.
     */
    public static function initialize()
    {
        if (self::isEnabled()) {
            $configFileURL = DIManager::getConfig()->getItem('Cache.ConfigFileURL');
            $hostMaster = DIManager::getConfig()->getItem('Cache.HostMaster');

            if ($configFileURL && (!$hostMaster || SystemHelper::isLocalIPAddress($hostMaster))) {
                DIManager::getConfig()->checkExtension('Cache');

                // Delete any existing InitializeCache messages
                $queueMessage = new InitializeCache();
                
                DIManager::getCacheClient()->del(
                    DIManager::getCacheClient()->keys('QueueRecord:' . $queueMessage->getMessageType() . ':*')
                );

                // Clear queues of any messages
                DIManager::getQueueManager()->clearQueue(DIManager::getQueueManager()->getQueue());

                $cachedCacheConfig = new RawData($configFileURL);

                try {
                    $client = new Client();
                    $cacheConfig = (string) $client->get($configFileURL)->getBody();

                    $cachedCacheConfig->save($cacheConfig, 0);
                } catch (\Exception $exception) {
                    new RefineryException('Unable to get config file: ' . $exception->getMessage());

                    $cacheConfig = $cachedCacheConfig->getData();
                }

                $yamlParser = new Yaml();

                $cacheConfig = $yamlParser->parse($cacheConfig);

                foreach ($cacheConfig['refresh-urls'] as $refreshMinutes => $refreshURLArray) {
                    DIManager::getQueueManager()->setupDelayedQueue($refreshMinutes);

                    foreach ($refreshURLArray as $refreshURL) {
                        DIManager::getQueueManager()->add(
                            new InitializeCache(null, $refreshURL, $refreshMinutes)
                        );
                    }
                }
            }
        }
    }
}
