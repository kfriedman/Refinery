<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\DIManager;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Server\Endpoint\api\nypl\locations\v1_0\locations\IndexEndpoint;
use NYPL\Refinery\Config\Config;

class LocationsgroupIndexEndpointTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!defined('CONFIG_FILE_DIRECTORY')) {
            define('CONFIG_FILE_DIRECTORY', __DIR__ . '/../../../../../../../../../config/app');
        }
        if (!defined('ENVIRONMENT_VARIABLE_NAME')) {
            define('ENVIRONMENT_VARIABLE_NAME', 'SERVER_ENV');
        }

        Config::initialize(new \Configula\Config(CONFIG_FILE_DIRECTORY), getenv(ENVIRONMENT_VARIABLE_NAME));

        $cacheManager = \Mockery::mock('CacheManager');
        $cacheManager
            ->shouldReceive('isEnabled')->andReturn(true)->getMock();
        DIManager::set('CacheManager', $cacheManager);

        $cacheClient = \Mockery::mock('CacheClient');
        $cacheClient->shouldIgnoreMissing();
        DIManager::set('CacheClient', $cacheClient);
    }

    public function testLocationsGroupEndpoint()
    {
        $endpoint = new IndexEndpoint();
        $endpoint->setDebug(true);
        $endpoint->get(new NDOFilter('schwarzman'));

        self::assertInstanceOf('\\NYPL\\Refinery\\Server\\Endpoint\\Response', $endpoint->getResponse());
    }
}
?>