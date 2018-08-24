<?php
namespace NYPL\Refinery\HealthCheck;

use NYPL\Refinery\Cache\CacheClient;
use NYPL\Refinery\HealthCheck;

/**
 * Cache health test
 *
 * @package NYPL\Refinery
 */
class MasterCacheCheck extends HealthCheck
{
    /**
     * Check to see if the cache is responding correctly.
     *
     * @return bool
     */
    public function runCheck()
    {
        if (CacheClient::isSlave()) {
            if (CacheClient::ping(true) == '+PONG') {
                $this->setSuccess('Connection to master cache server (' . CacheClient::getHostMaster() . ') succeeded');
            } else {
                $this->setFailure('Connection to master cache server (' . CacheClient::getHostMaster() . ') failed');
            }
        } else {
            $this->setSuccess('Server is master');
        }

        return $this->getSucceeded();
    }
}
