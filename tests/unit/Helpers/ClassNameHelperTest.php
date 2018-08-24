<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\Helpers\ClassNameHelper;
use NYPL\Refinery\Tests\Fixtures\FakeExtendedClass;

class ClassNameHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testNotPassingObjectFails()
    {
        ClassNameHelper::getNameWithoutNamespace('Test');
    }

    public function testObjectWithoutNamespaceReturnsCorrectName()
    {
        $mockObject = \Mockery::namedMock('FakeName');
        $this->assertSame('FakeName', ClassNameHelper::getNameWithoutNamespace($mockObject));
    }

    public function testObjectWithNamespaceReturnsCorrectName()
    {
        $mockObject = \Mockery::namedMock('FakeNamespace\FakeName');
        $this->assertSame('FakeName', ClassNameHelper::getNameWithoutNamespace($mockObject));
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testNotPassingObjectFailsForParent()
    {
        ClassNameHelper::getParentNameWithoutNamespace('Test');
    }

    public function testObjectWithParentReturnsCorrectName()
    {
        $fakeExtendedClass = new FakeExtendedClass();
        $this->assertSame('FakeClass', ClassNameHelper::getParentNameWithoutNamespace($fakeExtendedClass));
        $this->assertNotEquals('FakeExtendedClass', ClassNameHelper::getParentNameWithoutNamespace($fakeExtendedClass));
    }
}
