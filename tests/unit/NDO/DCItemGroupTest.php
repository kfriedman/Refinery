<?php


namespace NYPL\Refinery;


use NYPL\Refinery\NDO\DCItemGroup;

class DCItemGroupTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var DCItemGroup
   */
  private $dcItemGroup;

  public function setUp()
  {
    $this->dcItemGroup = new DCItemGroup();
  }

  /**
   * @covers \NYPL\Refinery\NDO\DCItemGroup::setSupportedProviders
   */
  public function testSetSupportedProviders()
  {

    $providerArray = $this->dcItemGroup->getSupportedReadProviders();
    $this->assertNotEmpty($providerArray);
  }
}
