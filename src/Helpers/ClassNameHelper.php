<?php
namespace NYPL\Refinery\Helpers;

use NYPL\Refinery\Exception\RefineryException;

/**
 * Helper to work with class names.
 *
 * Class names are usually defined with its full namespace representation. ClassNameHelper gives
 * a class name without its namespaces.
 *
 * @package NYPL\Refinery\Helpers
 */
class ClassNameHelper
{
    /**
     * Returns a class name without namespaces.
     *
     * @param object $class Class name with namespace format
     *
     * @return string       Name of class
     * @throws RefineryException
     */
    public static function getNameWithoutNamespace($class)
    {
        if (!is_object($class)) {
            throw new RefineryException('Parameter provided (' . $class . ') is not an object.');
        }

        return self::stripNamespace(get_class($class));
    }

    /**
     * Returns class's parent's name without namespaces.
     *
     * @param object $class Class name with namespace format
     *
     * @return string       Name of class's parent
     * @throws RefineryException
     */
    public static function getParentNameWithoutNamespace($class)
    {
        if (!is_object($class)) {
            throw new RefineryException('Parameter provided (' . $class . ') is not an object.');
        }

        return self::stripNamespace(get_parent_class($class));
    }

    /**
     * Removes namespace separators, returns class name without namespace separators.
     *
     * @param string $className Full class name with namespaces
     *
     * @return string           Class name without namespace separators
     */
    public static function stripNamespace($className = '')
    {
        $className = explode('\\', $className);

        return (string) array_pop($className);
    }
}