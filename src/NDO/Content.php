<?php
namespace NYPL\Refinery\NDO;

use NYPL\Refinery\NDO;

/**
 * Abstract class for a NDO
 *
 * @package NYPL\Refinery\NDO
 */
abstract class Content extends NDO
{
    /**
     * @var LocalDateTime
     */
    public $dateCreated;

    /**
     * @var LocalDateTime
     */
    public $dateModified;

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
     * @return LocalDateTime
     */
    public function getDateModified()
    {
        return $this->dateModified;
    }

    /**
     * @param LocalDateTime $dateModified
     */
    public function setDateModified(LocalDateTime $dateModified)
    {
        $this->dateModified = $dateModified;
    }
}