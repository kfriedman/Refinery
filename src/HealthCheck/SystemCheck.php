<?php
namespace NYPL\Refinery\HealthCheck;

use NYPL\Refinery\Config\Config;
use NYPL\Refinery\HealthCheck;

/**
 * System health test
 *
 * @package NYPL\Refinery
 */
class SystemCheck extends HealthCheck
{
    /**
     * Run basic health tests on the system
     *
     * @return bool
     */
    public function runCheck()
    {
        $freeSpace = disk_free_space('/');
        $freeSpaceRequired = Config::getItem('HealthTests.SystemTest.MinimumDiskSpaceBytes', null, true);

        if (disk_free_space('/') > $freeSpaceRequired) {
            $this->setSuccess('Free disk space (' . number_format($freeSpace) . ' bytes) within acceptable range (' . number_format($freeSpaceRequired) . ' bytes)');
        } else {
            $this->setFailure('Insufficient free disk space (' . number_format($freeSpace) . ' bytes) exceeds acceptable range (' . number_format($freeSpaceRequired) . ' bytes)');
        }

        $loadArray = sys_getloadavg();
        $loadAverage = (int) (array_sum($loadArray) / count($loadArray));
        $maximumLoadAverage = (float) Config::getItem('HealthTests.SystemTest.MaximumLoadPercent', null, true);

        if ($loadAverage < $maximumLoadAverage) {
            $this->setSuccess('Average system load (' . number_format($loadAverage) . '%) within acceptable range (' . number_format($maximumLoadAverage) . '%)');
        } else {
            $this->setFailure('Average system load (' . number_format($loadAverage) . '%) exceeds acceptable range (' . number_format($maximumLoadAverage) . '%)');
        }

        return $this->getSucceeded();
    }
}