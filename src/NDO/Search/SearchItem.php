<?php
namespace NYPL\Refinery\NDO\Search;

use NYPL\Refinery\Helpers\UrlHelper;
use NYPL\Refinery\NDO;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class SearchItem extends NDO
{
    public $title = '';

    public $htmlTitle = '';

    public $link = '';

    public $displayLink = '';

    public $snippet = '';

    public $htmlSnippet = '';

    public $formattedUrl = '';

    public $htmlFormattedUrl = '';

    public $labels = [];

    public $thumbnailUrl = '';

    public $openGraphTitle = '';

    public $openGraphDescription = '';

    public $openGraphImageUrl = '';

    public $pageMap = [];

    public $nyplData = [];

    /**
     * @return string
     */
    public function getHtmlTitle()
    {
        return $this->htmlTitle;
    }

    /**
     * @param string $htmlTitle
     */
    public function setHtmlTitle($htmlTitle)
    {
        $this->htmlTitle = $htmlTitle;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = strip_tags($link);
    }

    /**
     * @return string
     */
    public function getDisplayLink()
    {
        return $this->displayLink;
    }

    /**
     * @param string $displayLink
     */
    public function setDisplayLink($displayLink)
    {
        $this->displayLink = $displayLink;
    }

    /**
     * @return string
     */
    public function getSnippet()
    {
        return $this->snippet;
    }

    /**
     * @param string $snippet
     */
    public function setSnippet($snippet)
    {
        $this->snippet = strip_tags($snippet);
    }

    /**
     * @return string
     */
    public function getHtmlSnippet()
    {
        return $this->htmlSnippet;
    }

    /**
     * @param string $htmlSnippet
     */
    public function setHtmlSnippet($htmlSnippet)
    {
        $this->htmlSnippet = $htmlSnippet;
    }

    /**
     * @return string
     */
    public function getFormattedUrl()
    {
        return $this->formattedUrl;
    }

    /**
     * @param string $formattedUrl
     */
    public function setFormattedUrl($formattedUrl)
    {
        $this->formattedUrl = $formattedUrl;
    }

    /**
     * @return string
     */
    public function getHtmlFormattedUrl()
    {
        return $this->htmlFormattedUrl;
    }

    /**
     * @param string $htmlFormattedUrl
     */
    public function setHtmlFormattedUrl($htmlFormattedUrl)
    {
        $this->htmlFormattedUrl = $htmlFormattedUrl;
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
        $this->title = strip_tags($title);
    }

    /**
     * @return array
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @param array $labels
     */
    public function setLabels($labels)
    {
        $this->labels = $labels;
    }

    /**
     * @return string
     */
    public function getThumbnailUrl()
    {
        return $this->thumbnailUrl;
    }

    /**
     * @param string $thumbnailUrl
     */
    public function setThumbnailUrl($thumbnailUrl)
    {
        $this->thumbnailUrl = UrlHelper::rewriteMixedUrl($thumbnailUrl);
    }

    /**
     * @return string
     */
    public function getOpenGraphTitle()
    {
        return $this->openGraphTitle;
    }

    /**
     * @param string $openGraphTitle
     */
    public function setOpenGraphTitle($openGraphTitle)
    {
        $this->openGraphTitle = $openGraphTitle;
    }

    /**
     * @return string
     */
    public function getOpenGraphDescription()
    {
        return $this->openGraphDescription;
    }

    /**
     * @param string $openGraphDescription
     */
    public function setOpenGraphDescription($openGraphDescription)
    {
        $this->openGraphDescription = $openGraphDescription;
    }

    /**
     * @return array
     */
    public function getPageMap()
    {
        return $this->pageMap;
    }

    /**
     * @param array $pageMap
     */
    public function setPageMap(array $pageMap)
    {
        $this->pageMap = $pageMap;
    }

    /**
     * @return string
     */
    public function getOpenGraphImageUrl()
    {
        return $this->openGraphImageUrl;
    }

    /**
     * @param string $openGraphImageUrl
     */
    public function setOpenGraphImageUrl($openGraphImageUrl)
    {
        $this->openGraphImageUrl = UrlHelper::rewriteMixedUrl($openGraphImageUrl);
    }

    /**
     * @return array
     */
    public function getNyplData()
    {
        return $this->nyplData;
    }

    /**
     * @param array $nyplData
     */
    public function setNyplData(array $nyplData)
    {
        $this->nyplData = $nyplData;
    }
}
