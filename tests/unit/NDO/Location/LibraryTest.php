<?php

namespace NYPL\Refinery;


use NYPL\Refinery\NDO\Content\Image;
use NYPL\Refinery\NDO\Location\Library;

class LibraryTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var Library
   */
  private $library;

  public function setUp()
  {
    $this->library = new Library();
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetExteriorImageThrowsExceptionOnNonImageParameter()
  {
    $this->library->setExteriorImage(new Library());
  }

  public function testSetExteriorImageAcceptsImageParameter()
  {
    $image = new Image();
    $this->library->setExteriorImage($image);
    $this->assertSame($image, $this->library->getExteriorImage());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetInteriorImageThrowsExceptionOnNonImageParameter()
  {
    $this->library->setInteriorImage(new Library());
  }

  public function testSetInteriorImageAcceptsImageParameter()
  {
    $image = new Image();
    $this->library->setInteriorImage($image);
    $this->assertSame($image, $this->library->getInteriorImage());
  }

}
