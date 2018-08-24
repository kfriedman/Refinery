<?php


namespace NYPL\Refinery;


use NYPL\Refinery\NDO\Content\Image;
use NYPL\Refinery\NDO\Event;
use NYPL\Refinery\NDO\LocalDateTime;
use NYPL\Refinery\NDO\URI;

class EventTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var Event
   */
  private $event;

  public function setUp()
  {
    $this->event = new Event();
  }

  public function testSetEventIDConvertsToInteger()
  {
    $eventID = '20';
    $intValue = intval($eventID);
    $this->event->setEventID($eventID);
    $this->assertSame($intValue, $this->event->getEventID());
  }

  public function testSetEventIDConvertsNonIntegerToZero()
  {
    $eventID = 'Not an eventID.';
    $this->event->setEventID($eventID);
    $this->assertSame(0, $this->event->getEventID());
  }

  public function testSetAndGetUuid()
  {
    $uuid = 'Data validation of Uuid is performed elsewhere.';
    $this->event->setUuid($uuid);
    $this->assertSame($uuid, $this->event->getUuid());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetDateCreatedThrowsExceptionOnNonLocalDateTimeParameter()
  {
    $this->event->setDateCreated(new Event());
  }

  public function testSetDateCreatedAcceptsLocalDateTimeParameter()
  {
    $dateCreated = new LocalDateTime();
    $this->event->setDateCreated($dateCreated);
    $this->assertSame($dateCreated, $this->event->getDateCreated());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetDateModifiedThrowsExceptionOnNonLocalDateTimeParameter()
  {
    $this->event->setDateModified(new Event());
  }

  public function testSetDateModifiedAcceptsLocalDateTimeParameter()
  {
    $dateModified = new LocalDateTime();
    $this->event->setDateModified($dateModified);
    $this->assertSame($dateModified, $this->event->getDateModified());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetStartDateThrowsExceptionOnNonLocalDateTimeParameter()
  {
    $this->event->setStartDate(new Event());
  }

  public function testSetStartDateAcceptsLocalDateTimeParameter()
  {
    $startDate = new LocalDateTime();
    $this->event->setStartDate($startDate);
    $this->assertSame($startDate, $this->event->getStartDate());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetEndDateThrowsExceptionOnNonLocalDateTimeParameter()
  {
    $this->event->setEndDate(new Event());
  }

  public function testSetEndDateAcceptsLocalDateTimeParameter()
  {
    $endDate = new LocalDateTime();
    $this->event->setEndDate($endDate);
    $this->assertSame($endDate, $this->event->getEndDate());
  }

  public function testSetAndGetName()
  {
    $name = 'Data validation of Name is performed elsewhere.';
    $this->event->setName($name);
    $this->assertSame($name, $this->event->getName());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetUriThrowsExceptionOnNonUriParameter()
  {
    $this->event->setUri(new LocalDateTime());
  }

  public function testSetUriAcceptsUriParameter()
  {
    $uri = new URI();
    $this->event->setUri($uri);
    $this->assertSame($uri, $this->event->getUri());
  }

  public function testSetAndGetDescriptionFull()
  {
    $descriptionFull = 'Data validation of descriptionFull is performed elsewhere.';
    $this->event->setDescriptionFull($descriptionFull);
    $this->assertSame($descriptionFull, $this->event->getDescriptionFull());
  }

  public function testSetAndGetDescriptionShort()
  {
    $descriptionShort = 'Data validation of descriptionShort is performed elsewhere';
    $this->event->setDescriptionShort($descriptionShort);
    $this->assertSame($descriptionShort, $this->event->getDescriptionShort());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetImageThrowsExceptionOnNonImageParameter()
  {
    $this->event->setImage(new LocalDateTime());
  }

  public function testSetImageAcceptsImageParameter()
  {
    $image = new Image();
    $this->event->setImage($image);
    $this->assertSame($image, $this->event->getImage());
  }

  public function testSetAndGetEventStatus()
  {
    $eventStatus = 'Data validation of eventStatus is performed elsewhere.';
    $this->event->setEventStatus($eventStatus);
    $this->assertSame($eventStatus, $this->event->getEventStatus());
  }

  public function testSetAndGetRegistrationType()
  {
    $registrationType = 'Data validation of registrationType is performed elsewhere.';
    $this->event->setRegistrationType($registrationType);
    $this->assertSame($registrationType, $this->event->getRegistrationType());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetRegistrationOpenThrowsExceptionOnNonLocalDateTimeParameter()
  {
    $this->event->setRegistrationOpen(new Event());
  }

  public function testSetRegistrationAcceptsLocalDateTimeParameter()
  {
    $registrationOpen = new LocalDateTime();
    $this->event->setRegistrationOpen($registrationOpen);
    $this->assertSame($registrationOpen, $this->event->getRegistrationOpen());
  }
}
