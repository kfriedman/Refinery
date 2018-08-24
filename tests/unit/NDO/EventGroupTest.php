<?php


namespace NYPL\Refinery;


use NYPL\Refinery\NDO\EventGroup;

class EventGroupTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @covers \NYPL\Refinery\NDO\EventGroup::setSupportedProviders
   */
  public function testSetSupportedProviders()
  {
    $eventGroup = new EventGroup();
    $providerArray = $eventGroup->getSupportedReadProviders();
    $this->assertNotEmpty($providerArray);
  }
}
