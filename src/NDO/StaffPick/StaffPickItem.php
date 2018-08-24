<?php
namespace NYPL\Refinery\NDO\StaffPick;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDO\StaffPickTagGroup;
use NYPL\Refinery\Provider\RemoteJSON\StaffPicksServer;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class StaffPickItem extends NDO
{
    public $title = '';

    public $author = '';

    public $catalogSlug = '';

    public $imageSlug = '';

    /**
     * @var NDO\URI
     */
    public $ebookURI;

    /**
     * @var StaffPickTagGroup
     */
    public $tags;

    /**
     * @var NDO\StaffPickGroup
     */
    public $picks;

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new StaffPicksServer());
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
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getCatalogSlug()
    {
        return $this->catalogSlug;
    }

    /**
     * @param string $catalogSlug
     */
    public function setCatalogSlug($catalogSlug)
    {
        $this->catalogSlug = $catalogSlug;
    }

    /**
     * @return string
     */
    public function getImageSlug()
    {
        return $this->imageSlug;
    }

    /**
     * @param string $imageSlug
     */
    public function setImageSlug($imageSlug)
    {
        $this->imageSlug = $imageSlug;
    }

    /**
     * @return StaffPickTagGroup
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param StaffPickTagGroup $tags
     */
    public function setTags(StaffPickTagGroup $tags)
    {
        $this->tags = $tags;
    }

    /**
     * @param StaffPickTag $tag
     */
    public function addTag(StaffPickTag $tag)
    {
        if (!$this->getTags()) {
            $this->setTags(new StaffPickTagGroup());
        }

        $this->getTags()->append($tag);
    }

    /**
     * @return NDO\StaffPickGroup
     */
    public function getPicks()
    {
        return $this->picks;
    }

    /**
     * @param NDO\StaffPickGroup $picks
     */
    public function setPicks(NDO\StaffPickGroup $picks)
    {
        $this->picks = $picks;
    }

    /**
     * @param NDO\StaffPick $pick
     */
    public function addPick(NDO\StaffPick $pick)
    {
        if (!$this->picks) {
            $this->setPicks(new NDO\StaffPickGroup());
        }

        $this->getPicks()->append($pick);
    }

    /**
     * @return NDO\URI
     */
    public function getEbookURI()
    {
        return $this->ebookURI;
    }

    /**
     * @param NDO\URI $ebookURI
     */
    public function setEbookURI(NDO\URI $ebookURI)
    {
        $this->ebookURI = $ebookURI;
    }
}