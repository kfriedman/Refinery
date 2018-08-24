<?php

namespace NYPL\Refinery;


use NYPL\Refinery\NDO\Content\Feature;
use NYPL\Refinery\NDO\Content\Image;
use NYPL\Refinery\NDO\URI;

class FeatureTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var Feature
   */
  private $feature;

  public function setUp()
  {
    $this->feature = new Feature();
  }

  public function testSetAndGetFeatureID()
  {
    /*
     * Data validation of $featureID is performed elsewhere.
     */
    $featureID = 11;
    $this->feature->setFeatureID($featureID);
    $this->assertSame($featureID, $this->feature->getFeatureID());
  }

  public function testSetAndGetTitle()
  {
    $title = 'Data validation of Title is performed elsewhere.';
    $this->feature->setTitle($title);
    $this->assertSame($title, $this->feature->getTitle());
  }

  public function testSetAndGetBody()
  {
    $body = 'Data validation of Body is performed elsewhere.';
    $this->feature->setBody($body);
    $this->assertSame($body, $this->feature->getBody());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetImageThrowsExceptionOnNonImageParameter()
  {
    $this->feature->setImage(new Feature());
  }

  public function testSetImageAcceptsImageParameter()
  {
    $image = new Image();
    $this->feature->setImage($image);
    $this->assertSame($image, $this->feature->getImage());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetUriThrowsExceptionOnNonUriParameter()
  {
    $this->feature->setUri(new Image());
  }

  public function testSetUriAcceptsUriParameter()
  {
    $uri = new URI();
    $this->feature->setUri($uri);
    $this->assertSame($uri, $this->feature->getUri());
  }
}
