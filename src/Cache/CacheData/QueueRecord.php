<?php
namespace NYPL\Refinery\Cache\CacheData;

use NYPL\Refinery\Cache\CacheData;
use NYPL\Refinery\Serializer;

/**
 * Class QueueRecord
 *
 * QueueRecords are used to keep track of whether a message has been sent to the queue server.
 * QueueRecords are primarily used to prevent sending duplicate messages to the queue or
 * excessive messages in a given time frame. They generally have a default TTL, derived
 * from configuration file.
 *
 * @package NYPL\Refinery\Cache\CacheData
 */
class QueueRecord extends CacheData
{
    /**
     * QueueRecord is JSON encoded.
     *
     * @var int
     */
    protected $encodingFormat = Serializer::JSON;
}