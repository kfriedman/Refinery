<?php
namespace NYPL\Refinery\NDO\SiteDatum;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI\D7RefineryServerNew;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class FooterItem extends NDO
{
    /**
     * @var NDO\TextGroup
     */
    public $name;

    /**
     * @var NDO\TextGroup
     */
    public $link;

    /**
     * @var int
     */
    public $sort = 0;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param NDO\TextGroup $name
     */
    public function setName(NDO\TextGroup $name)
    {
        $this->name = $name;
    }

    /**
     * @return NDO\TextGroup
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param NDO\TextGroup $link
     */
    public function setLink(NDO\TextGroup $link)
    {
        $this->link = $link;
    }

    /**
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     */
    public function setSort($sort)
    {
        $this->sort = (int) $sort;
    }
}