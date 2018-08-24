<?php
namespace NYPL\Refinery\Tests;

use Mockery\MockInterface;

class D7RefineryServerCurrentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MockInterface $provider
     */
    public $provider;

    public function setUp()
    {
        $this->provider = \Mockery::mock('\NYPL\Refinery\Provider\RESTAPI\D7RefineryServerCurrent')->shouldDeferMissing();
    }

    public function testBaseParams()
    {
        $this->provider->shouldReceive('isHealthy')->andReturn(false)->byDefault();

        $this->assertSame(false, $this->provider->isHealthy());

        $this->provider->shouldReceive('isHealthy')->andReturn(true)->byDefault();

        $this->assertSame(true, $this->provider->isHealthy());
    }

    public function testStatusCode()
    {
        $this->provider->shouldReceive('getStatusCode')->andReturn(200);

        $this->assertSame(200, $this->provider->getStatusCode());
    }

    public function testProviderMetaData()
    {
       $this->assertSame(array('host' => ''), $this->provider->getProviderMetaData());
    }
}
?>