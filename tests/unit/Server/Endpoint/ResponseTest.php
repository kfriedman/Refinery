<?php
namespace NYPL\Refinery;

use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\Server\Endpoint\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testSettersAndGetters()
    {
        $response = new Response();

        $debugArray = array('data');
        $response->setDebugArray($debugArray);
        $this->assertSame($response->getDebugArray(), array($debugArray));

        $data = new \StdClass();
        $data->key = 'value';
        $response->setData($data);
        $this->assertSame($response->getData(), (array) $data);
    }
}