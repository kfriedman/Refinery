<?php

namespace NYPL\Refinery;


use NYPL\Refinery\NDO\MediaGroup;

class MediaGroupTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @covers \NYPL\Refinery\NDO\MediaGroup::setSupportedProviders
   */
  public function testSetSupportedProviders()
  {
    $mediaGroup = new MediaGroup();
    $providerArray = $mediaGroup->getSupportedReadProviders();
    $this->assertNotEmpty($providerArray);
  }
}
