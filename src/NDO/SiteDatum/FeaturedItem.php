<?php
namespace NYPL\Refinery\NDO\SiteDatum;

use NYPL\Refinery\Helpers\UrlHelper;
use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI\D7RefineryServerNew;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class FeaturedItem extends NDO
{
    /**
     * @var NDO\TextGroup
     */
    public $title;

    /**
     * @var string
     */
    public $url;

    /**
     * @var NDO\TextGroup
     */
    public $category;

    /**
     * @var NDO\TextGroup
     */
    public $description;

    /**
     * @var string
     */
    public $date;

    /**
     * @var string
     */
    protected $time;

    /**
     * @var NDO\Content\Image
     */
    public $bannerImage;

    /**
     * @var NDO\Content\Image
     */
    public $mobileBannerImage;

    /**
     * @var NDO\Content\Image
     */
    public $squareImage;

    /**
     * @var NDO\Content\Image
     */
    public $rectangularImage;

    /**
     * @var NDO\Content\Image
     */
    public $bookCoverImage;

    /**
     * @var string
     */
    public $location = '';

    /**
     * @var NDO\TextGroup
     */
    public $bannerShortTitle;

    /**
     * @var string
     */
    public $personFirstName = '';

    /**
     * @var string
     */
    public $personLastName = '';

    /**
     * @var string
     */
    public $personTitle = '';

    /**
     * @var NDO\TextGroup
     */
    public $mediaSeries;

    public $authorName = '';

    /**
     * @var NDO\TextGroup
     */
    public $audience;

    /**
     * @var NDO\TextGroup
     */
    public $genre;

    /**
     * @var ContainerGroup
     */
    public $containers;

    /**
     * @var NDO\Term\MediaType
     */
    public $mediaType;

    /**
     * @var NDO\Content\Node
     */
    public $relatedNode;

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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param NDO\TextGroup $title
     */
    public function setTitle(NDO\TextGroup $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = UrlHelper::rewriteMixedUrl($url);
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
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param string $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return NDO\Content\Image
     */
    public function getBannerImage()
    {
        return $this->bannerImage;
    }

    /**
     * @param NDO\Content\Image $bannerImage
     */
    public function setBannerImage(NDO\Content\Image $bannerImage)
    {
        $this->bannerImage = $bannerImage;
    }

    /**
     * @return NDO\Content\Image
     */
    public function getRectangularImage()
    {
        return $this->rectangularImage;
    }

    /**
     * @param NDO\Content\Image $rectangularImage
     */
    public function setRectangularImage(NDO\Content\Image $rectangularImage)
    {
        $this->rectangularImage = $rectangularImage;
    }

    /**
     * @return NDO\Content\Image
     */
    public function getBookCoverImage()
    {
        return $this->bookCoverImage;
    }

    /**
     * @param NDO\Content\Image $bookcoverImage
     */
    public function setBookCoverImage(NDO\Content\Image $bookcoverImage)
    {
        $this->bookCoverImage = $bookcoverImage;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return NDO\TextGroup
     */
    public function getBannerShortTitle()
    {
        return $this->bannerShortTitle;
    }

    /**
     * @param NDO\TextGroup $bannerShortTitle
     */
    public function setBannerShortTitle(NDO\TextGroup $bannerShortTitle)
    {
        $this->bannerShortTitle = $bannerShortTitle;
    }

    /**
     * @return string
     */
    public function getPersonFirstName()
    {
        return $this->personFirstName;
    }

    /**
     * @param string $personFirstName
     */
    public function setPersonFirstName($personFirstName)
    {
        $this->personFirstName = $personFirstName;
    }

    /**
     * @return string
     */
    public function getPersonLastName()
    {
        return $this->personLastName;
    }

    /**
     * @param string $personLastName
     */
    public function setPersonLastName($personLastName)
    {
        $this->personLastName = $personLastName;
    }

    /**
     * @return string
     */
    public function getPersonTitle()
    {
        return $this->personTitle;
    }

    /**
     * @param string $personTitle
     */
    public function setPersonTitle($personTitle)
    {
        $this->personTitle = $personTitle;
    }

    /**
     * @return NDO\TextGroup
     */
    public function getMediaSeries()
    {
        return $this->mediaSeries;
    }

    /**
     * @param NDO\TextGroup $mediaSeries
     */
    public function setMediaSeries(NDO\TextGroup $mediaSeries)
    {
        $this->mediaSeries = $mediaSeries;
    }

    /**
     * @return ContainerGroup
     */
    public function getContainers()
    {
        return $this->containers;
    }

    /**
     * @param ContainerGroup $containers
     */
    public function setContainers(ContainerGroup $containers)
    {
        $this->containers = $containers;
    }

    /**
     * @return NDO\Content\Image
     */
    public function getSquareImage()
    {
        return $this->squareImage;
    }

    /**
     * @param NDO\Content\Image $squareImage
     */
    public function setSquareImage(NDO\Content\Image $squareImage)
    {
        $this->squareImage = $squareImage;
    }

    /**
     * @return NDO\Term\MediaType
     */
    public function getMediaType()
    {
        return $this->mediaType;
    }

    /**
     * @param NDO\Term\MediaType $mediaType
     */
    public function setMediaType(NDO\Term\MediaType $mediaType)
    {
        $this->mediaType = $mediaType;
    }

    /**
     * @return NDO\Content\Node
     */
    public function getRelatedNode()
    {
        return $this->relatedNode;
    }

    /**
     * @param NDO\Content\Node $relatedNode
     */
    public function setRelatedNode(NDO\Content\Node $relatedNode)
    {
        $this->relatedNode = $relatedNode;
    }

    /**
     * @return NDO\Content\Image
     */
    public function getMobileBannerImage()
    {
        return $this->mobileBannerImage;
    }

    /**
     * @param NDO\Content\Image $mobileBannerImage
     */
    public function setMobileBannerImage(NDO\Content\Image $mobileBannerImage)
    {
        $this->mobileBannerImage = $mobileBannerImage;
    }

    /**
     * @return string
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * @param string $authorName
     */
    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;
    }

    /**
     * @return NDO\TextGroup
     */
    public function getAudience()
    {
        return $this->audience;
    }

    /**
     * @param NDO\TextGroup $audience
     */
    public function setAudience(NDO\TextGroup $audience)
    {
        $this->audience = $audience;
    }

    /**
     * @return NDO\TextGroup
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @param NDO\TextGroup $genre
     */
    public function setGenre(NDO\TextGroup $genre)
    {
        $this->genre = $genre;
    }
}
