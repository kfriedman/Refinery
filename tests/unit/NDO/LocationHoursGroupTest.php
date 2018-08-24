<?php

namespace NYPL\Refinery;


use NYPL\Refinery\NDO\LocationHoursGroup;

class LocationHoursGroupTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @covers \NYPL\Refinery\NDO\LocationHoursGroup::setSupportedProviders
   */
  public function testSetSupportedProviders()
  {
    $locationHoursGroup = new LocationHoursGroup();
    $providerArray = $locationHoursGroup->getSupportedReadProviders();
    $this->assertNotEmpty($providerArray);
  }
}
