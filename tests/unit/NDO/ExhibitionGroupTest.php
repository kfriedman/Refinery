<?php


namespace NYPL\Refinery;


use NYPL\Refinery\NDO\ExhibitionGroup;

class ExhibitionGroupTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @covers \NYPL\Refinery\NDO\ExhibitionGroup::setSupportedProviders
   */
  public function testSetSupportedProviders()
  {
    $exhibitionGroup = new ExhibitionGroup();
    $providerArray = $exhibitionGroup->getSupportedReadProviders();
    $this->assertNotEmpty($providerArray);
  }
}
