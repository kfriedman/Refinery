<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\DIManager;
use NYPL\Refinery\HealthCheck\CacheCheck;
use NYPL\Refinery\Config\Config;

class CacheCheckTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        DIManager::resetContainer();

        if (!defined('CONFIG_FILE_DIRECTORY')) {
            define('CONFIG_FILE_DIRECTORY', __DIR__ . '/../../../config/app');
        }
        if (!defined('ENVIRONMENT_VARIABLE_NAME')) {
            define('ENVIRONMENT_VARIABLE_NAME', 'SERVER_ENV');
        }

        Config::initialize(new \Configula\Config(CONFIG_FILE_DIRECTORY), getenv(ENVIRONMENT_VARIABLE_NAME));

        $this->mockRedis = \Mockery::mock('Redis');
        $this->mockRedis
            ->shouldReceive('pconnect')->andReturn(true)->getMock()
            ->shouldReceive('setOption')->andReturn(true)->getMock()
            ->shouldReceive('ping')->andReturn(true)->getMock()
            ->shouldReceive('slaveof')->andReturn(true)->getMock()
            ->shouldReceive('info')->andReturn(true)->getMock();
        DIManager::set('Redis', $this->mockRedis);
    }

    public function testIfCacheClientIsAvailable()
    {
        $cacheCheck = new CacheCheck();
        $response = $cacheCheck->runCheck();

        self::assertTrue($response);
    }
}
?>
