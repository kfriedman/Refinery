<?php
namespace NYPL\Refinery;

use NYPL\Refinery\Cache\CacheData\QueueRecord;
use NYPL\Refinery\Helpers\ClassNameHelper;

/**
 * All QueueMessage classes should extend this abstract class.
 *
 * @package NYPL\Refinery
 */
abstract class QueueMessage
{
    /**
     * Getter for the message body of a queue message.
     *
     * @return string
     */
    abstract public function getMessage();

    /**
     * Method used to take action/process the current message.
     *
     * @return mixed
     */
    abstract public function processMessage();

    /**
     * Method used to get the "type" header that should be set for message;
     * based strictly off the class name (without any namespace).
     *
     * @return string
     * @throws Exception\RefineryException
     */
    public function getMessageType()
    {
        return ClassNameHelper::getNameWithoutNamespace($this);
    }

    /**
     * Check to see a QueueRecord exists for the current message in the cache.
     *
     * @return bool
     */
    public function isQueueRecordExists()
    {
        if ($this->getQueueRecord()->isExists()) {
            return true;
        }

        return false;
    }

    /**
     * Save a QueueRecord for the current message to the cache (without any
     * data payload).
     */
    public function saveQueueRecord()
    {
        $this->getQueueRecord()->save(null);
    }

    /**
     * Return the corresponding QueueRecord for the current message, if it
     * exists.
     *
     * @return QueueRecord
     */
    public function getQueueRecord()
    {
        return new QueueRecord(self::getQueueRecordKey());
    }

    /**
     * Get the key name for the current queue message: a combination of the
     * current message type and a SHA1 hash of the actually message body.
     *
     * @return array
     */
    protected function getQueueRecordKey()
    {
        return [$this->getMessageType(), sha1($this->getMessage())];
    }

    /**
     * @param string $message
     */
    protected function setMessageProperties($message = '')
    {
        $message = unserialize($message);

        if ($message) {
            foreach ($message as $messageName => $messageValue) {
                $this->$messageName = $messageValue;
            }
        }
    }
}
