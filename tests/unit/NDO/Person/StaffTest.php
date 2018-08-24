<?php

namespace NYPL\Refinery;


use NYPL\Refinery\NDO\Person\Staff;

class StaffTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var Staff
   */
  private $staff;

  public function setUp()
  {
    $this->staff = new Staff();
  }

  public function testSetAndGetFirstName()
  {
    $firstName = 'Data validation of firstName is performed elsewhere.';
    $this->staff->setFirstName($firstName);
    $this->assertSame($firstName, $this->staff->getFirstName());
  }

  public function testSetAndGetLastName()
  {
    $lastName = 'Data validation of lastName is performed elsewhere.';
    $this->staff->setLastName($lastName);
    $this->assertSame($lastName, $this->staff->getLastName());
  }

  public function testSetAndGetTitle()
  {
    $title = 'Data validation of Title is performed elsewhere.';
    $this->staff->setTitle($title);
    $this->assertSame($title, $this->staff->getTitle());
  }

  public function testSetAndGetPhoneNumber()
  {
    $phoneNumber = 'Data validation of phoneNumber is performed elsewhere.';
    $this->staff->setPhone($phoneNumber);
    $this->assertSame($phoneNumber, $this->staff->getPhone());
  }

  public function testSetAndGetEmail()
  {
    $email = 'Data validation of Email is performed elsewhere.';
    $this->staff->setEmail($email);
    $this->assertSame($email, $this->staff->getEmail());
  }

  public function testSetAndGetFullName()
  {
    $fullName = 'Data validation of Full Name is performed elsewhere.';
    $this->staff->setFullName($fullName);
    $this->assertSame($fullName, $this->staff->getFullName());
  }
}
