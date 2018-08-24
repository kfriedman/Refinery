<?php

namespace NYPL\Refinery;


use NYPL\Refinery\NDO\Content\LocationHours;
use NYPL\Refinery\NDO\LocalDateTime;

class LocationHoursTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var LocationHours
   */
  private $locationHours;

  public function setUp()
  {
    $this->locationHours = new LocationHours();
  }

  public function testSetStartDayConvertToInteger()
  {
    $startDay = '11';
    $intValue = intval($startDay);
    $this->locationHours->setStartDay($startDay);
    $this->assertSame($intValue, $this->locationHours->getStartDay());
  }

  public function testSetStartDayConvertNonIntegerToZero()
  {
    $startDay = 'Not an integer';
    $this->locationHours->setStartDay($startDay);
    $this->assertSame(0, $this->locationHours->getStartDay());
  }

  public function testSetStartHourConvertToInteger()
  {
    $startHour = '12';
    $intValue = intval($startHour);
    $this->locationHours->setStartHour($startHour);
    $this->assertSame($intValue, $this->locationHours->getStartHour());
  }

  public function testSetStartHourConvertNonIntegerToZero()
  {
    $startHour = 'Not an integer';
    $this->locationHours->setStartHour($startHour);
    $this->assertSame(0, $this->locationHours->getStartHour());
  }

  public function testSetAndGetStartMinute()
  {
    $startMinute = '11';
    $this->locationHours->setStartMinute($startMinute);
    $this->assertSame($startMinute, $this->locationHours->getStartMinute());
  }

  public function testSetEndDayConvertToInteger()
  {
    $endDay = '11';
    $intValue = intval($endDay);
    $this->locationHours->setEndDay($endDay);
    $this->assertSame($intValue, $this->locationHours->getEndDay());
  }

  public function testSetEndDayConvertNonIntegerToZero()
  {
    $endDay = 'Not an integer';
    $this->locationHours->setEndDay($endDay);
    $this->assertSame(0, $this->locationHours->getEndDay());
  }

  public function testSetEndHourConvertToInteger()
  {
    $endHour = '12';
    $intValue = intval($endHour);
    $this->locationHours->setEndHour($endHour);
    $this->assertSame($intValue, $this->locationHours->getEndHour());
  }

  public function testSetEndHourConvertNonIntegerToZero()
  {
    $endHour = 'Not an integer';
    $this->locationHours->setEndHour($endHour);
    $this->assertSame(0, $this->locationHours->getEndHour());
  }

  public function testSetAndGetEndMinute()
  {
    $endMinute = '11';
    $this->locationHours->setEndMinute($endMinute);
    $this->assertSame($endMinute, $this->locationHours->getEndMinute());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetStartDateTimeThrowsExceptionOnNonLocalDateTimeParameter()
  {
    $this->locationHours->setStartDateTime(new LocationHours());
  }

  public function testSetStartDateTimeAcceptsLocalDateTimeParameter()
  {
    $startDateTime = new LocalDateTime();
    $this->locationHours->setStartDateTime($startDateTime);
    $this->assertSame($startDateTime, $this->locationHours->getStartDateTime());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetEndDateTimeThrowsExceptionOnNonLocalDateTimeParameter()
  {
    $this->locationHours->setEndDateTime(new LocationHours());
  }

  public function testSetEndDateTimeAcceptsLocalDateTimeParameter()
  {
    $endDateTime = new LocalDateTime();
    $this->locationHours->setEndDateTime($endDateTime);
    $this->assertSame($endDateTime, $this->locationHours->getEndDateTime());
  }

  /**
   * @expectedException \NYPL\Refinery\Exception\RefineryException
   */
  public function testDayOfWeekToDayTextThrowsExceptionForOutOfRangeDay()
  {
    $this->locationHours->dayOfWeekToDayText(7);
  }

  public function testDayOfWeekToDayTextAcceptsInRangeDay()
  {
    /*
     * Testing every switch case
     */
    $dayTextArray = array('Sun.', 'Mon.', 'Tue.', 'Wed.', 'Thu.', 'Fri.', 'Sat.');
    for ($day=0; $day < 7; $day++) {
      $dayText = $this->locationHours->dayOfWeekToDayText($day);
      $this->assertSame($dayTextArray[$day], $dayText);
    }
  }
}
