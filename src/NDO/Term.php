<?php
namespace NYPL\Refinery\NDO;

use NYPL\Refinery\NDO;

/**
 * Abstract class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
abstract class Term extends NDO
{
    /**
     * @var string
     */
    public $name = '';

    /**
     * @var TextGroup
     */
    public $nameMultilingual;

    /**
     * @var string
     */
    public $alias = '';

    /**
     * @return string|TextGroup
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string|TextGroup $name
     */
    public function setName($name)
    {
        if (!$this->getNameMultilingual()) {
            $this->setNameMultilingual(new TextGroup(new NDO\Text\TextSingle($name)));
        }

        $this->name = $name;
    }

    /**
     * @return TextGroup
     */
    public function getNameMultilingual()
    {
        return $this->nameMultilingual;
    }

    /**
     * @param TextGroup $nameMultilingual
     */
    public function setNameMultilingual(TextGroup $nameMultilingual)
    {
        $this->nameMultilingual = $nameMultilingual;
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
    public function setAlias($alias)
    {
        if (substr($alias, 0, 1) == '/') {
            $alias = substr($alias, 1);
        }

        $this->alias = $alias;
    }
}
