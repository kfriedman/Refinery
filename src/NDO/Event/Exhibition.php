<?php
namespace NYPL\Refinery\NDO\Event;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a NDO
 */
class Exhibition extends NDO\Event
{
    public $ongoing = false;

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new RESTAPI\D7RefineryServerCurrent());
    }

    /**
     * @return boolean
     */
    public function isOngoing()
    {
        return $this->ongoing;
    }

    /**
     * @param boolean $ongoing
     */
    public function setOngoing($ongoing)
    {
        $this->ongoing = $ongoing;
    }
}
