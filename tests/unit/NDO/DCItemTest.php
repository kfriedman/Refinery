<?php


namespace NYPL\Refinery;


use NYPL\Refinery\NDO\DCItem;

class DCItemTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var DCItem
   */
  private $DCItem;

  public function setUp()
  {
    $this->DCItem = new DCItem();
  }

  public function testSetAndGetImageLink()
  {
    $imageLink = 'Data validation of imageLink is performed elsewhere.';
    $this->DCItem->setImageLink($imageLink);
    $this->assertSame($imageLink, $this->DCItem->getImageLink());
  }

  public function testSetAndGetImageID()
  {
    $imageID = 15;
    $this->DCItem->setImageID($imageID);
    $this->assertSame($imageID, $this->DCItem->getImageID());
  }

  public function testSetAndGetItemLink()
  {
    $itemLink = 'Data validation of imageLink is performed elsewhere.';
    $this->DCItem->setItemLink($itemLink);
    $this->assertSame($itemLink, $this->DCItem->getItemLink());
  }

  public function testSetAndGetTitle()
  {
    $title = 'Data validation of Title is performed elsewhere.';
    $this->DCItem->setTitle($title);
    $this->assertSame($title, $this->DCItem->getTitle());
  }
}
