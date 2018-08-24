<?php
namespace NYPL\Refinery\QueueMessage;

use NYPL\Refinery\Config\Config;
use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\QueueManager;
use NYPL\Refinery\QueueMessage;
use NYPL\Refinery\Server;

/**
 * The InitializeCache QueueMessage used to seed the cache for the Refinery
 * Server.
 *
 * @package NYPL\Refinery\QueueMessage
 */
class InitializeCache extends QueueMessage
{
    /**
     * @var string
     */
    protected $uri = '';

    /**
     * @var int
     */
    protected $refreshMinutes = 0;

    /**
     * @param string $message
     * @param string $uri
     * @param int    $refreshMinutes
     */
    public function __construct($message = '', $uri = '', $refreshMinutes = 0)
    {
        if ($message) {
            $this->setMessageProperties(stripslashes($message));
        }

        if ($uri) {
            $this->setUri($uri);
        }

        if ($refreshMinutes) {
            $this->setRefreshMinutes($refreshMinutes);
        }
    }

    /**
     * Getter for the message.
     *
     * @return string
     */
    public function getMessage()
    {
        return addslashes(serialize(get_object_vars($this)));
    }

    /**
     * Process the message. Have the Refinery Server parse the request,
     * delete the InitializeCache message, and then set a retry based on the
     * configuration file.
     *
     * @return mixed
     */
    public function processMessage()
    {
        Server::setRefreshIfStale(true);
        Server::setForceRefresh(false);

        try {
            $urlComponents = parse_url($this->getUri());

            if (isset($urlComponents['query'])) {
                parse_str($urlComponents['query'], $queryParameters);
            } else {
                $queryParameters = array();
            }

            Server::processRequest(explode('/', $urlComponents['path']), 'GET', $queryParameters);
        } catch (\Exception $exception) {
            $exception = new RefineryException('Unable to process message (' . $exception->getMessage() . ')');

            QueueManager::outputMessage($exception->getMessage(), true);
        }

        $this->getQueueRecord()->delete();

        if ($this->getRefreshMinutes()) {
            $delaySeconds = (int) $this->getRefreshMinutes() * 60;
        } else {
            $refreshInterval = new \DateInterval(Config::getItem('Cache.FreshInterval'));
            $delaySeconds = (int) $refreshInterval->format('%i') * 60;
        }

        QueueManager::add($this, true, $delaySeconds);
    }

    /**
     * Getter for the seed URI.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Setter for the seed URI. Does validation to make sure URI is in the
     * proper format.
     *
     * @param string $uri
     *
     * @throws RefineryException
     */
    public function setUri($uri = '')
    {
        if (strstr($uri, 'http')) {
            throw new RefineryException('URI should not contain protocol (e.g. http://)');
        }
        if (substr($uri, 0, 1) == '/') {
            throw new RefineryException('URI should not begin with a slash');
        }

        $this->uri = $uri;
    }

    /**
     * @return int
     */
    public function getRefreshMinutes()
    {
        return $this->refreshMinutes;
    }

    /**
     * @param int $refreshMinutes
     */
    public function setRefreshMinutes($refreshMinutes)
    {
        $this->refreshMinutes = (int) $refreshMinutes;
    }
}
