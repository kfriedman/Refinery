<?php
namespace NYPL\Refinery\Tests;

class HealthCheckTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HealthCheck $mockedHealthCheck
     */
    public $mockedHealthCheck;

    public function setUp() {
        $mockedConcrete = \Mockery::mock('\NYPL\Refinery\HealthCheck');
        $mockedConcrete->shouldDeferMissing();

        $this->mockedHealthCheck = $mockedConcrete;
    }

    public function testSetSucceededReturnsTrue()
    {
        $this->assertSame(true, $this->mockedHealthCheck->setSuccess());
    }

    public function testSetSucceededSetsSucceeded()
    {
        $this->mockedHealthCheck->setSuccess();

        $this->assertSame(true, $this->mockedHealthCheck->getSucceeded());
    }

    public function testSetSucceededSetsMessage()
    {
        $message = 'Message';

        $this->mockedHealthCheck->setSuccess('Message');

        $this->assertSame(current($this->mockedHealthCheck->getMessagesSucceeded()), $message);
    }

    public function testSetFailureReturnsFalse()
    {
        $this->assertSame(false, $this->mockedHealthCheck->setFailure());
    }

    public function testSetFailureSetsSucceeded()
    {
        $this->mockedHealthCheck->setFailure();

        $this->assertSame(false, $this->mockedHealthCheck->getSucceeded());
    }

    public function testSetFailureSetsMessage()
    {
        $message = 'Message';

        $this->mockedHealthCheck->setFailure('Message');

        $this->assertSame(current($this->mockedHealthCheck->getMessagesFailed()), $message);
    }

    public function testGetCheckNameSetsNameOfClass()
    {
        $nameOfClass = get_class($this->mockedHealthCheck);

        $this->assertSame($nameOfClass, $this->mockedHealthCheck->getCheckName());
    }
}
