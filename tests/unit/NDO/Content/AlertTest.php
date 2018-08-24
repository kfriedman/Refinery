<?php

namespace NYPL\Refinery;


use NYPL\Refinery\NDO\Content\Alert;
use NYPL\Refinery\NDO\LocalDateTime;
use NYPL\Refinery\NDO\URI;

class AlertTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var Alert
   */
  private $alert;

  public function setUp()
  {
    $this->alert = new Alert();
  }

  public function testSetAndGetScope()
  {
    $scope = 'Data validation of scope is performed elsewhere.';
    $this->alert->setScope($scope);
    $this->assertSame($scope, $this->alert->getScope());
  }

  public function testSetAndGetClosedMessage()
  {
    $closedMessage = 'Data validation of closed message is performed elsewhere.';
    $this->alert->setClosedMessage($closedMessage);
    $this->assertSame($closedMessage, $this->alert->getClosedMessage());
  }

  public function testSetAndGetMessage()
  {
    $message = 'Data validation of message is performed elsewhere.';
    $this->alert->setMessage($message);
    $this->assertSame($message, $this->alert->getMessage());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetDisplayDateStartThrowsExceptionOnNonLocalDateTimeParameter()
  {
    $this->alert->setDisplayDateStart('Not LocalDateTime.');
  }

  public function testSetDisplayDateAcceptsLocalDateTimeParameter()
  {
    $displayDateStart = new LocalDateTime();
    $this->alert->setDisplayDateStart($displayDateStart);
    $this->assertSame($displayDateStart, $this->alert->getDisplayDateStart());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetDisplayDateEndThrowsExceptionOnNonLocalDateTimeParameter()
  {
    $this->alert->setDisplayDateEnd(new Alert());
  }

  public function testSetDisplayDateEndAcceptsLocalDateTimeParameter()
  {
    $displayDateEnd = new LocalDateTime();
    $this->alert->setDisplayDateEnd($displayDateEnd);
    $this->assertSame($displayDateEnd, $this->alert->getDisplayDateEnd());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetClosingDateStartThrowsExceptionOnNonLocalDateTimeParameter()
  {
    $this->alert->setClosingDateStart('Not LocalDateTime');
  }

  public function testSetClosingDateStartAcceptsLocalDateTimeParameter()
  {
    $closingDateStart = new LocalDateTime();
    $this->alert->setClosingDateStart($closingDateStart);
    $this->assertSame($closingDateStart, $this->alert->getClosingDateStart());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetClosingDateEndThrowsExceptionOnNonLocalDateTimeParameter()
  {
    $this->alert->setClosingDateEnd(new Alert());
  }

  public function testSetClosingDateEndAcceptsLocalDateTimeParameter()
  {
    $closingDateEnd = new LocalDateTime();
    $this->alert->setClosingDateEnd($closingDateEnd);
    $this->assertSame($closingDateEnd, $this->alert->getClosingDateEnd());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetUriThrowsExceptionOnNonUriParameter()
  {
    $this->alert->setUri(new LocalDateTime());
  }

  public function testSetUriAcceptsUriParameter()
  {
    $uri = new URI();
    $this->alert->setUri($uri);
    $this->assertSame($uri, $this->alert->getUri());
  }

  public function testSetAndGetAlertSetArray()
  {
    $parentAlertSetArray = array('Data', 'validation', 'of Parent Alert Set Array Elements is performed elsewhere');
    $this->alert->setParentAlertSetArray($parentAlertSetArray);
    $this->assertSame($parentAlertSetArray, $this->alert->getParentAlertSetArray());
  }
}
