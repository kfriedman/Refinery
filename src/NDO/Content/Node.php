<?php
namespace NYPL\Refinery\NDO\Content;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class Node extends NDO\Content
{
    /**
     * @var NDO\TextGroup
     */
    public $title;

    /**
     * @var NDO\TextGroup
     */
    public $body;

    /**
     * @var NDO\PersonGroup
     */
    public $authors;

    /**
     * @var NDO\URI
     */
    public $uri;

    /**
     * @var string
     */
    public $alias = '';

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new RESTAPI\D7RefineryServerCurrent());
        $this->addSupportedReadProvider(new RESTAPI\D7RefineryServerNew());
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
     * @return NDO\TextGroup
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param NDO\TextGroup $body
     */
    public function setBody(NDO\TextGroup $body)
    {
        $this->body = $body;
    }

    /**
     * @return NDO\PersonGroup|NDO\AuthorGroup
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * @param NDO\PersonGroup|NDO\AuthorGroup $authors
     */
    public function setAuthors(NDO\PersonGroup $authors)
    {
        $this->authors = $authors;
    }

    /**
     * @return NDO\URI
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param NDO\URI $uri
     */
    public function setUri(NDO\URI $uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     */
    public function setAlias($alias = '')
    {
        if (substr($alias, 0, 1) == '/') {
            $alias = substr($alias, 1);
        }

        $this->alias = $alias;
    }
}
