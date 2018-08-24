<?php

namespace NYPL\Refinery;


use NYPL\Refinery\NDO\Event\Exhibition;

class ExhibitionTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @covers \NYPL\Refinery\NDO\Event\Exhibition::setSupportedProviders
   */
  public function testSetSupportedProviders()
  {
    $exhibition = new Exhibition();
    $providerArray = $exhibition->getSupportedReadProviders();
    $this->assertNotEmpty($providerArray);
  }
}
