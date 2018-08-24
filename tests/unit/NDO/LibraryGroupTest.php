<?php


namespace NYPL\Refinery;


use NYPL\Refinery\NDO\LibraryGroup;

class LibraryGroupTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @covers \NYPL\Refinery\NDO\LibraryGroup::setSupportedProviders
   */
  public function testSetSupportedProviders()
  {
    $libraryGroup = new LibraryGroup();
    $providerArray = $libraryGroup->getSupportedReadProviders();
    $this->assertNotEmpty($providerArray);
  }
}
