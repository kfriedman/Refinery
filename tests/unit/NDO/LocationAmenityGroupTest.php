<?php


namespace NYPL\Refinery;


use NYPL\Refinery\NDO\LocationAmenityGroup;

class LocationAmenityGroupTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @covers \NYPL\Refinery\NDO\LocationAmenityGroup::setSupportedProviders
   */
  public function testSetSupportedProviders()
  {
    $libraryAmenityGroup = new LocationAmenityGroup();
    $providerArray = $libraryAmenityGroup->getSupportedReadProviders();
    $this->assertNotEmpty($providerArray);
  }
}
