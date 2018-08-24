<?php
namespace NYPL\Refinery\NDO;

use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\Helpers\UrlHelper;
use NYPL\Refinery\NDO;

/**
 * Class to create a URI NDO
 *
 * @package NYPL\Refinery\NDO
 */
class URI extends NDO
{
    /**
     * @var string
     */
    public $fullURI = '';

    /**
     * @var string
     */
    public $description = '';

    /**
     * @param string $fullURI
     * @param string $description
     *
     * @throws RefineryException
     */
    public function __construct($fullURI = '', $description = '')
    {
        parent::__construct();

        if ($fullURI) {
            $this->setFullURI($fullURI);
        }

        if ($description) {
            $this->setDescription($description);
        }
    }

    /**
     * @return string
     */
    public function getFullURI()
    {
        return $this->fullURI;
    }

    /**
     * @param string $fullURI
     */
    public function setFullURI($fullURI = '')
    {
        $this->fullURI = UrlHelper::rewriteMixedUrl($fullURI);
    }

    /**
     * @return string
     */
    public function getURIWithoutHost()
    {
        $urlArray = explode('/', $this->getFullURI());

        return implode('/', array_splice($urlArray, 3));
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}
