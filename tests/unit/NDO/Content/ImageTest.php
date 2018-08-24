<?php

namespace NYPL\Refinery\NDO\Content;

use NYPL\Refinery\NDO\URI;

class ImageTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var Image
   */
  private $image;

  public function setUp()
  {
    /*
     * Reinforcing constructor to initialize with uuid and uri.
     */
    $this->image = new Image('8533C7CB-59E2-4D22-B11A-64A1773DB6Ag', new URI('http://www.nypl.org'));
    $this->assertInstanceOf('NYPL\Refinery\NDO\Content\Image', $this->image);
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetUriThrowsExceptionOnNonUriParameter()
  {
    $this->image->setUri(new Image());
  }

  public function testSetUriAcceptsUriParameter()
  {
    /*
     * URI needs to be a qualified one.
     */
    $uri = new URI('http://vagrant.drupal.nypl.org');
    $this->image->setUri($uri);
    $this->assertSame($uri, $this->image->getUri());
  }
}
