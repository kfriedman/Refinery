<?php
namespace NYPL\Refinery\HealthCheck;

use NYPL\Refinery\Config\Config;
use NYPL\Refinery\DIManager;
use NYPL\Refinery\HealthCheck;
use NYPL\Refinery\QueueManager;

/**
 * Queue server health test
 *
 * @package NYPL\Refinery
 */
class QueueCheck extends HealthCheck
{
    /**
     * Check to see if the queue server is running and run other health tests
     *
     * @return bool
     */
    public function runCheck()
    {
        $maximumQueueMessages = Config::getItem('HealthTests.QueueTest.MaximumQueueMessages', null, true);

        if (DIManager::getQueueManager()->isConnected()) {
            $this->setSuccess('Connection to queue server (' . QueueManager::getHost() . ') succeeded');

            $countMessages = QueueManager::getQueue()->declareQueue();

            if ($countMessages <= $maximumQueueMessages) {
                $this->setSuccess('Number of messages in queue (' . $countMessages . ') within allowable threshold (' . $maximumQueueMessages . ')');
            } else {
                $this->setFailure('Number of messages in queue (' . $countMessages . ') exceeds allowable threshold (' . $maximumQueueMessages . ')');
            }

            $response = $this->getSucceeded();
        } else {
            $response = $this->setFailure('Connection to queue server failed');
        }

        return $response;
    }
}
