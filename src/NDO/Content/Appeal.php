<?php
namespace NYPL\Refinery\NDO\Content;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class Appeal extends NDO\Content
{
    /**
     * @var int
     */
    public $appealId = 0;

    /**
     * @var string
     */
    public $statement = '';

    /**
     * @var string
     */
    public $title = '';

    /**
     * @var string
     */
    public $buttonTitle = '';

    /**
     * @var NDO\URI
     */
    public $buttonLink;

    /**
     * @var NDO\LocationGroup
     */
    public $locations;

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
    public function getStatement()
    {
        return $this->statement;
    }

    /**
     * @param string $statement
     */
    public function setStatement($statement)
    {
        $this->statement = $statement;
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
     * @return string
     */
    public function getButtonTitle()
    {
        return $this->buttonTitle;
    }

    /**
     * @param string $buttonTitle
     */
    public function setButtonTitle($buttonTitle)
    {
        $this->buttonTitle = $buttonTitle;
    }

    /**
     * @return NDO\URI
     */
    public function getButtonLink()
    {
        return $this->buttonLink;
    }

    /**
     * @param NDO\URI $buttonLink
     */
    public function setButtonLink(NDO\URI $buttonLink)
    {
        $this->buttonLink = $buttonLink;
    }

    /**
     * @return int
     */
    public function getAppealId()
    {
        return $this->appealId;
    }

    /**
     * @param int $appealId
     */
    public function setAppealId($appealId)
    {
        $this->appealId = $appealId;
    }

    /**
     * @return NDO\LocationGroup
     */
    public function getLocations()
    {
        if (!$this->locations) {
            $this->setLocations(new NDO\LocationGroup());
        }

        return $this->locations;
    }

    /**
     * @param NDO\LocationGroup $locations
     */
    public function setLocations(NDO\LocationGroup $locations)
    {
        $this->locations = $locations;
    }

    /**
     * @param NDO\Location $location
     */
    public function addLocation(NDO\Location $location)
    {
        $this->getLocations()->append($location);
    }
}
