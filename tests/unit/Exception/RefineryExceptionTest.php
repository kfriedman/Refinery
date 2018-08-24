<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\Exception\RefineryException;

class RefineryExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorSetsProperties()
    {
        $message = 'Message';
        $addedMessage = 'Added Message';

        $refineryException = new RefineryException($message, 200, $addedMessage);

        $this->assertSame($refineryException->getMessage(), 'Message');
        $this->assertSame($refineryException->getAddedMessage(), $addedMessage);
        $this->assertSame($refineryException->getStatusCode(), 200);
    }
}
