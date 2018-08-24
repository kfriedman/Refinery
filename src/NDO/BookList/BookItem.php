<?php
namespace NYPL\Refinery\NDO\BookList;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI\BookListServer;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class BookItem extends NDO
{
    /**
     * @var string
     */
    public $title = '';

    /**
     * @var string
     */
    public $subTitle = '';

    /**
     * @var array
     */
    public $authors = array();

    /**
     * @var array
     */
    public $upcs = array();

    /**
     * @var array
     */
    public $isbns = array();

    /**
     * @var string
     */
    public $format = '';

    /**
     * @var string
     */
    public $note = '';

    /**
     * @var string
     */
    public $publicationDate = '';

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new BookListServer());
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
     * @return array
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * @param array $authors
     */
    public function setAuthors(array $authors)
    {
        $this->authors = $authors;
    }

    /**
     * @return array
     */
    public function getUpcs()
    {
        return $this->upcs;
    }

    /**
     * @param array $upcs
     */
    public function setUpcs(array $upcs)
    {
        $this->upcs = $upcs;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getSubTitle()
    {
        return $this->subTitle;
    }

    /**
     * @param string $subTitle
     */
    public function setSubTitle($subTitle)
    {
        $this->subTitle = $subTitle;
    }

    /**
     * @return array
     */
    public function getIsbns()
    {
        return $this->isbns;
    }

    /**
     * @param array $isbns
     */
    public function setIsbns(array $isbns)
    {
        $this->isbns = $isbns;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * @return string
     */
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    /**
     * @param string $publicationDate
     */
    public function setPublicationDate($publicationDate)
    {
        $this->publicationDate = $publicationDate;
    }
}