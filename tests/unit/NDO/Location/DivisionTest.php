<?php

namespace NYPL\Refinery;


use NYPL\Refinery\NDO\Content\Image;
use NYPL\Refinery\NDO\Location\Division;

class DivisionTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var Division
   */
  private $division;

  public function setUp()
  {
    $this->division = new Division();
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetInteriorImageThrowsExceptionOnNonImageParameter()
  {
    $this->division->setInteriorImage(new Division());
  }

  public function testSetInteriorImageAcceptsImageParameter()
  {
    $image = new Image();
    $this->division->setInteriorImage($image);
    $this->assertSame($image, $this->division->getInteriorImage());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetCollectionsImageThrowsExceptionOnNonImageParameter()
  {
    $this->division->setCollectionsImage(new Division());
  }

  public function testSetCollectionsImageAcceptsImageParameter()
  {
    $image = new Image();
    $this->division->setCollectionsImage($image);
    $this->assertSame($image, $this->division->getCollectionsImage());
  }

  public function testSetOrderConvertsToInteger()
  {
    $sortOrder = '111';
    $intValue = intval($sortOrder);
    $this->division->setSortOrder($sortOrder);
    $this->assertSame($intValue, $this->division->getSortOrder());
  }

  public function testSetOrderConvertsNonIntegerToZero()
  {
    $sortOrder = 'Not a sort order.';
    $this->division->setSortOrder($sortOrder);
    $this->assertSame(0, $this->division->getSortOrder());
  }

  public function testSetAndGetParentLocationSymbol()
  {
    $parentLocationSymbol = 'Data validation of parentLocationSymbol is performed elsewhere.';
    $this->division->setParentLocationSymbol($parentLocationSymbol);
    $this->assertSame($parentLocationSymbol, $this->division->getParentLocationSymbol());
  }

  public function testSetParentLocationIDConvertToInteger()
  {
    $parentLocationID = '121';
    $intValue = intval($parentLocationID);
    $this->division->setParentLocationID($parentLocationID);
    $this->assertSame($intValue, $this->division->getParentLocationID());
  }

  public function testSetParentLocationIDConvertsNonIntegerToZero()
  {
    $parentLocationID = 'Not a Location ID';
    $this->division->setParentLocationID($parentLocationID);
    $this->assertSame(0, $this->division->getParentLocationID());
  }
}
