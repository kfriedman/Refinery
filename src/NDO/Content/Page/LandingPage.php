<?php
namespace NYPL\Refinery\NDO\Content\Page;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

class LandingPage extends NDO\Content\Page
{
    /**
     * @var NDO\TextGroup
     */
    public $heroSectionHeader;

    /**
     * @var NDO\Content\Image
     */
    public $heroImage;

    /**
     * @var NDO\TextGroup
     */
    public $heroHeadline;

    /**
     * @var NDO\TextGroup
     */
    public $heroBody;

    /**
     * @var NDO\TextGroup
     */
    public $bodyHtml;

    /**
     * @var NDO\TextGroup
     */
    public $bodyYaml;

    /**
     * @var NDO\ImageGroup
     */
    public $images;

    /**
     * @var NDO\Admin\UriAlias
     */
    public $urlAlias;

    /**
     * @var NDO\SiteDatum\HeaderItem
     */
    public $relatedHeaderItem;

    /**
     * @var NDO\SiteDatum\HeaderItem
     */
    public $parentRelatedHeaderItem;

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new RESTAPI\D7RefineryServerNew());
    }

    /**
     * @return NDO\TextGroup
     */
    public function getHeroHeadline()
    {
        return $this->heroHeadline;
    }

    /**
     * @param NDO\TextGroup $headerHeadline
     */
    public function setHeroHeadline(NDO\TextGroup $headerHeadline)
    {
        $this->heroHeadline = $headerHeadline;
    }

    /**
     * @return NDO\TextGroup
     */
    public function getHeroSectionHeader()
    {
        return $this->heroSectionHeader;
    }

    /**
     * @param NDO\TextGroup $heroSectionHeader
     */
    public function setHeroSectionHeader(NDO\TextGroup $heroSectionHeader)
    {
        $this->heroSectionHeader = $heroSectionHeader;
    }

    /**
     * @return NDO\TextGroup
     */
    public function getBodyHtml()
    {
        return $this->bodyHtml;
    }

    /**
     * @param NDO\TextGroup $body
     */
    public function setBodyHtml(NDO\TextGroup $body)
    {
        $this->bodyHtml = $body;
    }

    /**
     * @return NDO\Content\Image
     */
    public function getHeroImage()
    {
        return $this->heroImage;
    }

    /**
     * @param NDO\Content\Image $heroImage
     */
    public function setHeroImage(NDO\Content\Image $heroImage)
    {
        $this->heroImage = $heroImage;
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
     * @return NDO\Admin\UriAlias
     */
    public function getUrlAlias()
    {
        return $this->urlAlias;
    }

    /**
     * @param NDO\Admin\UriAlias $urlAlias
     */
    public function setUrlAlias(NDO\Admin\UriAlias $urlAlias)
    {
        $this->urlAlias = $urlAlias;
    }

    /**
     * @return NDO\TextGroup
     */
    public function getHeroBody()
    {
        return $this->heroBody;
    }

    /**
     * @param NDO\TextGroup $heroBody
     */
    public function setHeroBody(NDO\TextGroup $heroBody)
    {
        $this->heroBody = $heroBody;
    }

    /**
     * @return NDO\TextGroup
     */
    public function getBodyYaml()
    {
        return $this->bodyYaml;
    }

    /**
     * @param NDO\TextGroup $bodyYaml
     */
    public function setBodyYaml(NDO\TextGroup $bodyYaml)
    {
        $this->bodyYaml = $bodyYaml;
    }

    /**
     * @return NDO\SiteDatum\HeaderItem
     */
    public function getRelatedHeaderItem()
    {
        return $this->relatedHeaderItem;
    }

    /**
     * @param NDO\SiteDatum\HeaderItem
     */
    public function setRelatedHeaderItem(NDO\SiteDatum\HeaderItem $relatedHeaderItem)
    {
        $this->relatedHeaderItem = $relatedHeaderItem;
    }

    /**
     * @return NDO\SiteDatum\HeaderItem
     */
    public function getParentRelatedHeaderItem()
    {
        return $this->parentRelatedHeaderItem;
    }

    /**
     * @param NDO\SiteDatum\HeaderItem
     */
    public function setParentRelatedHeaderItem(NDO\SiteDatum\HeaderItem $parentRelatedHeaderItem)
    {
        $this->parentRelatedHeaderItem = $parentRelatedHeaderItem;
    }
}
