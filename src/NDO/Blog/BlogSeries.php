<?php
namespace NYPL\Refinery\NDO\Blog;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 */
class BlogSeries extends NDO
{
    use BlogTrait;

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new RESTAPI\D7RefineryServerCurrent());
    }

    /**
     * @var string
     */
    public $title = '';

    /**
     * @var NDO\TextGroup
     */
    public $body;

    /**
     * @var string
     */
    public $type = '';

    /**
     * @var NDO\URI
     */
    public $rssUri;

    /**
     * @var NDO\Content\Image
     */
    public $image;

    /**
     * @var NDO\AudienceGroup
     */
    public $audience;

    /**
     * @var NDO\SubjectGroup
     */
    public $subjects;

    /**
     * Setter for the ID of the NDO.
     *
     * @param string $ndoID
     */
    public function setNdoID($ndoID)
    {
        $ndoID = $this->translateFromRelativeUriToId($ndoID);

        parent::setNdoID($ndoID);
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
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return NDO\Content\Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param NDO\Content\Image $image
     */
    public function setImage(NDO\Content\Image $image)
    {
        $this->image = $image;
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
     * @return NDO\SubjectGroup
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * @param NDO\SubjectGroup $subjects
     */
    public function setSubjects(NDO\SubjectGroup $subjects)
    {
        $this->subjects = $subjects;
    }

    /**
     * @return NDO\TextGroup
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param NDO\TextGroup $body
     */
    public function setBody(NDO\TextGroup $body)
    {
        $this->body = $body;
    }

    /**
     * @return NDO\URI
     */
    public function getRssUri()
    {
        return $this->rssUri;
    }

    /**
     * @param NDO\URI $rssUri
     */
    public function setRssUri(NDO\URI $rssUri)
    {
        $this->rssUri = $rssUri;
    }
}
