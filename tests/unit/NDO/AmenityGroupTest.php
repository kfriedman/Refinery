<?php
namespace NYPL\Refinery;

use NYPL\Refinery\NDO\AmenityGroup;

class AmenityGroupTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @covers \NYPL\Refinery\NDO\AmenityGroup::setSupportedProviders
   */
  public function testSetSupportedProviders()
  {
    $amenityGroup = new AmenityGroup();
    $providerArray = $amenityGroup->getSupportedReadProviders();
    $this->assertNotEmpty($providerArray);
  }
}
