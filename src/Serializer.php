<?php
namespace NYPL\Refinery;

/**
 * Class used to abstract array serialization; primarily for storing data in the
 * cache.
 *
 * @package NYPL\Refinery
 */
class Serializer
{
    /**
     * JSON format
     */
    const JSON = 0;

    /**
     * BSON format
     */
    const BSON = 1;

    /**
     * PHP Serialization format
     */
    const SERIALIZE = 2;

    /**
     * Encode an array into serialized format.
     *
     * @param mixed $data
     * @param int   $encodingMethod
     *
     * @return mixed|null|string
     */
    public static function encode($data, $encodingMethod = 1)
    {
        self::checkConfig($encodingMethod);

        switch ($encodingMethod) {
            case self::SERIALIZE:
                return serialize($data);
                break;
            case self::BSON:
                return bson_encode($data);
                break;
            case self::JSON:
                return json_encode($data);
                break;
        }

        return null;
    }

    /**
     * Decode data from serialized format into an array.
     *
     * @param mixed $data
     * @param int   $encodingMethod
     *
     * @return array|null
     */
    public static function decode($data, $encodingMethod = 1)
    {
        if ($data) {
            self::checkConfig($encodingMethod);

            switch ($encodingMethod) {
                case self::SERIALIZE:
                    return unserialize($data);
                    break;
                case self::JSON:
                    return json_decode($data, true);
                    break;
                case self::BSON:
                    return bson_decode($data);
                    break;
            }
        }

        return null;
    }


    /**
     * Check to make sure configuration (e.g. extensions installed) are
     * compatible with requested encoding method.
     *
     * @param int $encodingMethod
     *
     * @throws Exception\RefineryException
     */
    protected static function checkConfig($encodingMethod = 0)
    {
        switch ($encodingMethod) {
            case self::BSON:
                DIManager::getConfig()->checkExtension('Serializer');
                break;
        }
    }
}
