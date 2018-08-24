<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\NDO\DCItem;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\ProviderHandler\NDOReader;

class ProviderHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testProviderDoesNotSupportNDO()
    {
        $provider = \Mockery::mock('\NYPL\Refinery\Provider\RESTAPI\D7RefineryServerCurrent')->shouldDeferMissing();
        $provider->setHost('testing.tld');
        $provider->setBaseURL('refinery/api/v0.1');

        NDOReader::readNDO($provider, new DCItem(), new NDOFilter());
    }
}
