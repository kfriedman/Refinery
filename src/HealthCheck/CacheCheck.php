<?php
namespace NYPL\Refinery\HealthCheck;

use NYPL\Refinery\Cache\CacheClient;
use NYPL\Refinery\HealthCheck;

/**
 * Cache health test
 *
 * @package NYPL\Refinery
 */
class CacheCheck extends HealthCheck
{
    /**
     * Check to see if the cache is responding correctly.
     *
     * @return bool
     */
    public function runCheck()
    {
        if (CacheClient::ping() == '+PONG') {
            $this->setSuccess('Connection to cache server (' . CacheClient::getHost() . ') succeeded');
        } else {
            $this->setFailure('Connection to cache server (' . CacheClient::getHost() . ') failed');
        }

        return $this->getSucceeded();
    }
}
