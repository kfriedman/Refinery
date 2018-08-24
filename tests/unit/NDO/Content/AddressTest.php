<?php

namespace NYPL\Refinery;


use NYPL\Refinery\NDO\Content\Address;

class AddressTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var Address
   */
  private $address;

  public function setUp()
  {
    $this->address = new Address();
  }


  public function testSetAndGetAddress1()
  {
    $address1 = 'Data validation of Address1 is performed elsewhere.';
    $this->address->setAddress1($address1);
    $this->assertSame($address1, $this->address->getAddress1());
  }

  public function testSetAndGetCity()
  {
    $city = 'Data validation of City is performed elsewhere.';
    $this->address->setCity($city);
    $this->assertSame($city, $this->address->getCity());
  }

  public function testSetAndGetRegion()
  {
    $region = 'Data validation of Region is performed elsewhere.';
    $this->address->setRegion($region);
    $this->assertSame($region, $this->address->getRegion());
  }

  public function testSetAndGetPostalCode()
  {
    $postalCode = 'Data validation of PostalCode is performed elsewhere.';
    $this->address->setPostalCode($postalCode);
    $this->assertSame($postalCode, $this->address->getPostalCode());
  }

  public function testSetAndGetAddress2()
  {
    $address2 = 'Data validation of Address2 is performed elsewhere.';
    $this->address->setAddress2($address2);
    $this->assertSame($address2, $this->address->getAddress2());
  }

  public function testSetLatitudeWillConvertNonFloatToZero()
  {
    $latitude = 'This is not in latitude format.';
    $this->address->setLatitude($latitude);
    $this->assertSame(0.0, $this->address->getLatitude());
  }

  public function testSetLongitudeWillConvertNonFloatToZero()
  {
    $longitude = 'This is not in latitude format.';
    $this->address->setLongitude($longitude);
    $this->assertSame(0.0, $this->address->getLongitude());
  }

  public function testSetAndGetFloor()
  {
    $floor = 'Data validation of Floor is performed elsewhere.';
    $this->address->setFloor($floor);
    $this->assertSame($floor, $this->address->getFloor());
  }

  public function testSetAndGetRoom()
  {
    $room = 'Data validate of Room is performed elsewhere.';
    $this->address->setRoom($room);
    $this->assertSame($room, $this->address->getRoom());
  }
}
