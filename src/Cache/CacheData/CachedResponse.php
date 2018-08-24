<?php
namespace NYPL\Refinery\Cache\CacheData;

use NYPL\Refinery\Cache\CacheData;
use NYPL\Refinery\Serializer;

/**
 * Class CachedResponse
 *
 * CachedResponse is front-end caching of endpoints.
 * Primarily used by the Refinery server.
 *
 * @package NYPL\Refinery\Cache\CacheData
 */
class CachedResponse extends CacheData
{
    /**
     * @var int
     */
    protected $encodingFormat = Serializer::SERIALIZE;
}