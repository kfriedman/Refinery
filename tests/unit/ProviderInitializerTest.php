<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\Config\Config;
use NYPL\Refinery\DIManager;
use NYPL\Refinery\ProviderInitializer;

class ProviderInitializerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Set up for all tests that use a mock Provider.
     */
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

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testProviderDoesNotInitialize()
    {
        $badProvider = \Mockery::mock('NYPL\Refinery\Provider\RESTAPI\D7RefineryServerCurrent')->makePartial();
        $badProvider->shouldReceive('setDebug');
        
        ProviderInitializer::initializeProvider($badProvider, true);
    }

    public function testProviderInitializes()
    {
        $provider = \Mockery::mock('NYPL\Refinery\Provider\RESTAPI')->makePartial();
        $provider->shouldReceive('setDebug');

        $host = 'testing.tld';
        
        $provider->setHost($host);
        $provider->setBaseURL('refinery/api/v0.1');

        $config = \Mockery::mock('Config');
        $config->shouldReceive('getItem')->andReturn($host);
        $config->shouldIgnoreMissing();
        DIManager::set('Config', $config, true);

        ProviderInitializer::initializeProvider($provider, 'environment');
        
        $this->assertInstanceOf('\\NYPL\\Refinery\\Provider', $provider);
    }
}
