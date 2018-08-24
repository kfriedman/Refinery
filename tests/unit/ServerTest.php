<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\Server;
use NYPL\Refinery\Config\Config;

class ServerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!defined('CONFIG_FILE_DIRECTORY')) {
            define('CONFIG_FILE_DIRECTORY', __DIR__ . '/../../config/app');
        }
        if (!defined('ENVIRONMENT_VARIABLE_NAME')) {
            define('ENVIRONMENT_VARIABLE_NAME', 'SERVER_ENV');
        }

        Config::initialize(new \Configula\Config(CONFIG_FILE_DIRECTORY), getenv(ENVIRONMENT_VARIABLE_NAME));
    }

    public function testServerIsInitialized()
    {
        $mockServer = \Mockery::mock('\NYPL\Refinery\Server');
        $mockServer->shouldReceive('run')->andSet('basePath', Config::getItem('Server.BasePath'))->andReturn('\Slim\Slim');

        $this->assertInstanceOf('\\NYPL\\Refinery\\Server', $mockServer);
    }
}
?>