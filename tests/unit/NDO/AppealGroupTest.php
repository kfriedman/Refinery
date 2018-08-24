<?php

namespace NYPL\Refinery;


use NYPL\Refinery\NDO\AppealGroup;

class AppealGroupTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var AppealGroup
   */
  private $appealGroup;

  public function setUp()
  {
    $this->appealGroup = new AppealGroup();
  }

  /**
   * @cover \NYPL\Refinery\NDO\AppealGroup::setSupportedProviders()
   */
  public function testSetSupportedProviders()
  {
    $providerArray = $this->appealGroup->getSupportedReadProviders();
    $this->assertNotEmpty($providerArray);
  }
}
