<?php
namespace NYPL\Refinery\NDO\Admin;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class UriAlias extends NDO
{
    /**
     * @var string
     */
    public $source;

    /**
     * @var string
     */
    public $alias;

    /**
     * @var string
     */
    public $language;

    /**
     * @var NDO\Content\Page
     */
    public $relatedPage;

    /**
     * @var NDO\PageGlobal
     */
    public $pageGlobal;

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new RESTAPI\D7RefineryServerNew());
        $this->addSupportedReadProvider(new RESTAPI\D7RefineryServerCurrent());
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string
     */
    public function setAlias($alias)
    {
        if (substr($alias, 0, 1) == '/') {
            $alias = substr($alias, 1);
        }

        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return NDO\Content\Page
     */
    public function getRelatedPage()
    {
        return $this->relatedPage;
    }

    /**
     * @param NDO\Content\Page $relatedPage
     */
    public function setRelatedPage(NDO\Content\Page $relatedPage)
    {
        $this->relatedPage = $relatedPage;
    }

    /**
     * @return NDO\PageGlobal
     */
    public function getPageGlobal()
    {
        return $this->pageGlobal;
    }

    /**
     * @param NDO\PageGlobal $pageGlobal
     */
    public function setPageGlobal(NDO\PageGlobal $pageGlobal)
    {
        $this->pageGlobal = $pageGlobal;
    }
}
