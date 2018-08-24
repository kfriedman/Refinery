<?php
namespace NYPL\Refinery\NDO\Blog;

use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\NDO;
use NYPL\Refinery\NDO\Content\Node\BlogGroup;

/**
 * Class to create a NDO
 */
trait BlogTrait
{
    /**
     * @var BlogGroup
     */
    public $blogPosts;

    /**
     * @return BlogGroup
     */
    public function getBlogPosts()
    {
        return $this->blogPosts;
    }

    /**
     * @param BlogGroup $blogPosts
     */
    public function setBlogPosts(BlogGroup $blogPosts)
    {
        $this->blogPosts = $blogPosts;
    }

    /**
     * @param string $relativeUri
     *
     * @return string
     * @throws RefineryException
     */
    protected function translateFromRelativeUriToId($relativeUri = '')
    {
        if (strpos($relativeUri, '/') !== false) {
            if ($this instanceof BloggerProfile || $this instanceof BlogSubject) {
                $uriArray = explode('/', $relativeUri);

                return array_pop($uriArray);
            }

            if ($this instanceof BlogSeries) {
                $uriArray = explode('/voices/blogs/blog-channels/', $relativeUri);

                return str_replace('/', '@', array_pop($uriArray));
            }

            throw new RefineryException('No matching translation found for relative URI (' . $relativeUri . ')');
        } else {
            return $relativeUri;
        }
    }
}
