<?php
namespace NYPL\Refinery\HealthCheck;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use NYPL\Refinery\Config\Config;
use NYPL\Refinery\HealthCheck;

/**
 * Queue worker health test
 *
 * @package NYPL\Refinery
 */
class QueueWorkersCheck extends HealthCheck
{
    /**
     * Check to see if the queue server is running and run other health tests
     *
     * @return bool
     */
    public function runCheck()
    {
        $client = new Client();

        $url = 'http://' . Config::getItem('Queue.Host') . ':15672/api/queues/' . urlencode(Config::getItem('Queue.Vhost'));

        try {
            $response = $client->get(
                $url,
                array('auth' =>  array(Config::getItem('Queue.User'), Config::getItem('Queue.Password')))
            );
        } catch (RequestException $exception) {
            $response = $this->setFailure($exception->getMessage());
        }

        $queues = json_decode($response->getBody(), true);

        $queueName = Config::getItem('Queue.Name'). 'Queue';
        $queueDelayedName = Config::getItem('Queue.Name'). 'QueueDelayed';

        $queueFound = false;

        foreach ($queues as $queue) {
            if (strpos($queue['name'], $queueName) !== false && strpos($queue['name'], $queueDelayedName) === false) {
                if ($queue['consumers']) {
                    $response = $this->setSuccess($queue['consumers'] . ' consumer(s) found on ' . $queue['name']);
                } else {
                    $response = $this->setFailure('No queue workers found on ' . $queue['name']);
                }

                $queueFound = true;
            }
        }

        if (!$queueFound) {
            $response = $this->setFailure('No queue was found on ' . $url);
        }

        return $response;
    }
}
