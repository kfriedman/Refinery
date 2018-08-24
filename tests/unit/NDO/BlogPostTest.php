<?php
namespace NYPL\Refinery;


use NYPL\Refinery\NDO\AudienceGroup;
use NYPL\Refinery\NDO\AuthorGroup;
use NYPL\Refinery\NDO\Blog\BlogSeriesGroup;
use NYPL\Refinery\NDO\CatalogItemGroup;
use NYPL\Refinery\NDO\Content\Image;
use NYPL\Refinery\NDO\Content\Page\BlogPost;
use NYPL\Refinery\NDO\ContentGroup;
use NYPL\Refinery\NDO\DCItemGroup;
use NYPL\Refinery\NDO\LocationGroup;
use NYPL\Refinery\NDO\ProgramGroup;
use NYPL\Refinery\NDO\SeriesGroup;
use NYPL\Refinery\NDO\StatusPublishing;
use NYPL\Refinery\NDO\StatusReview;
use NYPL\Refinery\NDO\SubjectOtherGroup;
use NYPL\Refinery\NDO\Term\Language;
use NYPL\Refinery\NDO\URI;

class BlogPostTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var BlogPost
   */
  private $blogPost;

  public function setUp()
  {
    $this->blogPost = new BlogPost();
    $this->assertInstanceOf('\NYPL\Refinery\NDO\Content\Page\BlogPost', $this->blogPost);
  }

  public function testGetAuthorsByDefaultIsNull()
  {
    $this->assertNull($this->blogPost->getAuthors());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetAuthorsThrowsExceptionOnNonAuthorGroupParameter()
  {
    $this->blogPost->setAuthors('Not authors');
  }

  public function testSetAuthorsAcceptsAuthorGroupParameter()
  {
    $authors = new AuthorGroup();
    $this->blogPost->setAuthors($authors);
    $this->assertSame($authors, $this->blogPost->getAuthors());
  }

  public function testGetLanguageByDefaultIsNull()
  {
    $this->assertNull($this->blogPost->getLanguage());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetLanguageThrowsExceptionOnNonLanguageParameter()
  {
    $this->blogPost->setLanguage('Not a language');
  }

  public function testSetLanguageAcceptsLanguageParameter()
  {
    $language = new Language();
    $this->blogPost->setLanguage($language);
    $this->assertSame($language, $this->blogPost->getLanguage());
  }

  public function testGetTitleByDefaultIsBlank()
  {
    $this->assertSame('', $this->blogPost->getTitle());
  }

  /**
   * @expectedException \InvalidArgumentException
   */
  public function testSetTitleThrowsExceptionOnNonStringParameter()
  {
    $this->blogPost->setTitle(1234);
  }

  public function testSetTitleAcceptsStringParameter()
  {
    $title = 'I can be any string';
    $this->blogPost->setTitle($title);
    $this->assertSame($title, $this->blogPost->getTitle());
  }

  public function testGetBodyFullByDefaultIsBlank()
  {
    $this->assertSame('', $this->blogPost->getBodyFull());
  }

  /**
   * @expectedException \InvalidArgumentException
   */
  public function testSetBodyFullThrowsExceptionOnNonStringParameter()
  {
    $this->blogPost->setBodyFull(5678);
  }

  public function testSetBodyFullAcceptsStringParameter()
  {
    $bodyFull = 'Full body text.';
    $this->blogPost->setBodyFull($bodyFull);
    $this->assertSame($bodyFull, $this->blogPost->getBodyFull());
  }

  public function testGetBodyShortByDefaultIsBlank()
  {
    $this->assertSame('', $this->blogPost->getBodyShort());
  }

  /**
   * @expectedException \InvalidArgumentException
   */
  public function testSetBodyShortThrowsExceptionOnNonStringParameter()
  {
    $this->blogPost->setBodyShort(9090);
  }

  public function testSetBodyShortAcceptsStringParameter()
  {
    $bodyShort = 'Body Short text.';
    $this->blogPost->setBodyShort($bodyShort);
    $this->assertSame($bodyShort, $this->blogPost->getBodyShort());
  }

  public function testGetURIByDefaultIsNull()
  {
    $this->assertNull($this->blogPost->getURI());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetURIThrowsExceptionOnNonURIParameter()
  {
    $this->blogPost->setURI('Not a URI');
  }

  public function testSetURIAcceptsURIParameter()
  {
    $uri = new URI();
    $this->blogPost->setURI($uri);
    $this->assertSame($uri, $this->blogPost->getURI());
  }

  public function testGetSeriesSequenceByDefaultIsZero()
  {
    $this->assertSame(0, $this->blogPost->getSeriesSequence());
  }

  /**
   * @expectedException \InvalidArgumentException
   */
  public function testSetSeriesSequenceThrowsExceptionOnNonIntegerParameter()
  {
    $this->blogPost->setSeriesSequence('abc');
  }

  public function testSetSeriesSequenceAcceptsIntegerParameter()
  {
    $seriesSequence = 11;
    $this->blogPost->setSeriesSequence($seriesSequence);
    $this->assertSame($seriesSequence, $this->blogPost->getSeriesSequence());
  }

  public function testGetSubjectsByDefaultIsNull()
  {
    $this->assertNull($this->blogPost->getSubjects());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetSubjectsThrowsExceptionOnNonSubjectGroupParameter()
  {
    $this->blogPost->setSubjects('Not a subject group');
  }

  public function testSetSubjectsAcceptsSubjectGroupParameter()
  {
    $subjects = new SubjectOtherGroup();
    $this->blogPost->setSubjects($subjects);
    $this->assertSame($subjects, $this->blogPost->getSubjects());
  }

  public function testGetAudienceByDefaultIsNull()
  {
    $this->assertNull($this->blogPost->getAudience());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetAudienceThrowsExceptionOnNonAudienceGroupParameter()
  {
    $this->blogPost->setAudience('Not audience');
  }

  public function testSetAudienceAcceptsAudienceGroupParameter()
  {
    $audience = new AudienceGroup();
    $this->blogPost->setAudience($audience);
    $this->assertSame($audience, $this->blogPost->getAudience());
  }

  public function testGetSeriesByDefaultIsNull()
  {
    $this->assertNull($this->blogPost->getSeries());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetSeriesThrowsExceptionOnNonSeriesGroupParameter()
  {
    $this->blogPost->setSeries('Not a series group.');
  }

  public function testSetSeriesAcceptsSeriesGroupParameter()
  {
    $series = new SeriesGroup();
    $this->blogPost->setSeries($series);
    $this->assertSame($series, $this->blogPost->getSeries());
  }

  public function testGetEmbeddedContentByDefaultIsNull()
  {
    $this->assertNull($this->blogPost->getEmbeddedContent());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetEmbeddedContentThrowsExceptionOnNonContentGroupParameter()
  {
    $this->blogPost->setEmbeddedContent('Not content group.');
  }

  public function testSetEmbeddedContentAcceptsContentGroupParameter()
  {
    $embeddedContent = new ContentGroup();
    $this->blogPost->setEmbeddedContent($embeddedContent);
    $this->assertSame($embeddedContent, $this->blogPost->getEmbeddedContent());
  }

  public function testGetStatusReviewByDefaultIsNull()
  {
    $this->assertNull($this->blogPost->getStatusReview());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetStatusReviewThrowsExceptionOnNonStatusReviewParameter()
  {
    $this->blogPost->setStatusReview('Not status review.');
  }

  public function testSetStatusReviewAcceptsStatusReviewParameter()
  {
    $statusReview = new StatusReview();
    $this->blogPost->setStatusReview($statusReview);
    $this->assertSame($statusReview, $this->blogPost->getStatusReview());
  }

  public function testGetStatusPublishingByDefaultIsNull()
  {
    $this->assertNull($this->blogPost->getStatusPublishing());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetStatusPublishingThrowsExceptionOnNonStatusPublishingParameter()
  {
    $this->blogPost->setStatusPublishing('Not Status Publishing.');
  }

  public function testSetStatusPublishingAcceptsStatusPublishingParameter()
  {
    $statusPublishing = new StatusPublishing();
    $this->blogPost->setStatusPublishing($statusPublishing);
    $this->assertSame($statusPublishing, $this->blogPost->getStatusPublishing());
  }

  public function testGetRelatedLocationsByDefaultIsNull()
  {
    $this->assertNull($this->blogPost->getRelatedLocations());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetRelatedLocationsThrowsExceptionOnNonLocationGroupParameter()
  {
    $this->blogPost->setRelatedLocations('Not LocationGroup.');
  }

  public function testSetRelatedLocationsAcceptsLocationGroupParameter()
  {
    $relatedLocations = new LocationGroup();
    $this->blogPost->setRelatedLocations($relatedLocations);
    $this->assertSame($relatedLocations, $this->blogPost->getRelatedLocations());
  }

  public function testGetRelatedContentByDefaultIsNull()
  {
    $this->assertNull($this->blogPost->getRelatedContent());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetRelatedContentThrowsExceptionOnNonContentGroupParameter()
  {
    $this->blogPost->setRelatedContent('Not ContentGroup.');
  }

  public function testSetRelatedContentAcceptsContentGroupParameter()
  {
    $relatedContent = new ContentGroup();
    $this->blogPost->setRelatedContent($relatedContent);
    $this->assertSame($relatedContent, $this->blogPost->getRelatedContent());
  }

  public function testGetRelatedProgramsByDefaultIsNull()
  {
    $this->assertNull($this->blogPost->getRelatedPrograms());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetRelatedProgramsThrowsExceptionOnNonProgramGroupParameter()
  {
    $this->blogPost->setRelatedPrograms('Not ProgramGroup.');
  }

  public function testSetRelatedProgramsAcceptsProgramGroupParameter()
  {
    $relatedPrograms = new ProgramGroup();
    $this->blogPost->setRelatedPrograms($relatedPrograms);
    $this->assertSame($relatedPrograms, $this->blogPost->getRelatedPrograms());
  }

  public function testGetRelatedDCItemsByDefaultIsNull()
  {
    $this->assertNull($this->blogPost->getRelatedDCItems());
  }

  public function testSetRelatedDCItemsAcceptsDCItemGroupParameter()
  {
    $relatedDCItems = new DCItemGroup();
    $this->blogPost->setRelatedDCItems($relatedDCItems);
    $this->assertSame($relatedDCItems, $this->blogPost->getRelatedDCItems());
  }

  public function testGetRelatedCatalogItemsByDefaultIsNull()
  {
    $this->assertNull($this->blogPost->getRelatedCatalogItems());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetRelatedCatalogItemsThrowsExceptionOnNonCatalogItemGroupParameter()
  {
    $this->blogPost->setRelatedDCItems(new ContentGroup());
  }

  public function testSetRelatedCatalogItemsAcceptsCatalogItemGroupParameter()
  {
    $relatedCatalogItems = new CatalogItemGroup();
    $this->blogPost->setRelatedCatalogItems($relatedCatalogItems);
    $this->assertSame($relatedCatalogItems, $this->blogPost->getRelatedCatalogItems());
  }

  public function testGetRelativeURIByDefaultIsNull()
  {
    $this->assertNull($this->blogPost->getRelativeURI());
  }

  public function testSetRelativeURI()
  {
    $relativeURI = 'I can be any string.';
    $this->blogPost->setRelativeURI($relativeURI);
    $this->assertSame($relativeURI, $this->blogPost->getRelativeURI());
  }

  public function testGetHighlightImageByDefaultIsNull()
  {
    $this->assertNull($this->blogPost->getHighlightImage());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testSetHighlightImageThrowsExceptionOnNonImageParameter()
  {
    $this->blogPost->setHighlightImage(new AuthorGroup());
  }

  public function testSetHighlightImageAcceptsImageParameter()
  {
    $highlightImage = new Image();
    $this->blogPost->setHighlightImage($highlightImage);
    $this->assertSame($highlightImage, $this->blogPost->getHighlightImage());
  }
}
