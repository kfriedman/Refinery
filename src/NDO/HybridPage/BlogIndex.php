<?php
namespace NYPL\Refinery\NDO\HybridPage;

use NYPL\Refinery\NDO;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class BlogIndex extends HybridPage
{
    /**
     * @var NDO\SiteDatum\Collection
     */
    public $headerCollection;

    /**
     * @var NDO\Content\Node\BlogGroup
     */
    public $firstPageBlogPosts;

    /**
     * @var NDO\Blog\BlogSubjectGroup
     */
    public $blogSubjects;

    /**
     * @return NDO\SiteDatum\Collection
     */
    public function getHeaderCollection()
    {
        return $this->headerCollection;
    }

    /**
     * @param NDO\SiteDatum\Collection $headerCollection
     */
    public function setHeaderCollection(NDO\SiteDatum\Collection $headerCollection)
    {
        $this->headerCollection = $headerCollection;
    }

    /**
     * @return NDO\Content\Node\BlogGroup
     */
    public function getFirstPageBlogPosts()
    {
        return $this->firstPageBlogPosts;
    }

    /**
     * @param NDO\Content\Node\BlogGroup $firstPageBlogPosts
     */
    public function setFirstPageBlogPosts(NDO\Content\Node\BlogGroup $firstPageBlogPosts)
    {
        $this->firstPageBlogPosts = $firstPageBlogPosts;
    }

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
}
