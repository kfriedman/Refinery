<?php
namespace NYPL\Refinery\NDO\StaffPick;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RemoteJSON\StaffPicksServer;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class StaffPickTag extends NDO
{
    public $tag = '';

    /**
     * @var NDO\StaffPickGroup
     */
    public $picks;

    /**
     * Set supported Provider(s) for this NDO
     */
    protected function setSupportedProviders()
    {
        $this->addSupportedReadProvider(new StaffPicksServer());
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    /**
     * @return NDO\StaffPickGroup
     */
    public function getPicks()
    {
        return $this->picks;
    }

    /**
     * @param NDO\StaffPickGroup $picks
     */
    public function setPicks(NDO\StaffPickGroup $picks)
    {
        $this->picks = $picks;
    }

    /**
     * @param NDO\StaffPick $pick
     */
    public function addPick(NDO\StaffPick $pick)
    {
        if (!$this->picks) {
            $this->setPicks(new NDO\StaffPickGroup());
        }

        $this->getPicks()->append($pick);
    }
}