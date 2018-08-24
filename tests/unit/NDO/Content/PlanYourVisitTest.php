<?php

namespace NYPL\Refinery;


use NYPL\Refinery\NDO\Content\PlanYourVisit;
use NYPL\Refinery\NDO\URI;

class PlanYourVisitTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var PlanYourVisit
   */
  private $planYourVisit;

  public function setUp()
  {
    $this->planYourVisit = new PlanYourVisit();
  }

  public function testSetAndGetLabel()
  {
    $label = 'Data validation of Label is performed elsewhere.';
    $this->planYourVisit->setLabel($label);
    $this->assertSame($label, $this->planYourVisit->getLabel());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetURIThrowsExceptionOnNonURIParameter()
  {
    $this->planYourVisit->setURI('Not a URI.');
  }

  public function testSetURIAcceptsURIParameter()
  {
    $uri = new URI();
    $this->planYourVisit->setURI($uri);
    $this->assertSame($uri, $this->planYourVisit->getURI());
  }

  public function testSetSortOrderConvertToInteger()
  {
    $sortOrder = '1';
    $intValue = intval($sortOrder);
    $this->planYourVisit->setSortOrder($sortOrder);
    $this->assertSame($intValue, $this->planYourVisit->getSortOrder());
  }

  public function testSetSortOrderWillConvertNonIntegerToZero()
  {
    $sortOrder = 'Not a sort order.';
    $this->planYourVisit->setSortOrder($sortOrder);
    $this->assertSame(0, $this->planYourVisit->getSortOrder());
  }
}
