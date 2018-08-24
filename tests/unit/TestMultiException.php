<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\Exception\RefineryException;

class TestMultiException
{
    /**
     * @var \Exception
     */
    private $expectedException;

    public function __destruct()
    {
        if ($this->expectedException) {
            throw new RefineryException('Multi exception was not cleared');
        }
    }

    public function expectException(\Exception $exception)
    {
        $this->expectedException = $exception;
    }

    public function clearException(\Exception $exception)
    {
        if (!$this->expectedException) {
            throw new RefineryException('Exception was not thrown');
        }

        if (!$this->expectedException instanceof $exception) {
            throw new RefineryException('Exception thrown does not match');
        }

        $this->expectedException = null;
    }

    public function done()
    {
        if ($this->expectedException) {
            throw new RefineryException('Previous exception was not expected');
        }
    }
}