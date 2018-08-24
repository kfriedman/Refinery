<?php
namespace NYPL\Refinery\NDO\Content;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class Alert extends NDO\Content
{
    /**
     * @var string
     */
    public $scope = '';

    /**
     * @var NDO\URI
     */
    public $uri;

    /**
     * @var string
     */
    private $closedMessage = '';

    /**
     * @var string
     */
    private $message = '';

    /**
     * @var NDO\TextGroup
     */
    public $alertText;

    /**
     * @var NDO\TextGroup
     */
    public $closedReason;

    /**
     * @var NDO\LocalDateTime
     */
    public $displayDateStart;

    /**
     * @var NDO\LocalDateTime
     */
    public $displayDateEnd;

    /**
     * @var NDO\LocalDateTime
     */
    public $closingDateStart;

    /**
     * @var NDO\LocalDateTime
     */
    public $closingDateEnd;

    /**
     * @var array
     */
    protected $parentAlertSetArray = array();

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
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param string $scope
     */
    public function setScope($scope)
    {
        if ($scope == 'home') {
            $scope = 'all';
        }

        $this->scope = $scope;
    }

    /**
     * @return string
     */
    public function getClosedMessage()
    {
        return $this->closedMessage;
    }

    /**
     * @param string $closedMessage
     */
    public function setClosedMessage($closedMessage)
    {
        $this->setClosedReason(new NDO\TextGroup(new NDO\Text\TextSingle($closedMessage)));

        $this->closedMessage = $closedMessage;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->setAlertText(new NDO\TextGroup(new NDO\Text\TextSingle($message)));

        $this->message = $message;
    }

    /**
     * @return NDO\LocalDateTime
     */
    public function getDisplayDateStart()
    {
        return $this->displayDateStart;
    }

    /**
     * @param NDO\LocalDateTime $displayDateStart
     */
    public function setDisplayDateStart(NDO\LocalDateTime $displayDateStart)
    {
        $this->displayDateStart = $displayDateStart;
    }

    /**
     * @return NDO\LocalDateTime
     */
    public function getDisplayDateEnd()
    {
        return $this->displayDateEnd;
    }

    /**
     * @param NDO\LocalDateTime $displayDateEnd
     */
    public function setDisplayDateEnd(NDO\LocalDateTime $displayDateEnd)
    {
        $this->displayDateEnd = $displayDateEnd;
    }

    /**
     * @return NDO\LocalDateTime
     */
    public function getClosingDateStart()
    {
        return $this->closingDateStart;
    }

    /**
     * @param NDO\LocalDateTime $closingDateStart
     */
    public function setClosingDateStart(NDO\LocalDateTime $closingDateStart)
    {
        $this->closingDateStart = $closingDateStart;
    }

    /**
     * @return NDO\LocalDateTime
     */
    public function getClosingDateEnd()
    {
        return $this->closingDateEnd;
    }

    /**
     * @param NDO\LocalDateTime $closingDateEnd
     */
    public function setClosingDateEnd(NDO\LocalDateTime $closingDateEnd)
    {
        $this->closingDateEnd = $closingDateEnd;
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
     * @return array
     */
    public function getParentAlertSetArray()
    {
        return $this->parentAlertSetArray;
    }

    /**
     * @param array $parentAlertSetArray
     */
    public function setParentAlertSetArray($parentAlertSetArray)
    {
        $this->parentAlertSetArray = $parentAlertSetArray;
    }

    /**
     * @return NDO\TextGroup
     */
    public function getAlertText()
    {
        return $this->alertText;
    }

    /**
     * @param NDO\TextGroup $alertText
     */
    public function setAlertText(NDO\TextGroup $alertText)
    {
        $this->alertText = $alertText;
    }

    /**
     * @return NDO\TextGroup
     */
    public function getClosedReason()
    {
        return $this->closedReason;
    }

    /**
     * @param NDO\TextGroup $closedReason
     */
    public function setClosedReason(NDO\TextGroup $closedReason)
    {
        $this->closedReason = $closedReason;
    }

    /**
     * @return NDO\LocationGroup
     */
    public function getLocations()
    {
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
        if (!$this->locations) {
            $this->locations = new NDO\LocationGroup();
        }

        $this->getLocations()->append($location);
    }
}