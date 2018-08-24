<?php
namespace NYPL\Refinery\Tests;

use Mockery\MockInterface;
use NYPL\Refinery\DIManager;
use NYPL\Refinery\Cache\CacheData;

class CacheDataTest extends \PHPUnit_Framework_TestCase
{
    public static function tearDownAfterClass()
    {
        DIManager::resetContainer();
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testNoKeySet()
    {
        $key = null;
        $uri = 'http://dev.www.aws.nypl.org/refinery/api/v0_1/content/blogpostgroup';

        \Mockery::mock('\NYPL\Refinery\Cache\CacheData', array($key, $uri));
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testImproperURISet()
    {
        $key = 'Key';
        $uri = 'invalid_uri';

        \Mockery::mock('NYPL\Refinery\Cache\CacheData', array($key, $uri));
    }

    public function testDataRead()
    {
        $key = 'Key';
        $uri = 'http://dev.www.aws.nypl.org/refinery/api/v0_1/content/blogpostgroup';

        $data = null;

        $cachedData = array(
            'data' => $data,
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
            ->shouldReceive('get')->andReturn($cachedData)->getMock();
        DIManager::set('CacheClient', $cacheClient);

        /**
         * @var MockInterface|CacheData $mockedCacheData
         */
        $mockedCacheData = \Mockery::mock('NYPL\Refinery\Cache\CacheData', array($key, $uri));
        $mockedCacheData->shouldDeferMissing();

        $this->assertSame($mockedCacheData->getData(), $data);
    }
}
