<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\NDO\AlertGroup;

class AlertGroupTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @covers \NYPL\Refinery\NDO\AlertGroup::setSupportedProviders
   */
  public function testSetSupportedProviders()
  {
    $alertGroup = new AlertGroup();
    $providerArray = $alertGroup->getSupportedReadProviders();
    $this->assertNotEmpty($providerArray);
  }
}
