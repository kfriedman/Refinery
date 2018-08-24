<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\DIManager;
use NYPL\Refinery\HealthChecker;

class HealthCheckerTest // extends \PHPUnit_Framework_TestCase
{
    public function getMockedCachedResults($isExists = false)
    {
        $mockRawData = \Mockery::mock();
        $mockRawData->shouldIgnoreMissing();
        $mockRawData->shouldReceive('isExists')->andReturn(true);

        return $mockRawData;
    }

    public function testReturnsCachedResults()
    {

        DIManager::set('RawData', $this->getMockedCachedResults(true));

        $output = HealthChecker::run(true, true);

        $this->assertSame(true, $output['cached']);
    }

    public function testChecksReturnsOutput()
    {
        DIManager::set('RawData', $this->getMockedCachedResults(false));

        $output = HealthChecker::run(true, true);
    }
}