<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\HealthCheck\ProviderCheck;

class ProviderCheckTest extends \PHPUnit_Framework_TestCase
{
    public function testIfProviderCheckFails()
    {
        $healthCheckResponse = \Mockery::mock();
        $healthCheckResponse
            ->shouldReceive('isHealthy')->andReturn(false)->getMock()
            ->shouldIgnoreMissing();

        $mockProvider = \Mockery::mock('\NYPL\Refinery\Provider\RESTAPI\D7RefineryServerCurrent');
        $mockProvider
            ->shouldReceive('isHealthy')->andReturn(false)->getMock()
            ->shouldReceive('checkHealth')->andReturn($healthCheckResponse);
        
        $providerCheck = new ProviderCheck($mockProvider);
        $response = $providerCheck->runCheck();

        self::assertFalse($response);
    }

    public function testIfProviderCheckSucceeds()
    {
        $healthCheckResponse = \Mockery::mock();
        $healthCheckResponse
            ->shouldReceive('isHealthy')->andReturn(true)->getMock()
            ->shouldIgnoreMissing();

        $mockProvider = \Mockery::mock('\NYPL\Refinery\Provider\RESTAPI\D7RefineryServerCurrent');
        $mockProvider
            ->shouldReceive('isHealthy')->andReturn(true)->getMock()
            ->shouldReceive('checkHealth')->andReturn($healthCheckResponse);

        $providerCheck = new ProviderCheck($mockProvider);
        $response = $providerCheck->runCheck();

        self::assertTrue($response);
    }
}
?>