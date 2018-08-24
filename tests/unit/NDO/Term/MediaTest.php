<?php

namespace NYPL\Refinery;



class MediaTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var NDO\Term\Media
   */
  private $media;

  public function setUp()
  {
    $this->media = new NDO\Term\Media();
  }
  /**
   * @cover \NYPL\Refinery\NDO\Term\Media::setSupportedProviders()
   */
  public function testSetSupportedProviders()
  {
    $providerArray = $this->media->getSupportedReadProviders();
    $this->assertNotEmpty($providerArray);
  }
}
