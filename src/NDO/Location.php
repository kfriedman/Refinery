<?php
namespace NYPL\Refinery\NDO;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Abstract class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 *
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class Location extends NDO
{
    const DEFAULT_CATALOG_URI = 'https://browse.nypl.org';

    /**
     * @var string
     */
    public $fullName = '';

    /**
     * @var string
     */
    public $shortName = '';

    /**
     * @var string
     */
    public $symbol = '';

    /**
     * @var string
     */
    public $slug = '';

    /**
     * @var string
     */
    public $phone = '';

    /**
     * @var string
     */
    public $fax = '';

    /**
     * @var string
     */
    public $tty = '';

    /**
     * @var string
     */
    public $email = '';

    /**
     * @var string
     */
    public $crossStreet = '';

    /**
     * @var URI
     */
    public $mainUri;

    /**
     * @var URI
     */
    public $aboutURI;

    /**
     * @var URI
     */
    public $blogURI;

    /**
     * @var URI
     */
    public $eventsURI;

    /**
     * @var URI
     */
    public $catalogURI;

    /**
     * @var URI
     */
    public $contactURI;

    /**
     * @var URI
     */
    public $conciergeURI;

    /**
     * @var string
     */
    public $accessibility = '';

    /**
     * @var string
     */
    public $accessibilityNote = '';

    /**
     * @var NDO\Content\Address
     */
    public $address;

    /**
     * @var string
     */
    public $locationType = '';

    /**
     * @var URIGroup
     */
    public $relatedLinks;

    /**
     * @var SubjectOtherGroup
     */
    public $subjectGroup;

    /**
     * @var MediaGroup
     */
    public $mediaGroup;

    /**
     * @var array
     */
    public $synonyms = array();

    /**
     * @var array
     */
    public $hours = array();

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new RESTAPI\D7RefineryServerCurrent());
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @param string $shortName
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    }

    /**
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return URI
     */
    public function getAboutURI()
    {
        return $this->aboutURI;
    }

    /**
     * @param URI $aboutURI
     */
    public function setAboutURI(URI $aboutURI)
    {
        $this->aboutURI = $aboutURI;
    }

    /**
     * @return URI
     */
    public function getBlogURI()
    {
        return $this->blogURI;
    }

    /**
     * @param URI $blogURI
     */
    public function setBlogURI(URI $blogURI)
    {
        $this->blogURI = $blogURI;
    }

    /**
     * @return URI
     */
    public function getEventsURI()
    {
        return $this->eventsURI;
    }

    /**
     * @param URI $eventsURI
     */
    public function setEventsURI(URI $eventsURI)
    {
        $this->eventsURI = $eventsURI;
    }

    /**
     * @return URI
     */
    public function getCatalogURI()
    {
        return $this->catalogURI;
    }

    /**
     * @param URI $catalogURI
     */
    public function setCatalogURI(URI $catalogURI)
    {
        if (!$catalogURI->getFullURI()) {
            $catalogURI->setFullURI(self::DEFAULT_CATALOG_URI);
        }

        $this->catalogURI = $catalogURI;
    }

    /**
     * @return URI
     */
    public function getContactURI()
    {
        return $this->contactURI;
    }

    /**
     * @param URI $contactURI
     */
    public function setContactURI(URI $contactURI)
    {
        $this->contactURI = $contactURI;
    }

    /**
     * @return URI
     */
    public function getConciergeURI()
    {
        return $this->conciergeURI;
    }

    /**
     * @param URI $conciergeURI
     */
    public function setConciergeURI(URI $conciergeURI)
    {
        $this->conciergeURI = $conciergeURI;
    }

    /**
     * @return string
     */
    public function getAccessibility()
    {
        return $this->accessibility;
    }

    /**
     * @param string $accessibility
     */
    public function setAccessibility($accessibility)
    {
        $this->accessibility = $accessibility;
    }

    /**
     * @return string
     */
    public function getAccessibilityNote()
    {
        return $this->accessibilityNote;
    }

    /**
     * @param string $accessibilityNote
     */
    public function setAccessibilityNote($accessibilityNote)
    {
        $this->accessibilityNote = (string) $accessibilityNote;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param string $fax
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    }

    /**
     * @return string
     */
    public function getTTY()
    {
        return $this->tty;
    }

    /**
     * @param string $tty
     */
    public function setTty($tty)
    {
        $this->tty = $tty;
    }

    /**
     * @return string
     */
    public function getCrossStreet()
    {
        return $this->crossStreet;
    }

    /**
     * @param string $crossStreet
     */
    public function setCrossStreet($crossStreet)
    {
        $this->crossStreet = $crossStreet;
    }

    /**
     * @return Content\Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Content\Address $address
     */
    public function setAddress(Content\Address $address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getLocationType()
    {
        return $this->locationType;
    }

    /**
     * @param string $locationType
     */
    public function setLocationType($locationType)
    {
        $this->locationType = $locationType;
    }

    /**
     * @return URIGroup
     */
    public function getRelatedLinks()
    {
        return $this->relatedLinks;
    }

    /**
     * @param URIGroup $relatedLinks
     */
    public function setRelatedLinks(URIGroup $relatedLinks)
    {
        $this->relatedLinks = $relatedLinks;
    }

    /**
     * @return SubjectOtherGroup
     */
    public function getSubjectGroup()
    {
        return $this->subjectGroup;
    }

    /**
     * @param SubjectOtherGroup $subjectGroup
     */
    public function setSubjectGroup(SubjectOtherGroup $subjectGroup)
    {
        $this->subjectGroup = $subjectGroup;
    }

    /**
     * @return MediaGroup
     */
    public function getMediaGroup()
    {
        return $this->mediaGroup;
    }

    /**
     * @param MediaGroup $mediaGroup
     */
    public function setMediaGroup(MediaGroup $mediaGroup)
    {
        $this->mediaGroup = $mediaGroup;
    }

    /**
     * @return array
     */
    public function getSynonyms()
    {
        return $this->synonyms;
    }

    /**
     * @param array $synonyms
     */
    public function setSynonyms(array $synonyms)
    {
        $this->synonyms = $synonyms;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return URI
     */
    public function getMainUri()
    {
        return $this->mainUri;
    }

    /**
     * @param URI $mainUri
     */
    public function setMainUri(URI $mainUri)
    {
        $this->mainUri = $mainUri;
    }

    /**
     * @return array
     */
    public function getHours()
    {
        return $this->hours;
    }

    /**
     * @param array $hours
     */
    public function setHours($hours)
    {
        $this->hours = $hours;
    }
}
