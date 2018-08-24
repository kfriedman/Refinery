<?php

namespace NYPL\Refinery;

use NYPL\Refinery\NDO\Content\Amenity\LocationAmenity;

class LocationAmenityTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var LocationAmenity
   */
  private $locationAmenity;

  public function setUp()
  {
    $this->locationAmenity = new LocationAmenity();
  }

  public function testGetLocationSortOrderByDefaultIsZero()
  {
    $this->assertSame(0, $this->locationAmenity->getLocationSortOrder());
  }

  public function testSetLocationSortOrderWillConvertToInteger()
  {
    $this->locationAmenity->setLocationSortOrder('123');
    $this->assertSame(123, $this->locationAmenity->getLocationSortOrder());
  }

  public function testSetLocationSortOrderWillConvertAnyNonIntegerToZero()
  {
    $this->locationAmenity->setLocationSortOrder('abc');
    $this->assertSame(0, $this->locationAmenity->getLocationSortOrder());
  }

  public function testGetAccessibilityNoteByDefaultIsBlank()
  {
    $this->assertSame('', $this->locationAmenity->getAccessibilityNote());
  }

  public function testSetAccessibilityNoteAcceptsString()
  {
    $accessNote = 'I will take any string value.';
    $this->locationAmenity->setAccessibilityNote($accessNote);
    $this->assertSame($accessNote, $this->locationAmenity->getAccessibilityNote());
  }

  public function testIsStaffAssistanceRequiredByDefaultIsFalse()
  {
    $this->assertFalse($this->locationAmenity->isStaffAssistanceRequired());
  }

  public function testSetStaffAssistanceRequiredAcceptsBoolean()
  {
    $this->locationAmenity->setStaffAssistanceRequired(true);
    $this->assertTrue($this->locationAmenity->isStaffAssistanceRequired());
  }

  public function testGetAccessibleByDefaultIsBlank()
  {
    $this->assertSame('', $this->locationAmenity->getAccessible());
  }

  public function testSetAccessibleAcceptsBoolean()
  {
    $this->locationAmenity->setAccessible(true);
    $this->assertTrue($this->locationAmenity->getAccessible());
  }

  public function testSetAccessibleAcceptsString()
  {
    $accessible = 'String value for $accessible';
    $this->locationAmenity->setAccessible($accessible);
    $this->assertSame($accessible, $this->locationAmenity->getAccessible());
  }
}
