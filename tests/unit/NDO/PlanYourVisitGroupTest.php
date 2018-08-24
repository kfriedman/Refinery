<?php

namespace NYPL\Refinery;


use NYPL\Refinery\NDO\PlanYourVisitGroup;

class PlanYourVisitGroupTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @covers \NYPL\Refinery\NDO\PlanYourVisitGroup::setSupportedProviders
   */
  public function testSetSupportedProviders()
  {
    $planYourVisitGroup = new PlanYourVisitGroup();
    $providerArray = $planYourVisitGroup->getSupportedReadProviders();
    $this->assertNotEmpty($providerArray);
  }
}
