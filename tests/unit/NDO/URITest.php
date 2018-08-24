<?php
namespace NYPL\Refinery;

use NYPL\Refinery\NDO\URI;

class URITest extends \PHPUnit_Framework_TestCase
{
  public function testConstructor()
  {
    $theURI = new URI('http://www.google.com', 'Research');
    $this->assertInstanceOf('\NYPL\Refinery\NDO\URI', $theURI);
  }

  /**
   * @covers \NYPL\Refinery\NDO\URI::setFullURI
   */
  public function testSetFullURI()
  {
      $theURI = 'http://www.google.com';
      $uri = new URI($theURI);
      $this->assertSame($theURI, $uri->getFullURI());
  }

  public function testGetURIWithoutHost()
  {
    $uri = new URI('http://www.google.com/chrome');
    $uriWithoutHost = $uri->getURIWithoutHost();
    $this->assertSame('chrome', $uriWithoutHost);
  }

  public function testSetAndGetType()
  {
    $uri = new URI();
    $type = 'URI';
    $this->assertSame($type, $uri->getNdoType());
  }
}
