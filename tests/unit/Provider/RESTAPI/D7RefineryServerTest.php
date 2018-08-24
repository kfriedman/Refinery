<?php
namespace NYPL\Refinery\Tests;

class D7RefineryServerTest
{
    public $provider;

    public function setUp()
    {
        $this->provider = \Mockery::mock('\NYPL\Refinery\Provider\RESTAPI\D7RefineryServer')->shouldDeferMissing();
    }
}
?>