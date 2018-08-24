<?php
namespace NYPL\Refinery\NDO\Content\Node;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class Blog extends NDO\Content\Node
{
    /**
     * @var NDO\Blog\BlogSubjectGroup
     */
    public $blogSubjects;

    /**
     * @var NDO\Blog\BloggerProfileGroup
     */
    public $blogProfiles;

    /**
     * @var NDO\Blog\BlogSeriesGroup
     */
    public $blogSeries;

    /**
     * @var NDO\URI
     */
    public $featuredImage;

    /**
     * @return NDO\Blog\BlogSubjectGroup
     */
    public function getBlogSubjects()
    {
        return $this->blogSubjects;
    }

    /**
     * @param NDO\Blog\BlogSubjectGroup $blogSubjects
     */
    public function setBlogSubjects(NDO\Blog\BlogSubjectGroup $blogSubjects)
    {
        $this->blogSubjects = $blogSubjects;
    }

    /**
     * @return NDO\Blog\BloggerProfileGroup
     */
    public function getBlogProfiles()
    {
        return $this->blogProfiles;
    }

    /**
     * @param NDO\Blog\BloggerProfileGroup $blogProfiles
     */
    public function setBlogProfiles(NDO\Blog\BloggerProfileGroup $blogProfiles)
    {
        $this->blogProfiles = $blogProfiles;
    }

    /**
     * @return NDO\Blog\BlogSeriesGroup
     */
    public function getBlogSeries()
    {
        return $this->blogSeries;
    }

    /**
     * @param NDO\Blog\BlogSeriesGroup $blogSeries
     */
    public function setBlogSeries($blogSeries)
    {
        $this->blogSeries = $blogSeries;
    }

    /**
     * @return NDO\URI
     */
    public function getFeaturedImage()
    {
        return $this->featuredImage;
    }

    /**
     * @param NDO\URI $featuredImage
     */
    public function setFeaturedImage(NDO\URI $featuredImage)
    {
        $this->featuredImage = $featuredImage;
    }
}
