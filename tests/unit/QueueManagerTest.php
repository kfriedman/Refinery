<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\DIManager;
use NYPL\Refinery\QueueManager;
use NYPL\Refinery\Config\Config;

class QueueManagerTest // extends \PHPUnit_Framework_TestCase
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

        $ampqConnection = \Mockery::mock('AMQPConnection');
        $ampqConnection->shouldReceive('isConnected')->andReturn(true);
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

    public function testIfQueueManagerIsConnected()
    {
        $qManager = new QueueManager();
        $response = $qManager->isConnected();
        
        self::assertTrue($response);
    }

}
?>