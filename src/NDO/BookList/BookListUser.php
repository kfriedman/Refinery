<?php
namespace NYPL\Refinery\NDO\BookList;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI\BookListServer;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class BookListUser extends NDO
{
    /**
     * @var string
     */
    public $username = '';

    /**
     * @var string
     */
    public $name = '';

    /**
     * @var NDO\BookListGroup
     */
    public $bookLists;

    /**
     * @var int
     */
    public $userID = 0;

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
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return NDO\BookListGroup
     */
    public function getBookLists()
    {
        return $this->bookLists;
    }

    /**
     * @param NDO\BookListGroup $bookLists
     */
    public function setBookLists(NDO\BookListGroup $bookLists)
    {
        $this->bookLists = $bookLists;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * @param int $userID
     */
    public function setUserID($userID)
    {
        $this->userID = (int) $userID;
    }
}