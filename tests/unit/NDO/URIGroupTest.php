<?php

namespace NYPL\Refinery;


use NYPL\Refinery\NDO\URIGroup;

class URIGroupTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @covers \NYPL\Refinery\NDO\URIGroup::setSupportedProviders
   */
  public function testSetSupportedProviders()
  {
    $uriGroup = new URIGroup();
    $providerArray = $uriGroup->getSupportedReadProviders();
    $this->assertNotEmpty($providerArray);
  }
}
