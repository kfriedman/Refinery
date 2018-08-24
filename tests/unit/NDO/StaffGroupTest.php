<?php

namespace NYPL\Refinery;


use NYPL\Refinery\NDO\StaffGroup;

class StaffGroupTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @covers \NYPL\Refinery\NDO\StaffGroup::setSupportedProviders
   */
  public function testSetSupportedProviders()
  {
    $staffGroup = new StaffGroup();
    $providerArray = $staffGroup->getSupportedReadProviders();
    $this->assertNotEmpty($providerArray);
  }
}
