<?php


namespace NYPL\Refinery;


use NYPL\Refinery\NDO\Content\Appeal;
use NYPL\Refinery\NDO\URI;

class AppealTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var Appeal
   */
  private $appeal;

  public function setUp()
  {
    $this->appeal = new Appeal();
  }

  public function testSetAndGetStatement()
  {
    $statement = 'Data validation of Statement is performed elsewhere.';
    $this->appeal->setStatement($statement);
    $this->assertSame($statement, $this->appeal->getStatement());
  }

  public function testSetAndGetTitle()
  {
    $title = 'Data validation of Title is performed elsewhere.';
    $this->appeal->setTitle($title);
    $this->assertSame($title, $this->appeal->getTitle());
  }

  public function testSetAndGetButtonTitle()
  {
    $buttonTitle = 'Data validation of ButtonTitle is performed elsewhere.';
    $this->appeal->setButtonTitle($buttonTitle);
    $this->assertSame($buttonTitle, $this->appeal->getButtonTitle());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetButtonLinkThrowsExceptionOnNonURIParameter()
  {
    $this->appeal->setButtonLink('Not a URI.');
  }

  public function testSetButtonLinkAcceptsURIParameter()
  {
    $buttonLink = new URI();
    $this->appeal->setButtonLink($buttonLink);
    $this->assertSame($buttonLink, $this->appeal->getButtonLink());
  }
  public function testSetAndGetAppealId()
  {
    /*
     * Data validation of AppealId is performed elsewhere.
     */
    $appealId = 22;
    $this->appeal->setAppealId($appealId);
    $this->assertSame($appealId, $this->appeal->getAppealId());
  }
}
