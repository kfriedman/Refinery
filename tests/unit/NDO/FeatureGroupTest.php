<?php


namespace NYPL\Refinery;


use NYPL\Refinery\NDO\FeatureGroup;

class FeatureGroupTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @covers \NYPL\Refinery\NDO\FeatureGroup::setSupportedProviders
   */
  public function testSetSupportedProviders()
  {
    $featureGroup = new FeatureGroup();
    $providerArray = $featureGroup->getSupportedReadProviders();
    $this->assertNotEmpty($providerArray);
  }
}
