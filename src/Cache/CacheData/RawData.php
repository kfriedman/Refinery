<?php
namespace NYPL\Refinery\Cache\CacheData;

use NYPL\Refinery\Cache\CacheData;
use NYPL\Refinery\Serializer;

/**
 * Class RawData
 *
 * RawData is data returned from a Provider and cached to increase response speed.
 * Primarily used by the Refinery server.
 *
 * @package NYPL\Refinery\Cache\CacheData
 */
class RawData extends CacheData
{
    /**
     * RawData is BSON encoded.
     *
     * @var int
     */
    protected $encodingFormat = Serializer::BSON;
}
