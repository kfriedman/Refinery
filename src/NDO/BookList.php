<?php
namespace NYPL\Refinery\NDO;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI\BookListServer;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class BookList extends NDO
{
    /**
     * @var string
     */
    public $listName = '';

    /**
     * @var string
     */
    public $listDescription = '';

    /**
     * @var LocalDateTime
     */
    public $dateCreated;

    /**
     * @var NDO\BookList\BookListUser
     */
    public $user;

    /**
     * @var NDO\BookList\BookListItemGroup
     */
    public $listItems;

    /**
     * @var string
     */
    public $listType = '';

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new BookListServer());
    }

    /**
     * @return LocalDateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param LocalDateTime $dateCreated
     */
    public function setDateCreated(LocalDateTime $dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return BookList\BookListUser
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param BookList\BookListUser $user
     */
    public function setUser(BookList\BookListUser $user)
    {
        $this->user = $user;
    }

    /**
     * @return BookList\BookListItemGroup
     */
    public function getListItems()
    {
        return $this->listItems;
    }

    /**
     * @param BookList\BookListItemGroup $listItems
     */
    public function setListItems(BookList\BookListItemGroup $listItems)
    {
        $this->listItems = $listItems;
    }

    /**
     * @return string
     */
    public function getListType()
    {
        return $this->listType;
    }

    /**
     * @param string $listType
     */
    public function setListType($listType)
    {
        $this->listType = $listType;
    }

    /**
     * @return string
     */
    public function getListName()
    {
        return $this->listName;
    }

    /**
     * @param string $listName
     */
    public function setListName($listName)
    {
        $this->listName = $listName;
    }

    /**
     * @return string
     */
    public function getListDescription()
    {
        return $this->listDescription;
    }

    /**
     * @param string $listDescription
     */
    public function setListDescription($listDescription)
    {
        $this->listDescription = $listDescription;
    }
}