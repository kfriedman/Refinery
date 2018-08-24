<?php
namespace NYPL\Refinery\QueueMessage;

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
class URLRefresh extends QueueMessage
{
    /**
     * @var string
     */
    protected $uri = '';

    /**
     * Constructor for the InitializeCache message. Sets the URI to seed.
     *
     * @param string $uri
     */
    public function __construct($uri = '')
    {
        if ($uri) {
            $this->setUri($uri);
        }
    }

    /**
     * Getter for the message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->getUri();
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
            $urlComponents = parse_url($this->getMessage());

            if (isset($urlComponents['query'])) {
                parse_str($urlComponents['query'], $queryParameters);
            } else {
                $queryParameters = array();
            }

            Server::processRequest(explode('/', $urlComponents['path']), 'GET', $queryParameters, true);
        } catch (\Exception $exception) {
            $exception = new RefineryException('Unable to process message (' . $exception->getMessage() . ')');

            QueueManager::outputMessage($exception->getMessage(), true);
        }
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
}
