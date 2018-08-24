<?php
namespace NYPL\Refinery;

use NYPL\Refinery\Exception\RefineryException;

/**
 * Class used to manage interactions with the Queue Server using AMQP.
 *
 * @package NYPL\Refinery
 */
class QueueManager
{
    /**
     * The number of messages for consumers to prefetch.
     */
    const PREFETCH_MESSAGES = 1;

    /**
     * Whether the Queue Server is enabled or not.
     *
     * @var bool
     */
    protected static $enabled;

    /**
     * The AMQP connection object for the queue server.
     *
     * @var \AMQPConnection
     */
    protected static $connection;

    /**
     * The name of the primary exchange.
     *
     * @var string
     */
    protected static $exchangeName = '';

    /**
     * The name of the delayed exchange.
     *
     * @var string
     */
    protected static $exchangeNameDelayed = '';

    /**
     * The AMQP channel object used to connect to the queue server.
     *
     * @var \AMQPChannel
     */
    protected static $channel;

    /**
     * The AMQP exchange object for the primary exchange.
     *
     * @var \AMQPExchange
     */
    protected static $exchange;

    /**
     * The AMQP exchange object for the delayed exchange.
     *
     * @var \AMQPExchange
     */
    protected static $exchangeDelayed;

    /**
     * The AMQP queue object for the primary queue.
     *
     * @var \AMQPQueue
     */
    protected static $queue;

    /**
     * Hostname of the queue server.
     *
     * @var string
     */
    protected static $host = '';

    /**
     * Add a message to the queue.
     *
     * @param QueueMessage $queueMessage The QueueMessage to add.
     * @param bool         $forceQueue   Force queueing the message even if a QueueMessage exists.
     * @param int          $delaySeconds Delay the delivery of the message by given number of seconds.
     * @param int          $priority     The priority of the message.
     */
    public static function add(QueueMessage $queueMessage, $forceQueue = false, $delaySeconds = 0, $priority = 0)
    {
        if (self::isEnabled()) {
            if (!$queueMessage->isQueueRecordExists() || $forceQueue) {
                self::initialize();

                if ($delaySeconds) {
                    $exchange = self::getExchangeDelayed();
                    $routingKey = self::getQueueName($delaySeconds);
                } else {
                    $exchange = self::getExchange();
                    $routingKey = self::getQueueName();
                }

                $attributes = array('delivery_mode' => 2, 'type' => $queueMessage->getMessageType());

                if ($priority) {
                    $attributes['priority'] = $priority;
                }

                $isPublished = false;

                while (!$isPublished) {
                    try {
                        $exchange->publish($queueMessage->getMessage(), $routingKey, AMQP_MANDATORY, $attributes);
                        $isPublished = true;
                    } catch (\Exception $exception) {
                        self::reConnect($exception);
                    }
                }

                $queueMessage->saveQueueRecord();
            }
        }
    }

    /**
     * Connect to the the queue server and run setup.
     *
     * @throws RefineryException
     */
    protected static function initialize()
    {
        if (self::isEnabled()) {
            DIManager::getConfig()->checkExtension('Queue');
        }

        if (!self::$connection) {
            /**
             * @var \AMQPConnection $amqpConnection;
             */
            $amqpConnection = DIManager::get('AMQPConnection', array('credentials' => array(
                'host' => self::getHost(),
                'vhost' => DIManager::getConfig()->getItem('Queue.Vhost'),
                'port' => DIManager::getConfig()->getItem('Queue.Port'),
                'login' => DIManager::getConfig()->getItem('Queue.User'),
                'password' => DIManager::getConfig()->getItem('Queue.Password')
            )), true);

            self::setConnection($amqpConnection);

            self::getConnection()->connect();

            self::setUp();
        }
    }

    /**
     * Setup the channel, exchange, and queue.
     *
     * @throws RefineryException
     */
    private static function setUp()
    {
        /**
         * @var \AMQPChannel $amqpChannel
         */
        $amqpChannel = DIManager::get('AMQPChannel', array(
            'amqp_connection' => self::getConnection()
        ));
        self::setChannel($amqpChannel);
        self::getChannel()->qos(false, self::PREFETCH_MESSAGES);

        // Exchange & Queue

        /**
         * @var \AMQPExchange $amqpExchange
         */
        $amqpExchange = DIManager::get('AMQPExchange', array(
            'amqp_channel' => self::getChannel()
        ));
        self::setExchange($amqpExchange);

        self::getExchange()->setName(self::getExchangeName());
        self::getExchange()->setType(AMQP_EX_TYPE_DIRECT);
        self::getExchange()->declareExchange();

        /**
         * @var \AMQPQueue $amqpQueue
         */
        $amqpQueue = DIManager::get('AMQPQueue', array(
            'amqp_channel' => self::getChannel()
        ));
        self::setQueue($amqpQueue);

        self::getQueue()->setName(self::getQueueName());
        self::getQueue()->setFlags(AMQP_DURABLE);
        self::getQueue()->setArgument('x-max-priority', 255);
        self::getQueue()->declareQueue();

        self::getQueue()->bind(self::getExchangeName(), self::getQueueName());

        // Delayed Exchange & Queue
        /**
         * @var \AMQPExchange $amqpExchangeDelayed
         */
        $amqpExchangeDelayed = DIManager::get('AMQPExchangeDelayed', array(
            'amqp_channel' => self::getChannel()
        ));
        self::setExchangeDelayed($amqpExchangeDelayed);

        self::getExchangeDelayed()->setName(self::getExchangeNameDelayed());
        self::getExchangeDelayed()->setType(AMQP_EX_TYPE_DIRECT);
        self::getExchangeDelayed()->declareExchange();
    }

    /**
     * Create a consumer to process messages in the queue.
     *
     * This will create a queue "listener" that will remain listening until
     * canceled by the user. Queue messages are processed with the
     * corresponding processMessage() method in QueueMessage.
     */
    public static function processQueue()
    {
        if (!self::isEnabled()) {
            self::outputMessage('Queue Server is currently disabled', true);
        } else {
            self::initialize();

            self::outputMessage(' [*] Waiting for messages. To exit press CTRL+C');

            while (true) {
                try {
                    $count = 0;

                    self::getQueue()->consume(function ($message) use (&$count) {
                        ++$count;

                        /**
                         * @var $message \AMQPEnvelope
                         */
                        self::outputMessage($count . '. Received "' . $message->getType() . '" with "' .
                            $message->getBody() . '" for "' . $message->getRoutingKey() . '" (' . date('r') . ")");

                        if (!$message->getType()) {
                            self::outputMessage('Error: Message type not defined');
                        } else {
                            $pipes = array();

                            $process = proc_open(
                                'php -d error_log=/var/log/php_errors.log ' . __DIR__ . '/worker.php ' .
                                    escapeshellarg($message->getType()) . ' ' .
                                    escapeshellarg($message->getBody()),
                                array(),
                                $pipes
                            );

                            proc_close($process);

                            self::outputMessage('Done' . ' (' . date('r') . ')');
                        }

                        self::getQueue()->ack($message->getDeliveryTag());
                    });
                } catch (\Exception $exception) {
                    self::reConnect($exception);
                }
            }
        }
    }

    /**
     * @param \Exception $exception
     *
     * @throws RefineryException
     */
    protected static function reConnect(\Exception $exception = null)
    {
        if ($exception) {
            $addedMessage = ' (' . $exception->getMessage() . ')';
        } else {
            $addedMessage = null;
        }

        $reconnectSeconds = DIManager::getConfig()->getItem('Queue.ReconnectSeconds', null, true);
        self::outputMessage('Reconnecting to queue server ' . self::getHost() . ' in ' . $reconnectSeconds . ' seconds' . $addedMessage);
        sleep($reconnectSeconds);

        try {
            self::$connection = null;

            self::initialize();
        } catch (\Exception $exception) {
            self::outputMessage('Reconnecting to queue server ' . self::getHost() . ' failed (' . $exception->getMessage() . ')');
        }
    }

    /**
     * Given the type of the message, instantiate the corresponding QueueMessage
     * object constructed with the body of the message.
     *
     * @param string $messageType
     * @param string $message
     *
     * @return QueueMessage
     * @throws RefineryException
     */
    public static function getQueueMessageClass($messageType = '', $message = '')
    {
        $fullClassName = '\\NYPL\\Refinery\\QueueMessage\\' . $messageType;

        if (!class_exists($fullClassName)) {
            throw new RefineryException('QueueMessage class (' . $fullClassName . ') does not exist');
        }

        return new $fullClassName($message);
    }

    /**
     * Getter for the AMQP connection object.
     *
     * @return \AMQPConnection
     */
    protected static function getConnection()
    {
        return self::$connection;
    }

    /**
     * Setter for the AMQP connection object.
     *
     * @param \AMQPConnection $connection
     */
    protected static function setConnection(\AMQPConnection $connection)
    {
        self::$connection = $connection;
    }

    /**
     * @param int $delaySeconds
     *
     * @return string
     * @throws RefineryException
     */
    protected static function getQueueName($delaySeconds = 0)
    {
        if ($delaySeconds) {
            $delayMinutes = $delaySeconds / 60;

            return DIManager::getConfig()->getItem('Queue.Name') . 'QueueDelayed-' . $delayMinutes;
        } else {
            return DIManager::getConfig()->getItem('Queue.Name') . 'Queue';
        }
    }

    /**
     * Getter for the primary exchange name. If no exchange name is set, initialize with
     * name specified in the environment config file.
     *
     * @return string
     */
    protected static function getExchangeName()
    {
        if (!self::$exchangeName) {
            self::setExchangeName(DIManager::getConfig()->getItem('Queue.Name') . 'Exchange');
        }

        return self::$exchangeName;
    }

    /**
     * Setter for the primary exchange name.
     *
     * @param string $exchangeName
     */
    protected static function setExchangeName($exchangeName)
    {
        self::$exchangeName = $exchangeName;
    }

    /**
     * Getter for the primary AMQP channel object.
     *
     * @return \AMQPChannel
     */
    protected static function getChannel()
    {
        return self::$channel;
    }

    /**
     * Setter for the primary AMQP channel object.
     *
     * @param \AMQPChannel $channel
     */
    protected static function setChannel(\AMQPChannel $channel)
    {
        self::$channel = $channel;
    }

    /**
     * Getter for the primary AMQP exchange object.
     *
     * @return \AMQPExchange
     */
    protected static function getExchange()
    {
        return self::$exchange;
    }

    /**
     * Setter for the primary AMQP exchange object.
     *
     * @param \AMQPExchange $exchange
     */
    protected static function setExchange(\AMQPExchange $exchange)
    {
        self::$exchange = $exchange;
    }

    /**
     * Getter for the primary AMQP queue object. If queueing is enabled in the
     * environment, initialize the queue. Otherwise, return null.
     *
     * @return \AMQPQueue
     */
    public static function getQueue()
    {
        if (self::isEnabled()) {
            self::initialize();
        }

        return self::$queue;
    }

    /**
     * Setter for the primary AMQP queue object.
     *
     * @param \AMQPQueue $queue
     */
    protected static function setQueue(\AMQPQueue $queue)
    {
        self::$queue = $queue;
    }

    /**
     * Getter for the delayed AMQP exchange object.
     *
     * @return \AMQPExchange
     */
    protected static function getExchangeDelayed()
    {
        return self::$exchangeDelayed;
    }

    /**
     * Setter for the delayed AMQP exchange object.
     *
     * @param \AMQPExchange $exchangeDelayed
     */
    protected static function setExchangeDelayed(\AMQPExchange $exchangeDelayed)
    {
        self::$exchangeDelayed = $exchangeDelayed;
    }

    /**
     * Getter for the name of the delayed exchange. If no name is set, initialize with
     * name specified in the environment config file.
     *
     * @return string
     */
    protected static function getExchangeNameDelayed()
    {
        if (!self::$exchangeNameDelayed) {
            self::setExchangeNameDelayed(DIManager::getConfig()->getItem('Queue.Name') . 'ExchangeDelayed');
        }

        return self::$exchangeNameDelayed;
    }

    /**
     * Setter for the name of the delayed exchange.
     *
     * @param string $exchangeNameDelayed
     */
    protected static function setExchangeNameDelayed($exchangeNameDelayed)
    {
        self::$exchangeNameDelayed = $exchangeNameDelayed;
    }

    /**
     * Check if queueing is enabled. If the enabled parameter is set to null,
     * get the enabled parameter from the environment config file.
     *
     * @return boolean
     */
    protected static function isEnabled()
    {
        if (self::$enabled === null) {
            self::setEnabled((bool) DIManager::getConfig()->getItem('Queue.Enabled'));
        }

        return self::$enabled;
    }

    /**
     * Setter for the queue enabled setting.
     *
     * @param boolean $enabled
     */
    protected static function setEnabled($enabled)
    {
        self::$enabled = $enabled;
    }

    /**
     * Clear all messages from the specified queue.
     *
     * @param \AMQPQueue $queue
     */
    public static function clearQueue(\AMQPQueue $queue)
    {
        $queue->purge();
    }

    /**
     * If server is configured to output messages, output a message.
     *
     * @param string $message     Message to output.
     * @param bool   $forceOutput Force the outputting of the missing regardless of configuration.
     */
    public static function outputMessage($message = '', $forceOutput = false)
    {
        if (DIManager::getConfig()->getItem('Queue.OutputMessages') || $forceOutput) {
            echo $message . "\n";
        }
    }

    /**
     * Check whether the connection to the AMQP broker is still valid.
     *
     * @return bool
     */
    public static function isConnected()
    {
        self::initialize();

        return self::getConnection()->isConnected();
    }

    /**
     * Getter for hostname of the queue server.
     *
     * @return string
     */
    public static function getHost()
    {
        if (!self::$host) {
            self::setHost(DIManager::getConfig()->getItem('Queue.Host'));
        }

        return self::$host;
    }

    /**
     * Setter for hostname of the queue server.
     *
     * @param string $host
     */
    public static function setHost($host)
    {
        self::$host = $host;
    }

    public function setupDelayedQueue($delayMinutes = 0)
    {
        $delaySeconds = $delayMinutes * 60;

        $queueName = self::getQueueName($delaySeconds);

        /**
         * @var \AMQPQueue $amqpQueue
         */
        $amqpQueue = DIManager::get(
            'AMQPQueue',
            array('amqp_channel' => self::getChannel()),
            true
        );

        $amqpQueue->setName($queueName);
        $amqpQueue->setFlags(AMQP_DURABLE);
        $amqpQueue->setArgument('x-max-priority', 255);
        $amqpQueue->setArgument('x-message-ttl', $delaySeconds * 1000);
        $amqpQueue->setArgument('x-dead-letter-exchange', self::getExchangeName());
        $amqpQueue->setArgument('x-dead-letter-routing-key', self::getQueueName());
        $amqpQueue->declareQueue();

        $amqpQueue->bind(self::getExchangeNameDelayed(), $queueName);

        self::clearQueue($amqpQueue);
    }
}
