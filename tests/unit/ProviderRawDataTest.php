<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\ProviderRawData;

class ProviderRawDataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testRawDataStringMustBeJSON()
    {
        $rawDataString = 'Not JSON';
        new ProviderRawData($rawDataString);
    }

    public function testGetterReturnsArray()
    {
        $rawData = '{ "Key": "Value" }';
        $provider = new ProviderRawData($rawData, '', array('this', 'that'), false, new NDOFilter());

        $this->assertArrayHasKey('Key', $provider->getRawDataArray());
    }

    public function testParsesAndReturnsArray()
    {
        $rawData = array('Key' => 'Value');
        $provider = new ProviderRawData($rawData, '', array('this', 'that'), false, new NDOFilter());

        $this->assertArrayHasKey('Key', $provider->getRawDataArray());
    }
}
