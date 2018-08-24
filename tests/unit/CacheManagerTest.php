<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\CacheManager;
use NYPL\Refinery\DIManager;

class CacheManagerTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        $config = \Mockery::mock('Config');
        $config
            ->shouldReceive('getItem')->withArgs(array('Cache.Enabled'))->andReturn(true)->getMock()
            ->shouldReceive('getItem')->withArgs(array('Cache.Host'))->andReturn('host')->getMock()
            ->shouldReceive('getItem')->withArgs(array('Cache.HostMaster'))->andReturn('host')->getMock()
            ->shouldReceive('getItem')->withArgs(array('Cache.Prefix'))->andReturn('host')->getMock()
            ->shouldReceive('getItem')->withArgs(array('Queue.Enabled'))->andReturn(true)->getMock()

            ->shouldReceive('getItem')->withArgs(array('Queue.Host'))->andReturn('host')->getMock()
            ->shouldReceive('getItem')->withArgs(array('Queue.Vhost'))->andReturn('host')->getMock()
            ->shouldReceive('getItem')->withArgs(array('Queue.Port'))->andReturn(100)->getMock()
            ->shouldReceive('getItem')->withArgs(array('Queue.User'))->andReturn('user')->getMock()
            ->shouldReceive('getItem')->withArgs(array('Queue.Password'))->andReturn('user')->getMock()
            ->shouldReceive('getItem')->withArgs(array('Queue.Name'))->andReturn('queue')->getMock()

            ->shouldReceive('getItem')->withArgs(array('CacheSeed.URLS', \Mockery::any(), \Mockery::any()))->andReturn(array())->getMock()
            ->shouldReceive('getItem')->withArgs(array('Cache.ConfigFileURL'))->andReturn(null)->getMock()

            ->shouldReceive('checkExtension')->andReturn(true)->getMock();
        DIManager::set('Config', $config);

        $redis = \Mockery::mock('Redis');
        $redis->shouldIgnoreMissing();
        DIManager::set('Redis', $redis);

        $cacheClient = \Mockery::mock('CacheClient');
        $cacheClient->shouldIgnoreMissing();
        DIManager::set('CacheClient', $cacheClient);

        $queueManager = \Mockery::mock('QueueManager');
        $queueManager->shouldIgnoreMissing();
        DIManager::set('QueueManager', $queueManager);

        $ampqConnection = \Mockery::mock('AMQPConnection');
        $ampqConnection->shouldIgnoreMissing();
        DIManager::set('AMQPConnection', $ampqConnection);

        $amqpChannel = \Mockery::mock('AMQPChannel');
        $amqpChannel->shouldIgnoreMissing();
        DIManager::set('AMQPChannel', $amqpChannel);

        $amqpQueue = \Mockery::mock('AMQPQueue');
        $amqpQueue->shouldIgnoreMissing();
        DIManager::set('AMQPQueue', $amqpQueue);

        $ampqExchange = \Mockery::mock('AMQPExchange');
        $ampqExchange->shouldIgnoreMissing();
        DIManager::set('AMQPExchange', $ampqExchange);

        $ampqExchangeDelayed = \Mockery::mock('AMQPExchange');
        $ampqExchangeDelayed->shouldIgnoreMissing();
        DIManager::set('AMQPExchangeDelayed', $ampqExchangeDelayed);

        $amqpQueueDelayed = \Mockery::mock('AMQPQueue');
        $amqpQueueDelayed->shouldIgnoreMissing();
        DIManager::set('AMQPQueueDelayed', $amqpQueueDelayed);
    }

    public static function tearDownAfterClass()
    {
        DIManager::resetContainer();
    }

    public function testCacheManagerIsEnabled()
    {
        $manager = new CacheManager();
        $manager::initialize();

        self::assertTrue($manager->isEnabled());
    }
}
