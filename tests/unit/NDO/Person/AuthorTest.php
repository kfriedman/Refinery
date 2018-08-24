<?php

namespace NYPL\Refinery;


use NYPL\Refinery\NDO\Person\Author;

class AuthorTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var Author
   */
  private $author;

  public function setUp()
  {
    $this->author = new Author();
  }

  public function testSetAndGetDisplayName()
  {
    $displayName = 'Data validation of displayName is performed elsewhere.';
    $this->author->setDisplayName($displayName);
    $this->assertSame($displayName, $this->author->getDisplayName());
  }

  public function testSetAndGetLocation()
  {
    $location = 'Data validation of location is performed elsewhere.';
    $this->author->setLocation($location);
    $this->assertSame($location, $this->author->getLocation());
  }
}
