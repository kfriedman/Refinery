<?php

namespace NYPL\Refinery;


use NYPL\Refinery\NDO\Content\Address;
use NYPL\Refinery\NDO\Location;
use NYPL\Refinery\NDO\MediaGroup;
use NYPL\Refinery\NDO\SubjectOtherGroup;
use NYPL\Refinery\NDO\URI;
use NYPL\Refinery\NDO\URIGroup;

class LocationTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var Location
   */
  private $location;

  public function setUp()
  {
    $this->location = new Location();
  }

  /**
   * @covers \NYPL\Refinery\NDO\Location::setSupportedProviders
   */
  public function testSetSupportedProviders()
  {
    $providerArray = $this->location->getSupportedReadProviders();
    $this->assertNotEmpty($providerArray);
  }

  public function testSetAndGetFullName()
  {
    $fullName = 'Data validation of FullName is performed elsewhere.';
    $this->location->setFullName($fullName);
    $this->assertSame($fullName, $this->location->getFullName());
  }

  public function testSetAndGetShortName()
  {
    $shortName = 'Data validation of ShortName is performed elsewhere.';
    $this->location->setShortName($shortName);
    $this->assertSame($shortName, $this->location->getShortName());
  }

  public function testSetAndGetSymbol()
  {
    $symbol = 'Data validation of Symbol is performed elsewhere.';
    $this->location->setSymbol($symbol);
    $this->assertSame($symbol, $this->location->getSymbol());
  }

  public function testSetAndGetSlug()
  {
    $slug = 'Data validation of Slug is performed elsewhere.';
    $this->location->setSlug($slug);
    $this->assertSame($slug, $this->location->getSlug());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetAboutURIThrowsExceptionOnNonURIParameter()
  {
    $this->location->setAboutURI(new Location());
  }

  public function testSetAboutURIAcceptsURIParameter()
  {
    $aboutURI = new URI();
    $this->location->setAboutURI($aboutURI);
    $this->assertSame($aboutURI, $this->location->getAboutURI());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetBlogURIThrowsExceptionOnNonURIParameter()
  {
    $this->location->setBlogURI(new Location());
  }

  public function testSetBlogURIAcceptsURIParameter()
  {
    $blogURI = new URI();
    $this->location->setBlogURI($blogURI);
    $this->assertSame($blogURI, $this->location->getBlogURI());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetEventsURIThrowsExceptionOnNonURIParameter()
  {
    $this->location->setEventsURI(new Location());
  }

  public function testSetEventsURIAcceptsURIParameter()
  {
    $eventsURI = new URI();
    $this->location->setEventsURI($eventsURI);
    $this->assertSame($eventsURI, $this->location->getEventsURI());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetCatalogURIThrowsExceptionOnNonURIParameter()
  {
    $this->location->setCatalogURI(new Location());
  }

  public function testSetCatalogURIAcceptsURIParameter()
  {
    $catalogURI = new URI();
    $this->location->setCatalogURI($catalogURI);
    $this->assertSame($catalogURI, $this->location->getCatalogURI());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetContactURIThrowsExceptionOnNonURIParameter()
  {
    $this->location->setContactURI(new Location());
  }

  public function testSetContactURIAcceptsURIParameter()
  {
    $contactURI = new URI();
    $this->location->setContactURI($contactURI);
    $this->assertSame($contactURI, $this->location->getContactURI());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetConciergeURIThrowsExceptionOnNonURIParameter()
  {
    $this->location->setConciergeURI('Not a URI.');
  }

  public function testSetConciergeURIAcceptsURIParameter()
  {
    $conciergeURI = new URI();
    $this->location->setConciergeURI($conciergeURI);
    $this->assertSame($conciergeURI, $this->location->getConciergeURI());
  }

  public function testSetAndGetAccessibility()
  {
    $accessibility = 'Data validation of Accessibility is performed elsewhere.';
    $this->location->setAccessibility($accessibility);
    $this->assertSame($accessibility, $this->location->getAccessibility());
  }

  public function testSetAndGetAccessibilityNote()
  {
    $accessibilityNote = 'Data validation of AccessibilityNote is performed elsewhere.';
    $this->location->setAccessibilityNote($accessibilityNote);
    $this->assertSame($accessibilityNote, $this->location->getAccessibilityNote());
  }

  public function testSetAndGetPhone()
  {
    $phone = 'Data validation of Phone is performed elsewhere.';
    $this->location->setPhone($phone);
    $this->assertSame($phone, $this->location->getPhone());
  }

  public function testSetAndGetFax()
  {
    $fax = 'Data validation of Fax is performed elsewhere.';
    $this->location->setFax($fax);
    $this->assertSame($fax, $this->location->getFax());
  }

  public function testSetAndGetTTY()
  {
    $tty = 'Data validation of TTY is performed elsewhere.';
    $this->location->setTty($tty);
    $this->assertSame($tty, $this->location->getTTY());
  }

  public function testSetAndGetCrossStreet()
  {
    $crossStreet = 'Data validation of CrossStreet is performed elsewhere.';
    $this->location->setCrossStreet($crossStreet);
    $this->assertSame($crossStreet, $this->location->getCrossStreet());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetAddressThrowsExceptionOnNonAddressParameter()
  {
    $this->location->setAddress(new URI());
  }

  public function testSetAddressAcceptsAddressParameter()
  {
    $address = new Address();
    $this->location->setAddress($address);
    $this->assertSame($address, $this->location->getAddress());
  }

  public function testSetAndGetLocationType()
  {
    $locationType = 'Data validation of LocationType is performed elsewhere.';
    $this->location->setLocationType($locationType);
    $this->assertSame($locationType, $this->location->getLocationType());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetRelatedLinksThrowsExceptionOnNonURIGroupParameter()
  {
    $this->location->setRelatedLinks(new URI());
  }

  public function testSetRelatedLinksAcceptsURIGroupParameter()
  {
    $relatedLinks = new URIGroup();
    $this->location->setRelatedLinks($relatedLinks);
    $this->assertSame($relatedLinks, $this->location->getRelatedLinks());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetSubjectGroupThrowsExceptionOnNonSubjectGroupParameter()
  {
    $this->location->setSubjectGroup(new URI());
  }

  public function testSetSubjectGroupAcceptsSubjectGroupParameter()
  {
    $subjectGroup = new SubjectOtherGroup();
    $this->location->setSubjectGroup($subjectGroup);
    $this->assertSame($subjectGroup, $this->location->getSubjectGroup());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetMediaGroupThrowsExceptionOnNonMediaGroupParameter()
  {
    $this->location->setMediaGroup(new URIGroup());
  }

  public function testSetMediaGroupAcceptsMediaGroupParameter()
  {
    $mediaGroup = new MediaGroup();
    $this->location->setMediaGroup($mediaGroup);
    $this->assertSame($mediaGroup, $this->location->getMediaGroup());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetSynonymsThrowsExceptionOnNonArrayParameter()
  {
    $this->location->setSynonyms(10);
  }

  public function testSetSynonymsAcceptsArrayParameter()
  {
    $synonyms = array('Data', 'validation', 'of', 'Synonyms', 'is', 'performed', 'elsewhere');
    $this->location->setSynonyms($synonyms);
    $this->assertSame($synonyms, $this->location->getSynonyms());
  }
}
