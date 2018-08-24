<?php
namespace NYPL\Refinery\NDO\Content\Page;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 *
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BasicPage extends NDO\Content\Page
{
    /**
     * @var NDO\AuthorGroup
     */
    public $authors;
    /**
     * @var NDO\Term\Language
     */
    public $language;
    /**
     * @var string
     */
    public $title = '';
    /**
     * @var string
     */
    public $bodyFull = '';
    /**
     * @var string
     */
    public $bodyShort = '';
    /**
     * @var NDO\URI
     */
    public $URI;
    /**
     * @var int
     */
    public $seriesSequence = 0;

    /**
     * @var NDO\SubjectOtherGroup
     */
    public $subjects;
    /**
     * @var NDO\AudienceGroup
     */
    public $audience;
    /**
     * @var NDO\SeriesGroup
     */
    public $series;

    /**
     * @var NDO\ContentGroup
     */
    public $embeddedContent;

    /**
     * @var NDO\StatusReview
     */
    public $statusReview;
    /**
     * @var NDO\StatusPublishing
     */
    public $statusPublishing;

    /**
     * @var NDO\LocationGroup
     */
    public $relatedLocations;
    /**
     * @var NDO\ContentGroup
     */
    public $relatedContent;
    /**
     * @var NDO\ProgramGroup
     */
    public $relatedPrograms;
    /**
     * @var NDO\DCItemGroup
     */
    public $relatedDCItems;
    /**
     * @var NDO\CatalogItemGroup
     */
    public $relatedCatalogItems;

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new RESTAPI\D7RefineryServerCurrent());
    }

    /**
     * @return NDO\AuthorGroup
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * @param NDO\AuthorGroup $authors
     */
    public function setAuthors(NDO\AuthorGroup $authors)
    {
        $this->authors = $authors;
    }

    /**
     * @return NDO\Term\Language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param NDO\Term\Language $language
     */
    public function setLanguage(NDO\Term\Language $language)
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        if (is_string($title) === false) {
            throw new \InvalidArgumentException('Title (' . $title . ') is not a string.');
        }
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getBodyFull()
    {
        return $this->bodyFull;
    }

    /**
     * @param string $bodyFull
     */
    public function setBodyFull($bodyFull)
    {
        if (is_string($bodyFull) === false) {
            throw new \InvalidArgumentException($bodyFull . " is not a string.");
        }
        $this->bodyFull = $bodyFull;
    }

    /**
     * @return string
     */
    public function getBodyShort()
    {
        return $this->bodyShort;
    }

    /**
     * @param string $bodyShort
     */
    public function setBodyShort($bodyShort)
    {
        if (is_string($bodyShort) === false) {
            throw new \InvalidArgumentException($bodyShort . " is not a string.");
        }
        $this->bodyShort = $bodyShort;
    }

    /**
     * @return NDO\URI
     */
    public function getURI()
    {
        return $this->URI;
    }

    /**
     * @param NDO\URI $uri
     */
    public function setURI(NDO\URI $uri)
    {
        $this->URI = $uri;
    }

    /**
     * @return int
     */
    public function getSeriesSequence()
    {
        return $this->seriesSequence;
    }

    /**
     * @param int $seriesSequence
     */
    public function setSeriesSequence($seriesSequence)
    {
        if (is_int($seriesSequence) === false) {
            throw new \InvalidArgumentException($seriesSequence . " is not an int.");
        }
        $this->seriesSequence = $seriesSequence;
    }

    /**
     * @return NDO\SubjectOtherGroup
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * @param NDO\SubjectOtherGroup $subjects
     */
    public function setSubjects(NDO\SubjectOtherGroup $subjects)
    {
        $this->subjects = $subjects;
    }

    /**
     * @return NDO\AudienceGroup
     */
    public function getAudience()
    {
        return $this->audience;
    }

    /**
     * @param NDO\AudienceGroup $audience
     */
    public function setAudience(NDO\AudienceGroup $audience)
    {
        $this->audience = $audience;
    }

    /**
     * @return NDO\SeriesGroup
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * @param NDO\SeriesGroup $series
     */
    public function setSeries(NDO\SeriesGroup $series)
    {
        $this->series = $series;
    }

    /**
     * @return NDO\ContentGroup
     */
    public function getEmbeddedContent()
    {
        return $this->embeddedContent;
    }

    /**
     * @param NDO\ContentGroup $embeddedContent
     */
    public function setEmbeddedContent(NDO\ContentGroup $embeddedContent)
    {
        $this->embeddedContent = $embeddedContent;
    }

    /**
     * @return NDO\StatusReview
     */
    public function getStatusReview()
    {
        return $this->statusReview;
    }

    /**
     * @param NDO\StatusReview $statusReview
     */
    public function setStatusReview(NDO\StatusReview $statusReview)
    {
        $this->statusReview = $statusReview;
    }

    /**
     * @return NDO\StatusPublishing
     */
    public function getStatusPublishing()
    {
        return $this->statusPublishing;
    }

    /**
     * @param NDO\StatusPublishing $statusPublishing
     */
    public function setStatusPublishing(NDO\StatusPublishing $statusPublishing)
    {
        $this->statusPublishing = $statusPublishing;
    }

    /**
     * @return NDO\LocationGroup
     */
    public function getRelatedLocations()
    {
        return $this->relatedLocations;
    }

    /**
     * @param NDO\LocationGroup $relatedLocations
     */
    public function setRelatedLocations(NDO\LocationGroup $relatedLocations)
    {
        $this->relatedLocations = $relatedLocations;
    }

    /**
     * @return NDO\ContentGroup
     */
    public function getRelatedContent()
    {
        return $this->relatedContent;
    }

    /**
     * @param NDO\ContentGroup $relatedContent
     */
    public function setRelatedContent(NDO\ContentGroup $relatedContent)
    {
        $this->relatedContent = $relatedContent;
    }

    /**
     * @return NDO\ProgramGroup
     */
    public function getRelatedPrograms()
    {
        return $this->relatedPrograms;
    }

    /**
     * @param NDO\ProgramGroup $relatedPrograms
     */
    public function setRelatedPrograms(NDO\ProgramGroup $relatedPrograms)
    {
        $this->relatedPrograms = $relatedPrograms;
    }

    /**
     * @return NDO\DCItemGroup
     */
    public function getRelatedDCItems()
    {
        return $this->relatedDCItems;
    }

    /**
     * @param NDO\DCItemGroup $relatedDCItems
     */
    public function setRelatedDCItems(NDO\DCItemGroup $relatedDCItems)
    {
        $this->relatedDCItems = $relatedDCItems;
    }

    /**
     * @return NDO\CatalogItemGroup
     */
    public function getRelatedCatalogItems()
    {
        return $this->relatedCatalogItems;
    }

    /**
     * @param NDO\CatalogItemGroup $relatedCatalogItems
     */
    public function setRelatedCatalogItems(NDO\CatalogItemGroup $relatedCatalogItems)
    {
        $this->relatedCatalogItems = $relatedCatalogItems;
    }
}
