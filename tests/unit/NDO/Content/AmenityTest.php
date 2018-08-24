<?php

namespace NYPL\Refinery;

use NYPL\Refinery\NDO\Content\Amenity;
use NYPL\Refinery\NDO\URI;

class AmenityTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var Amenity
   */
  private $amenity;

  public function setUp()
  {
    $this->amenity = new Amenity();
  }

  /**
   * @cover \NYPL\Refinery\NDO\Content\Amenity::setSupportedProviders()
   */
  public function testSetSupportedProviders()
  {
    $providerArray = $this->amenity->getSupportedReadProviders();
    $this->assertNotEmpty($providerArray);
  }

  public function testGetAmenityIDByDefaultIsZero()
  {
    $this->assertSame(0, $this->amenity->getAmenityID());
  }

  public function testSetAmenityIDWillConvertToInt()
  {
    $stringValue = '123';
    $intValue = intval($stringValue);
    $this->amenity->setAmenityID($stringValue);
    $this->assertSame($intValue, $this->amenity->getAmenityID());
  }

  public function testSetAmenityIDWillConvertAnyNonIntegerToZero()
  {
    $stringValue = 'abc';
    $this->amenity->setAmenityID($stringValue);
    $this->assertSame(0, $this->amenity->getAmenityID());
  }

  public function testGetActionNameByDefaultIsBlank()
  {
    $this->assertSame('', $this->amenity->getActionName());
  }

  public function testSetActionNameAcceptsStringParameter()
  {
    $stringValue = 'Any string value';
    $this->amenity->setActionName($stringValue);
    $this->assertSame($stringValue, $this->amenity->getActionName());
  }

  public function testGetActionURIByDefaultIsNull()
  {
    $this->assertNull($this->amenity->getActionURI());
  }

  public function testSetActionURIAcceptsURIParameter()
  {
    $someURI = new URI('http://www.google.com');
    $this->amenity->setActionURI($someURI);
    $this->assertSame($someURI, $this->amenity->getActionURI());
  }

  public function testGetInfoURIByDefaultIsNull()
  {
    $this->assertNull($this->amenity->getInfoURI());
  }

  public function testSetInfoURIAcceptsURIParameter()
  {
    $someURI = new URI('http://www.yahoo.com');
    $this->amenity->setInfoURI($someURI);
    $this->assertSame($someURI, $this->amenity->getInfoURI());
  }

  /**
   * Test type hinting for URI
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetInfoURIThrowsExceptionOnNonURIParameter()
  {
    $someURI = 'Not an URI';
    $this->amenity->setInfoURI($someURI);
  }

  public function testGetSortOrderByDefaultIsZero()
  {
    $this->assertSame(0, $this->amenity->getSortOrder());
  }

  public function testSetSortOrderWillConvertToInt()
  {
    $sortOrder = '456';
    $intValue = intval($sortOrder);
    $this->amenity->setSortOrder($sortOrder);
    $this->assertSame($intValue, $this->amenity->getSortOrder());
  }

  public function testSetSortOrderWillConvertAnyNonIntegerToZero()
  {
    $sortOrder = '***';
    $this->amenity->setSortOrder($sortOrder);
    $this->assertSame(0, $this->amenity->getSortOrder());
  }

  public function testGetNameByDefaultIsBlank()
  {
    $this->assertSame('', $this->amenity->getName());
  }

  public function testSetNameAcceptsString()
  {
    $theName = 'I will take any string.';
    $this->amenity->setName($theName);
    $this->assertSame($theName, $this->amenity->getName());
  }

  public function testGetInfoLabelByDefaultIsBlank()
  {
    $this->assertSame('', $this->amenity->getInfoLabel());
  }

  public function testSetInfoLabelAcceptsString()
  {
    $infoLabel = 'I will take any string';
    $this->amenity->setInfoLabel($infoLabel);
    $this->assertSame($infoLabel, $this->amenity->getInfoLabel());
  }

  public function testGetParentNameByDefaultIsBlank()
  {
    $this->assertSame('', $this->amenity->getParentName());
  }

  public function testSetParentNameAcceptsString()
  {
    $parentName = 'I will take any string';
    $this->amenity->setParentName($parentName);
    $this->assertSame($parentName, $this->amenity->getParentName());
  }

  public function testGetParentAmenityIDByDefaultIsZero()
  {
    $this->assertSame(0, $this->amenity->getParentAmenityID());
  }

  public function testSetParentAmenityIDWillConvertToInt()
  {
    $parentAmenityID = '123';
    $intValue = intval($parentAmenityID);
    $this->amenity->setParentAmenityID($parentAmenityID);
    $this->assertSame($intValue, $this->amenity->getParentAmenityID());
  }

  public function testSetParentAmenityIDWillConvertAnyNonIntegerToZero()
  {
    $parentAmenityID = 'I am not an integer.';
    $this->amenity->setParentAmenityID($parentAmenityID);
    $this->assertSame(0, $this->amenity->getParentAmenityID());
  }

  public function testGetAmenityLocationIDArrayByDefaultIsEmpty()
  {
    $this->assertSame(array(), $this->amenity->getAmenityLocationIDArray());
  }

  public function testSetAmenityLocationIDArrayAcceptsArray()
  {
    $amenityLocationIDArray = array(1,2,3,4);
    $this->amenity->setAmenityLocationIDArray($amenityLocationIDArray);
    $this->assertSame($amenityLocationIDArray, $this->amenity->getAmenityLocationIDArray());
  }

}
