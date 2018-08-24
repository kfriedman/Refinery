<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\Cache\CacheClient;
use NYPL\Refinery\DIManager;

class CacheClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Mockery\MockInterface $mockRedis
     */
    protected $mockRedis;

    public function setUp()
    {
        DIManager::setTestingMode(true);

        $config = \Mockery::mock('Config');
        $config
            ->shouldReceive('getItem')->withArgs(array('Cache.Host'))->andReturn('host')->getMock()
            ->shouldReceive('getItem')->withArgs(array('Cache.Prefix'))->andReturn('Refinery:')->getMock()
            ->shouldReceive('getItem')->withArgs(array('Cache.HostMaster'))->andReturn(null)->getMock();
        DIManager::set('Config', $config);

        $this->mockRedis = \Mockery::mock('Redis');
        $this->mockRedis
            ->shouldReceive('setOption')->andReturn(true)->getMock()
            ->shouldReceive('close')->andReturn(true)->getMock()
            ->shouldReceive('slaveof')->andReturn(true)->getMock()
            ->shouldReceive('info')->andReturn(true)->getMock();
        DIManager::set('Redis', $this->mockRedis);
    }

    public function tearDown()
    {
        CacheClient::disconnect();

        DIManager::resetContainer();
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testSetThrowsErrorOnBadConnect()
    {
        $this->mockRedis->shouldReceive('pconnect')->andThrow('\RedisException');

        CacheClient::set('someKey', 'some data');
    }

    public function testInitializesCache()
    {
        $this->mockRedis->shouldReceive('pconnect');
        $this->mockRedis->shouldReceive('set')->andReturn(true);

        $response = CacheClient::set('someKey', 'some data');

        $this->assertSame(true, $response);
    }

    public function testGetFromCache()
    {
        $this->mockRedis->shouldReceive('pconnect');
        $this->mockRedis->shouldReceive('get')->with('exists')->andReturn('data');
        $this->mockRedis->shouldReceive('get')->with('does_not_exist')->andReturn(false);

        $this->assertSame('data', CacheClient::get('exists'));
        $this->assertSame(false, CacheClient::get('does_not_exist'));
    }

    public function testGetKeysWithoutRemovingPrefix()
    {
        $this->mockRedis->shouldReceive('pconnect');

        $keys = array('Refinery:Pattern:Key1', 'Refinery:Pattern:Key2');
        $pattern = 'Refinery:Pattern:';

        $this->mockRedis->shouldReceive('keys')->with($pattern)->andReturn($keys);

        $this->assertSame($keys, CacheClient::keys($pattern, true));
    }

    public function testGetKeysRemovingPrefix()
    {
        $this->mockRedis->shouldReceive('pconnect');

        $keys = array('Refinery:Pattern:Key1', 'Refinery:Pattern:Key2');
        $returnKeys = array('Pattern:Key1', 'Pattern:Key2');
        $pattern = 'Refinery:Pattern:';

        $this->mockRedis->shouldReceive('keys')->with($pattern)->andReturn($keys);

        $this->assertSame($returnKeys, CacheClient::keys($pattern));
    }

    public function testDeleteKeys()
    {
        $this->mockRedis->shouldReceive('pconnect');

        $this->mockRedis->shouldReceive('del')->with('3_keys_exist')->andReturn(3);
        $this->mockRedis->shouldReceive('del')->with('does_not_exist')->andReturn(false);

        $this->assertSame(3, CacheClient::del('3_keys_exist'));
        $this->assertSame(false, CacheClient::del('does_not_exist'));
    }

    public function testFlushAll()
    {
        $this->mockRedis->shouldReceive('pconnect');

        $this->mockRedis->shouldReceive('flushAll')->andReturn(true);

        $this->assertSame(true, CacheClient::flushAll());
    }

    public function testGoodPing()
    {
        $this->mockRedis->shouldReceive('pconnect');

        $pingResponse = '+PONG';

        $this->mockRedis->shouldReceive('ping')->andReturn($pingResponse);

        $this->assertSame($pingResponse, CacheClient::ping());
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testBadPing()
    {
        $this->mockRedis->shouldReceive('pconnect');

        $this->mockRedis->shouldReceive('ping')->andThrow('\RedisException');

        CacheClient::ping();
    }
}
