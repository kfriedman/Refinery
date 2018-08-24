<?php

namespace NYPL\Refinery;


use NYPL\Refinery\NDO\SubjectOtherGroup;

class SubjectGroupTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @covers \NYPL\Refinery\NDO\SubjectGroup::setSupportedProviders
   */
  public function testSetSupportedProviders()
  {
    $subjectGroup = new SubjectOtherGroup();
    $providerArray = $subjectGroup->getSupportedReadProviders();
    $this->assertNotEmpty($providerArray);
  }
}
