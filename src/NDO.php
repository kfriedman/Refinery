<?php
namespace NYPL\Refinery;

use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\Helpers\ClassNameHelper;

/**
 * Abstract class for all NDO objects
 *
 * This is the master class for all NDOs in the Refinery.
 *
 * @package NYPL\Refinery
 */
abstract class NDO
{
    /**
     * The type name of the NDO.
     *
     * @var string
     */
    protected $ndoType = '';

    /**
     * The id of the NDO
     *
     * @var array|string
     */
    protected $ndoID;

    /**
     * The parent of this NDO.
     *
     * @var mixed
     */
    protected $parent;

    /**
     * The children of this NDO.
     *
     * @var NDOGroup
     */
    protected $children;

    /**
     * Whether the NDO has been read or not.
     *
     * @var bool
     */
    private $read = false;

    /**
     * Whether the NDO should be included.
     *
     * @var bool
     */
    private $include = false;

    /**
     * An array of Providers that support read operations for this NDO.
     *
     * @var array
     */
    private $supportedReadProviders = array();

    /**
     * @var string
     */
    private $providerName = '';

    /**
     * @var string
     */
    private $environmentName = '';

    /**
     * @var bool
     */
    private $cacheable = true;

    /**
     * Constructor for all NDO objects.
     *
     * @param string $ndoID
     */
    public function __construct($ndoID = '')
    {
        $this->initializeNDO();

        if ($ndoID) {
            $this->setNdoID($ndoID);
        }
    }

    /**
     * Throw an exception if a property name that is not defined is attempted
     * to be set.
     *
     * @param string $parameterName
     * @param mixed  $parameterValue
     *
     * @throws RefineryException
     */
    public function __set($parameterName = '', $parameterValue = null)
    {
        throw new RefineryException('Property (' . $parameterName . ' = ' . $parameterValue . ') is not valid for this NDO (' . $this->getNdoType() . ')');
    }

    /**
     * Initializer called for all NDO objects.
     */
    public function initializeNDO()
    {
        $this->setNdoType($this->getNDOName());
    }

    /**
     * Get the name of the NDO derived from the actual class name.
     *
     * @return string
     *
     * @throws RefineryException
     */
    private function getNDOName()
    {
        return ClassNameHelper::getNameWithoutNamespace($this);
    }

    /**
     * Get array of Providers that supports read operations for this NDO.
     *
     * @return array
     */
    public function getSupportedReadProviders()
    {
        if (method_exists($this, 'setSupportedProviders')) {
            $this->setSupportedProviders();
        }

        return $this->supportedReadProviders;
    }

    /**
     * Add a Provider to the list of read Providers for this NDO.
     *
     * @param Provider $provider
     */
    protected function addSupportedReadProvider(Provider $provider)
    {
        $this->supportedReadProviders[] = $provider;
    }

    /**
     * Getter for the type of the NDO.
     *
     * @return string
     */
    public function getNdoType()
    {
        return $this->ndoType;
    }

    /**
     * Setter for the type of the NDO.
     *
     * @param string $ndoType
     *
     * @throws RefineryException
     */
    public function setNdoType($ndoType = '')
    {
        if ($this->ndoType) {
            throw new RefineryException('Can not redeclare NDO type (attempted to redeclare as ' . $ndoType . '; already ' . $this->ndoType . ')');
        }

        $this->ndoType = $ndoType;
    }

    /**
     * Getter for the ID of the NDO.
     *
     * @return string
     */
    public function getNdoID()
    {
        return $this->ndoID;
    }

    /**
     * Setter for the ID of the NDO.
     *
     * @param string $ndoID
     */
    public function setNdoID($ndoID)
    {
        $this->ndoID = (string) $ndoID;
    }

    /**
     * Getter for whether the NDO has been read or not.
     *
     * @return boolean
     */
    public function isRead()
    {
        return $this->read;
    }

    /**
     * Setter for whether the NDO has been read or not.
     *
     * @param boolean $read
     */
    public function setRead($read)
    {
        $this->read = $read;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return boolean
     */
    public function isInclude()
    {
        return $this->include;
    }

    /**
     * @param boolean $include
     */
    public function setInclude($include)
    {
        $this->include = $include;
    }

    /**
     * @param mixed $parent
     *
     * @throws RefineryException
     */
    public function setParent($parent)
    {
        if (!$parent instanceof $this) {
            throw new RefineryException('Parent (' . ClassNameHelper::getNameWithoutNamespace($parent) . ') is not an instance of child (' . ClassNameHelper::getNameWithoutNamespace($this) . ')');
        }

        $this->parent = $parent;
    }

    /**
     * @return string
     */
    public function getProviderName()
    {
        return $this->providerName;
    }

    /**
     * @param string $providerName
     */
    protected function setProviderName($providerName)
    {
        $this->providerName = $providerName;
    }

    /**
     * @param Provider $provider
     */
    public function setProvider(Provider $provider)
    {
        $this->setProviderName(get_class($provider));
    }

    /**
     * @return NDOGroup
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param NDOGroup $children
     */
    public function setChildren(NDOGroup $children)
    {
        $this->children = $children;
    }

    /**
     * @param string $newID
     */
    public function setChild($newID = '')
    {
        if (!$this->getChildren()) {
            $newNDOGroupName = get_class($this) . 'Group';
            $this->setChildren(new $newNDOGroupName);
        }

        $newNDOName = get_class($this);
        $newNDO = new $newNDOName($newID);

        $this->getChildren()->append($newNDO);
    }

    /**
     * @return string
     */
    public function getEnvironmentName()
    {
        return $this->environmentName;
    }

    /**
     * @param string $environmentName
     */
    public function setEnvironmentName($environmentName)
    {
        $this->environmentName = $environmentName;
    }

    /**
     * @return boolean
     */
    public function isCacheable()
    {
        return $this->cacheable;
    }

    /**
     * @param boolean $cacheable
     */
    public function setCacheable($cacheable)
    {
        $this->cacheable = $cacheable;
    }
}
