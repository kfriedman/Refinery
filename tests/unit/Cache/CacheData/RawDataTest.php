<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\DIManager;
use NYPL\Refinery\Cache\CacheData\RawData;

class RawDataTest extends \PHPUnit_Framework_TestCase
{
    public static function tearDownAfterClass()
    {
        DIManager::resetContainer();
    }

    public function testDataRead()
    {
        $key = 'Key';
        $uri = 'http://dev.www.aws.nypl.org/refinery/api/v0_1/content/blogpostgroup';

        $data = array('DataKey' => 'DataValue');
        $encodedData = bson_encode($data);

        $cachedRawData = array(
            'data' => $encodedData,
            'dateAdded' => date('r')
        );

        $config = \Mockery::mock('Config');
        $config
            ->shouldReceive('checkExtension')->withArgs(array('Serializer'))->andReturn(true)->getMock();
        DIManager::set('Config', $config);

        $cacheManager = \Mockery::mock('CacheManager');
        $cacheManager
            ->shouldReceive('isEnabled')->andReturn(true)->getMock();
        DIManager::set('CacheManager', $cacheManager);

        $cacheClient = \Mockery::mock('CacheClient');
        $cacheClient
            ->shouldReceive('get')->andReturn($cachedRawData)->getMock();
        DIManager::set('CacheClient', $cacheClient);

        $rawData = new RawData($key, $uri);

        $this->assertSame($rawData->getData(), $data);
    }
}