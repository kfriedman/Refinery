<?php

namespace NYPL\Refinery;

class SubjectTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var NDO\Term\SubjectOther
   */
  protected $subject;

  public function setUp()
  {
    $this->subject = new NDO\Term\SubjectOther();
  }

  /**
   * @covers \NYPL\Refinery\NDO\Term\Subject::setSupportedProviders
   */
  public function testSetSupportedProviders()
  {
    $providerArray = $this->subject->getSupportedReadProviders();
    $this->assertNotEmpty($providerArray);
  }

  public function testGetName()
  {
    $name = "A SubjectOther";
    $this->subject->setName($name);
    $this->assertSame($name, $this->subject->getName());
  }
}
