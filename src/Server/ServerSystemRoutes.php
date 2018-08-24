<?php
namespace NYPL\Refinery\Server;

use NYPL\Refinery\Cache\CacheClient;
use NYPL\Refinery\Config\Config;
use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\QueueManager;
use Slim\Http\Request;

/**
 * Handles system routes from the Refinery Server.
 *
 * @package NYPL\Refinery
 */
class ServerSystemRoutes
{
    /**
     * @var array
     */
    protected static $nonProductionEnvironments = array('local', 'development', 'qa');

    /**
     * @param Request $request
     * @param string  $cacheKeyPattern
     *
     * @return array
     */
    public static function getCacheRoute(Request $request, $cacheKeyPattern = '')
    {
        if ($cacheKeyPattern == 'info') {
            return CacheClient::getInfo();
        } else {
            $keys = CacheClient::keys($cacheKeyPattern);

            if ($request->get('delete')) {
                CacheClient::del($keys);
            }

            foreach ($keys as &$key) {
                $key .= '=' . CacheClient::getTTL($key);
            }

            return array('count' => count($keys), 'keys' => $keys);
        }
    }

    /**
     * @return null
     */
    public static function getClearQueueRoute()
    {
        QueueManager::clearQueue(QueueManager::getQueue());
        QueueManager::clearQueue(QueueManager::getQueueDelayed());

        return null;
    }

    /**
     * @param int $sleepTime
     *
     * @throws RefineryException
     */
    public static function getLoadRoute($sleepTime = 0)
    {
        self::checkNonProductionEnvironment();

        $serverEndTime = microtime(true) + $sleepTime;

        $random = 0;

        while (microtime(true) < $serverEndTime) {
            $random += mt_rand();
            json_encode(array('test' => 'test'));
        }

        echo '<code>OKAY (SLEEP: ' . $sleepTime . ')</code>';
    }

    /**
     * @return null
     */
    public static function getPHPInfoRoute()
    {
        phpinfo();

        return null;
    }

    /**
     * @return array
     */
    public static function getInfoRoute()
    {
        exec('uptime', $uptime);

        return array('ip' => $_SERVER['SERVER_ADDR'], 'uptime' => $uptime[0]);
    }

    /**
     * @return bool
     * @throws RefineryException
     */
    public static function checkNonProductionEnvironment()
    {
        if (in_array(Config::getEnvironment(), self::$nonProductionEnvironments)) {
            return true;
        } else {
            throw new RefineryException('Invalid environment (' . Config::getEnvironment() . ') for this route');
        }
    }

    /**
     * Generate a fake error
     */
    public static function testError()
    {
        self::checkNonProductionEnvironment();

        fake_function();
    }
}
