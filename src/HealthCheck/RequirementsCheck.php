<?php
namespace NYPL\Refinery\HealthCheck;

use NYPL\Refinery\Config\Config;
use NYPL\Refinery\HealthCheck;

/**
 * Requirements test
 *
 * @package NYPL\Refinery
 */
class RequirementsCheck extends HealthCheck
{
    /**
     * Check to see if the cache is responding correctly.
     *
     * @return bool
     */
    public function runCheck()
    {
        foreach (Config::getItem('Extensions', null, true) as $extensionName) {
            if (extension_loaded($extensionName)) {
                $this->setSuccess('Required extension (' . $extensionName . ') is loaded');
            } else {
                $this->setFailure('Required extension (' . $extensionName . ') is not loaded');
            }
        }

        return $this->getSucceeded();
    }
}