<?php
namespace NYPL\Refinery\NDO\Blog;

use NYPL\Refinery\NDO;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class BloggerProfile extends NDO\Profile
{
    use BlogTrait;

    /**
     * @var NDO\TextGroup
     */
    public $profileText;

    /**
     * @var NDO\Person\Author
     */
    public $author;

    /**
     * @param string
     */
    public function setNdoID($ndoID)
    {
        $ndoID = $this->translateFromRelativeUriToId($ndoID);

        parent::setNdoID($ndoID);
    }

    /**
     * @return NDO\TextGroup
     */
    public function getProfileText()
    {
        return $this->profileText;
    }

    /**
     * @param NDO\TextGroup $profileText
     */
    public function setProfileText(NDO\TextGroup $profileText)
    {
        $this->profileText = $profileText;
    }

    /**
     * @return NDO\Person\Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param NDO\Person\Author $author
     */
    public function setAuthor(NDO\Person\Author $author)
    {
        $this->author = $author;
    }
}
