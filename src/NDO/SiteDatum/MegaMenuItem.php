<?php
namespace NYPL\Refinery\NDO\SiteDatum;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI\D7RefineryServerNew;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class MegaMenuItem extends NDO
{
    /**
     * @var NDO\TextGroup
     */
    public $category;

    /**
     * @var NDO\TextGroup
     */
    public $link;

    /**
     * @var NDO\TextGroup
     */
    public $headline;

    /**
     * @var NDO\TextGroup
     */
    public $description;

    /**
     * @var NDO\ImageGroup
     */
    public $images;

    /**
     * @var MegaMenuPane
     */
    public $megaMenuPane;

    /**
     * @var NDO\Content
     */
    public $relatedContent;

    /**
     * @var NDO\LocalDateTime
     */
    public $displayDateStart;

    /**
     * @var NDO\LocalDateTime
     */
    public $displayDateEnd;

    /**
     * @var NDO\Event
     */
    public $relatedEvent;

    /**
     * @var bool
     */
    public $default = false;

    /**
     * @var bool
     */
    public $current = false;

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new D7RefineryServerNew());
    }

    /**
     * @return NDO\TextGroup
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param NDO\TextGroup $category
     */
    public function setCategory(NDO\TextGroup $category)
    {
        $this->category = $category;
    }

    /**
     * @return NDO\TextGroup
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param NDO\TextGroup $link
     */
    public function setLink(NDO\TextGroup $link)
    {
        $this->link = $link;
    }

    /**
     * @return NDO\TextGroup
     */
    public function getHeadline()
    {
        return $this->headline;
    }

    /**
     * @param NDO\TextGroup $headline
     */
    public function setHeadline(NDO\TextGroup $headline)
    {
        $this->headline = $headline;
    }

    /**
     * @return NDO\TextGroup
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param NDO\TextGroup $description
     */
    public function setDescription(NDO\TextGroup $description)
    {
        $this->description = $description;
    }

    /**
     * @return MegaMenuPane
     */
    public function getMegaMenuPane()
    {
        return $this->megaMenuPane;
    }

    /**
     * @param MegaMenuPane $megaMenuPane
     */
    public function setMegaMenuPane(MegaMenuPane $megaMenuPane)
    {
        $this->megaMenuPane = $megaMenuPane;
    }

    /**
     * @return NDO\Content
     */
    public function getRelatedContent()
    {
        return $this->relatedContent;
    }

    /**
     * @param NDO\Content $relatedContent
     */
    public function setRelatedContent(NDO\Content $relatedContent)
    {
        $this->relatedContent = $relatedContent;
    }

    /**
     * @return NDO\Event
     */
    public function getRelatedEvent()
    {
        return $this->relatedEvent;
    }

    /**
     * @param NDO\Event $relatedEvent
     */
    public function setRelatedEvent(NDO\Event $relatedEvent)
    {
        $this->relatedEvent = $relatedEvent;
    }

    /**
     * @return NDO\ImageGroup
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param NDO\ImageGroup $images
     */
    public function setImages(NDO\ImageGroup $images)
    {
        $this->images = $images;
    }

    /**
     * @return NDO\LocalDateTime
     */
    public function getDisplayDateStart()
    {
        return $this->displayDateStart;
    }

    /**
     * @param NDO\LocalDateTime $displayDateStart
     */
    public function setDisplayDateStart(NDO\LocalDateTime $displayDateStart)
    {
        $this->displayDateStart = $displayDateStart;
    }

    /**
     * @return NDO\LocalDateTime
     */
    public function getDisplayDateEnd()
    {
        return $this->displayDateEnd;
    }

    /**
     * @param NDO\LocalDateTime $displayDateEnd
     */
    public function setDisplayDateEnd(NDO\LocalDateTime $displayDateEnd)
    {
        $this->displayDateEnd = $displayDateEnd;
    }

    /**
     * @return boolean
     */
    public function isDefault()
    {
        return $this->default;
    }

    /**
     * @param boolean $default
     */
    public function setDefault($default)
    {
        $this->default = (bool) $default;
    }

    /**
     * @return boolean
     */
    public function isCurrent()
    {
        return $this->current;
    }

    /**
     * @param boolean $current
     */
    public function setCurrent($current)
    {
        $this->current = $current;
    }
}