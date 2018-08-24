<?php
/**
 * @file
 * These tests can be included in PlanYourVisitTest, but for
 * organizational purposes, it is in a separate unit test file.
 */

namespace NYPL\Refinery;


use NYPL\Refinery\NDO\AuthorGroup;
use NYPL\Refinery\NDO\Content\Appeal;
use NYPL\Refinery\NDO\LocalDateTime;

class ContentTest extends \PHPUnit_Framework_TestCase
{
  /**
   * Appeal is chosen as concrete instance since all functions
   * covered here are not overridden in Appeal.
   *
   * @var Appeal
   */
  private $content;

  public function setUp()
  {
    /*
     * A concrete object is needed to test an abstract class.
     */
    $this->content = new Appeal();
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetDataCreatedThrowsExceptionOnNonLocalDateTimeParameter()
  {
    $this->content->setDateCreated(new AuthorGroup());
  }

  public function testSetDataCreatedAcceptsLocalDateTimeParameter()
  {
    $dateCreated = new LocalDateTime();
    $this->content->setDateCreated($dateCreated);
    $this->assertSame($dateCreated, $this->content->getDateCreated());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetDateModifiedThrowsExceptionOnNonLocalDateTimeParameter()
  {
    $this->content->setDateModified(new AuthorGroup());
  }

  public function testSetDateModifiedAcceptsLocalDateTimeParameter()
  {
    $dateModified = new LocalDateTime();
    $this->content->setDateModified($dateModified);
    $this->assertSame($dateModified, $this->content->getDateModified());
  }
}
