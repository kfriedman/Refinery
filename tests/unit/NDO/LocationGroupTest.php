<?php

namespace NYPL\Refinery;


use NYPL\Refinery\NDO\LocationGroup;

class LocationGroupTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @covers \NYPL\Refinery\NDO\LocationGroup::setSupportedProviders
   */
  public function testSetSupportedProviders()
  {
    $locationGroup = new LocationGroup();
    $providerArray = $locationGroup->getSupportedReadProviders();
    $this->assertNotEmpty($providerArray);
  }
}
