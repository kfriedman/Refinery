<?php
namespace NYPL\Refinery;

use NYPL\Refinery\NDO\Content\Page\BasicPage;

/**
 * @coversDefaultClass NYPL\Refinery\NDO\Content\Page\BasicPage
 */
class BasicPageTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var BasicPage
   */
  public $basicPage = null;

    /**
     * Fixtures: Setting up basicPage needed for subsequent tests.
     */
    public function setUp()
    {
        $this->basicPage = new BasicPage();
        $this->assertInstanceOf('\NYPL\Refinery\NDO\Content\Page\BasicPage', $this->basicPage);

    }

    /**
     * @covers \NYPL\Refinery\NDO\Content\Page\BasicPage::setSupportedProviders
     */
    public function testSetSupportedProviders()
    {
        $basicPage = new BasicPage();
        $providerArray = $basicPage->getSupportedReadProviders();
        $this->assertNotEmpty($providerArray);
    }

    /**
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testGettersSettingsObject()
    {
        $basicPage = new BasicPage();
        $basicPage->setLanguage('Not an Object');
    }


    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::getAuthors
     */
    public function testGetAuthorsByDefaultIsNull()
    {
        $this->assertNull($this->basicPage->getAuthors());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::setAuthors
     */
    public function testSetAuthors()
    {
        $authors = new NDO\AuthorGroup();
        $this->assertInstanceOf('NYPL\Refinery\NDO\AuthorGroup', $authors);

        $this->basicPage->setAuthors($authors);
        $this->assertSame($authors, $this->basicPage->getAuthors());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::getLanguage
     */
    public function testGetLanguageByDefaultIsNull()
    {
        $this->assertNull($this->basicPage->getLanguage());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::setLanguage
     */
    public function testSetLanguage()
    {
        $language = new NDO\Term\Language();
        $this->assertInstanceOf('NYPL\Refinery\NDO\Term\Language', $language);

        $this->basicPage->setLanguage($language);
        $this->assertSame($language, $this->basicPage->getLanguage());

    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::getTitle
     */
    public function testGetTitleByDefaultIsBlank()
    {
        $this->assertSame('', $this->basicPage->getTitle());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::setTitle
     * @expectedException \InvalidArgumentException
     */
    public function testSetTitleThrowsExceptionOnSettingNonStringValue()
    {
        $this->basicPage->setTitle(123);
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::setTitle
     */
    public function testSetTitle()
    {
        $title = 'Test title';
        $this->basicPage->setTitle($title);
        $this->assertSame($title, $this->basicPage->getTitle());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::getBodyFull
     */
    public function testGetBodyFullByDefaultIsBlank()
    {
        $this->assertSame('', $this->basicPage->getBodyFull());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::setBodyFull
     * @expectedException \InvalidArgumentException
     */
    public function testSetBodyFullThrowsExceptionOnSettingNonStringValue()
    {
        $this->basicPage->setBodyFull(123);
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::setBodyFull
     */
    public function testSetBodyFull()
    {
        $bodyFullText = 'This is a lot of text for a full body text.';
        $this->basicPage->setBodyFull($bodyFullText);

        $this->assertEquals($bodyFullText, $this->basicPage->getBodyFull());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::getBodyShort
     */
    public function testGetBodyShortByDefaultIsBlank()
    {
        $this->assertSame('', $this->basicPage->getBodyShort());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::setBodyShort
     * @expectedException \InvalidArgumentException
     */
    public function testSetBodyShortThrowsExceptionOnSettingNonStringValue()
    {
        $this->basicPage->setBodyShort(123);
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::setBodyShort
     */
    public function testSetBodyShort()
    {
        $bodyShort = 'This is a short text for body.';
        $this->basicPage->setBodyShort($bodyShort);

        $this->assertSame($bodyShort, $this->basicPage->getBodyShort());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::getURI
     */
    public function testGetURIByDefaultIsNull()
    {
        $this->assertNull($this->basicPage->getURI());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::setURI
     */
    public function testSetURI()
    {
        $uri = new NDO\URI();
        $this->assertInstanceOf('NYPL\Refinery\NDO\URI', $uri);

        $this->basicPage->setURI($uri);
        $this->assertSame($uri, $this->basicPage->getURI());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::getSeriesSequence
     */
    public function testGetSeriesSequenceByDefaultIsZero()
    {
        $this->assertSame(0, $this->basicPage->getSeriesSequence());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::setSeriesSequence
     * @expectedException \InvalidArgumentException
     */
    public function testSetSeriesSequenceThrowsExceptionOnSettingNonNumericValue()
    {
        $this->basicPage->setSeriesSequence('a');
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::setSeriesSequence
     */
    public function testSetSeriesSequence()
    {
        $seriesSequence = 2;

        $this->basicPage->setSeriesSequence($seriesSequence);
        $this->assertSame($seriesSequence, $this->basicPage->getSeriesSequence());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::getSubjects
     */
    public function testGetSubjectsByDefaultIsNull()
    {
        $this->assertNull($this->basicPage->getSubjects());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::setSubjects
     */
    public function testSetSubjects()
    {
        $subjects = new NDO\SubjectOtherGroup();
        $this->assertInstanceOf('NYPL\Refinery\NDO\SubjectOtherGroup', $subjects);

        $this->basicPage->setSubjects($subjects);
        $this->assertSame($subjects, $this->basicPage->getSubjects());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::getAudience
     */
    public function testGetAudienceByDefaultIsNull()
    {
        $this->assertNull($this->basicPage->getAudience());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::setAudience
     */
    public function testSetAudience()
    {
        $audience = new NDO\AudienceGroup();
        $this->assertInstanceOf('NYPL\Refinery\NDO\AudienceGroup', $audience);

        $this->basicPage->setAudience($audience);
        $this->assertSame($audience, $this->basicPage->getAudience());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::getSeries
     */
    public function testGetSeriesByDefaultIsNull()
    {
        $this->assertNull($this->basicPage->getSeries());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::setSeries
     */
    public function testSetSeries()
    {
        $series = new NDO\SeriesGroup();
        $this->assertInstanceOf('NYPL\Refinery\NDO\SeriesGroup', $series);

        $this->basicPage->setSeries($series);
        $this->assertSame($series, $this->basicPage->getSeries());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::getEmbeddedContent
     */
    public function testGetEmbeddedContentByDefaultIsNull()
    {
        $this->assertNull($this->basicPage->getEmbeddedContent());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::setEmbeddedContent
     */
    public function testSetEmbeddedContent()
    {
        $embeddedContent = new NDO\ContentGroup();
        $this->assertInstanceOf('NYPL\Refinery\NDO\ContentGroup', $embeddedContent);

        $this->basicPage->setEmbeddedContent($embeddedContent);
        $this->assertSame($embeddedContent, $this->basicPage->getEmbeddedContent());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::getStatusReview
     */
    public function testGetStatusReviewByDefaultIsNull()
    {
        $this->assertNull($this->basicPage->getStatusReview());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::setStatusReview
     */
    public function testSetStatusReview()
    {
        $statusReview = new NDO\StatusReview();
        $this->assertInstanceOf('NYPL\Refinery\NDO\StatusReview', $statusReview);

        $this->basicPage->setStatusReview($statusReview);
        $this->assertSame($statusReview, $this->basicPage->getStatusReview());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::getStatusPublishing
     */
    public function testGetStatusPublishingByDefaultIsNull()
    {
        $this->assertNull($this->basicPage->getStatusPublishing());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::setStatusPublishing
     */
    public function testSetStatusPublishing()
    {
        $statusPublishing = new NDO\StatusPublishing();
        $this->assertInstanceOf('NYPL\Refinery\NDO\StatusPublishing', $statusPublishing);

        $this->basicPage->setStatusPUblishing($statusPublishing);
        $this->assertSame($statusPublishing, $this->basicPage->getStatusPublishing());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::getRelatedLocations
     */
    public function testGetRelatedLocationsByDefaultIsNull()
    {
        $this->assertNull($this->basicPage->getRelatedLocations());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::setRelatedLocations
     */
    public function testSetRelatedLocations()
    {
        $relatedLocations = new NDO\LocationGroup();
        $this->assertInstanceOf('NYPL\Refinery\NDO\LocationGroup', $relatedLocations);

        $this->basicPage->setRelatedLocations($relatedLocations);
        $this->assertSame($relatedLocations, $this->basicPage->getRelatedLocations());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::getRelatedContent
     */
    public function testGetRelatedContent()
    {
        $this->assertNull($this->basicPage->getRelatedContent());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::setRelatedContent
     */
    public function testSetRelatedContent()
    {
        $relatedContent = new NDO\ContentGroup();
        $this->assertInstanceOf('NYPL\Refinery\NDO\ContentGroup', $relatedContent);

        $this->basicPage->setRelatedContent($relatedContent);
        $this->assertSame($relatedContent, $this->basicPage->getRelatedContent());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::getRelatedPrograms
     */
    public function testGetRelatedProgramsByDefaultIsNull()
    {
        $this->assertNull($this->basicPage->getRelatedPrograms());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::setRelatedPrograms
     */
    public function testSetRelatedPrograms()
    {
        $relatedPrograms = new NDO\ProgramGroup();
        $this->assertInstanceOf('NYPL\Refinery\NDO\ProgramGroup', $relatedPrograms);

        $this->basicPage->setRelatedPrograms($relatedPrograms);
        $this->assertSame($relatedPrograms, $this->basicPage->getRelatedPrograms());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::getRelatedDCItems
     */
    public function testGetRelatedDCItemsByDefaultIsNull()
    {
        $this->assertNull($this->basicPage->getRelatedDCItems());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::setRelatedDCItems
     */
    public function testSetRelatedDCItems()
    {
        $relatedDCItems = new NDO\DCItemGroup();
        $this->assertInstanceOf('NYPL\Refinery\NDO\DCItemGroup', $relatedDCItems);

        $this->basicPage->setRelatedDCItems($relatedDCItems);
        $this->assertSame($relatedDCItems, $this->basicPage->getRelatedDCItems());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::getRelatedCatalogItems
     */
    public function testGetRelatedCatalogItemsByDefaultIsNull()
    {
        $this->assertNull($this->basicPage->getRelatedCatalogItems());
    }

    /**
     * @covers NYPL\Refinery\NDO\Content\Page\BasicPage::setRelatedCatalogItems
     */
    public function testSetRelatedCatalogItems()
    {
        $relatedCatalogItems = new NDO\CatalogItemGroup();
        $this->assertInstanceOf('NYPL\Refinery\NDO\CatalogItemGroup', $relatedCatalogItems);

        $this->basicPage->setRelatedCatalogItems($relatedCatalogItems);
        $this->assertSame($relatedCatalogItems, $this->basicPage->getRelatedCatalogItems());
    }
}
