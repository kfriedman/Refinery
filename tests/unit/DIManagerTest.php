<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\DIManager;

class DIManagerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        DIManager::setTestingMode(true);
    }

    public function tearDown()
    {
        DIManager::resetContainer();
    }

    public function testGetInitializesContainer()
    {
        DIManager::setTestingMode(false);

        $mockServiceName1 = 'MockService1';
        $mockService1 = \Mockery::mock();
        $mockService1->shouldReceive('mockMethod')->andReturn('Test Value');

        $config = \Mockery::mock();
        $config
            ->shouldReceive('getItem')
            ->withArgs(array('DI.Services', \Mockery::any(), \Mockery::any()))
            ->andReturn(array(
                $mockServiceName1 => $mockService1
            ));
        DIManager::set('Config', $config, true);

        $this->assertSame('Test Value', DIManager::get($mockServiceName1)->mockMethod());
    }

    public function testGetDefaultServicesUsesClassName()
    {
        DIManager::setTestingMode(false);

        $mockServiceName2 = 'MockService2';
        $mockService2 = \Mockery::mock();

        $config = \Mockery::mock();
        $config
            ->shouldReceive('getItem')
            ->withArgs(array('DI.Services', \Mockery::any(), \Mockery::any()))
            ->andReturn(array(
                $mockServiceName2 => get_class($mockService2)
            ));
        DIManager::set('Config', $config, true);

        $this->assertInstanceOf('\Mockery\MockInterface', DIManager::get($mockServiceName2, array('TestKey' => 'Test Value')));
    }

    public function testGetReturnsNewServiceAfterSet()
    {
        $serviceName = 'TestService';
        $service = \Mockery::mock();

        DIManager::set($serviceName, $service, true);

        $this->assertSame($service, DIManager::get($serviceName));
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testResetFromGetResetsContainer()
    {
        $serviceName1 = 'TestService1';
        $service1 = \Mockery::mock();

        DIManager::set($serviceName1, $service1);

        $serviceName2 = 'TestService2';
        $service2 = \Mockery::mock();

        DIManager::set($serviceName2, $service2);

        DIManager::get($serviceName1, array(), true);
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testResetFromSetResetsContainer()
    {
        $serviceName1 = 'TestService1';
        $service1 = \Mockery::mock();

        DIManager::set($serviceName1, $service1);

        $serviceName2 = 'TestService2';
        $service2 = \Mockery::mock();

        DIManager::set($serviceName2, $service2, true);

        DIManager::get($serviceName1);
    }

    /**
     * We can't test the constructor completely so this isn't a perfect test
     */
    public function testParameterGetsSet()
    {
        $serviceName = 'TestService';
        $service = \Mockery::mock();

        DIManager::set($serviceName, $service);

        $this->assertSame($service, DIManager::get($serviceName, array('constructorArg' => 'constructorValue')));
    }

    public function testSetWithClassNameSetsService()
    {
        $serviceName = 'TestServiceWithName';
        $service = \Mockery::mock();

        DIManager::set($serviceName, get_class($service));

        $this->assertInstanceOf(get_class($service), DIManager::get($serviceName));
    }

    public function testGetCacheManagerReturnsCacheManagerService()
    {
        DIManager::set('CacheManager', \Mockery::mock('NYPL\Refinery\CacheManager'));

        $this->assertInstanceOf('\NYPL\Refinery\CacheManager', DIManager::getCacheManager());
    }

    public function testGetCacheClientReturnsCacheClientService()
    {
        DIManager::set('CacheClient', \Mockery::mock('NYPL\Refinery\Cache\CacheClient'));

        $this->assertInstanceOf('NYPL\Refinery\Cache\CacheClient', DIManager::getCacheClient());
    }

    public function testGetQueueManagerReturnsQueueManagerService()
    {
        DIManager::set('QueueManager', \Mockery::mock('NYPL\Refinery\QueueManager'));

        $this->assertInstanceOf('NYPL\Refinery\QueueManager', DIManager::getQueueManager());
    }

    public function testGetConfigWithoutConfigReturnsConfigService()
    {
        $this->assertInstanceOf('NYPL\Refinery\Config\Config', DIManager::getConfig());
    }

    public function testGetNDOReaderReturnsNDOReader()
    {
        DIManager::set('NDOReader', \Mockery::mock('NYPL\Refinery\ProviderHandler\NDOReader'));

        $this->assertInstanceOf('NYPL\Refinery\ProviderHandler\NDOReader', DIManager::getNDOReader());
    }
}