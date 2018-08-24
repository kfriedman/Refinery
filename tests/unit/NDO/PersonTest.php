<?php

namespace NYPL\Refinery;


use NYPL\Refinery\NDO\Person\User;

class PersonTest extends \PHPUnit_Framework_TestCase
{
  public function testSetAndGetTitle()
  {
    /**
     * @var User
     */
    $person = new User();
    $title = 'Data Validation for Title is perform elsewhere.';
    $person->setTitle($title);
    $this->assertSame($title, $person->getTitle());
  }
}
