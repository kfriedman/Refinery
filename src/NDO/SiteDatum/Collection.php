<?php
namespace NYPL\Refinery\NDO\SiteDatum;

use NYPL\Refinery\NDO;
use NYPL\Refinery\Provider\RESTAPI\D7RefineryServerNew;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class Collection extends NDO
{
    /**
     * @var NDO\TextGroup
     */
    public $name;

    /**
     * @var ContainerGroup
     */
    public $topLevelContainers;

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
     * @return ContainerGroup
     */
    public function getTopLevelContainers()
    {
        return $this->topLevelContainers;
    }

    /**
     * @param ContainerGroup $top_level_containers
     */
    public function setTopLevelContainers(ContainerGroup $topLevelContainers)
    {
        $this->topLevelContainers = $topLevelContainers;
    }

}
