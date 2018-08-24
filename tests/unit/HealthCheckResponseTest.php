<?php
namespace NYPL\Refinery;

class HealthCheckResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $isHealthy = true;
        $message = 'Message';

        $healthCheckResponse = new HealthCheckResponse($isHealthy, $message);

        $this->assertSame($isHealthy, $healthCheckResponse->isHealthy());
        $this->assertSame($message, $healthCheckResponse->getMessage());
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testHealthyMustBeBoolean()
    {
        $healthCheckResponse = new HealthCheckResponse();

        $healthCheckResponse->setHealthy('true');
    }
}
