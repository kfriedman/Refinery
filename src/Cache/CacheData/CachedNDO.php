<?php
namespace NYPL\Refinery\Cache\CacheData;

use NYPL\Refinery\Cache\CacheData;
use NYPL\Refinery\Serializer;

/**
 * Class CachedNDO
 *
 * CachedNDO is data returned from a Provider and cached to increase response speed.
 * Primarily used by the Refinery server.
 *
 * @package NYPL\Refinery\Cache\CacheData
 */
class CachedNDO extends CacheData
{
    /**
     * @var int
     */
    protected $encodingFormat = Serializer::SERIALIZE;
}
