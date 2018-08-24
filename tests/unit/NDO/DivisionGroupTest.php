<?php


namespace NYPL\Refinery;


use NYPL\Refinery\NDO\DivisionGroup;

class DivisionGroupTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var DivisionGroup
   */
  private $divisionGroup;

  public function setUp()
  {
    $this->divisionGroup = new DivisionGroup();
  }

  /**
   * @covers \NYPL\Refinery\NDO\DivisionGroup::setSupportedProviders
   */
  public function testSetSupportedProviders()
  {
    $providerArray = $this->divisionGroup->getSupportedReadProviders();
    $this->assertNotEmpty($providerArray);
  }
}
