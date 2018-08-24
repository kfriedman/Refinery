<?php
namespace NYPL\Refinery\Exception;

use NYPL\Refinery\NewRelicClient;

/**
 * Parent class for all Refinery Exceptions
 *
 * @package NYPL\Refinery\Exception
 */
class RefineryException extends \Exception
{
    const DEFAULT_STATUS_CODE = 500;

    /**
     * @var mixed
     */
    private $addedMessage;

    /**
     * @var int
     */
    private $statusCode = 0;

    /**
     * Creates a new RefineryException with a message and status code, notifying user of errors.
     *
     * @param string       $message      The Exception message to throw.
     * @param int          $statusCode   The status code from RefineryException.
     * @param string|array $addedMessage The added message from RefineryException.
     */
    public function __construct($message = '', $statusCode = 0, $addedMessage = null)
    {
        parent::__construct($message);

        if ($addedMessage) {
            $this->setAddedMessage($addedMessage);
        }

        if ($statusCode) {
            $this->setStatusCode($statusCode);
        } else {
            $this->setStatusCode(self::DEFAULT_STATUS_CODE);
        }

        $this->reportError();
    }

    /**
     * Report an error to New Relic
     */
    public function reportError()
    {
        if ($this->getStatusCode() >= 500) {
            NewRelicClient::reportError($this);
        }
    }

    /**
     * @param bool $isReturnAsString
     *
     * @return string|array
     */
    public function getAddedMessage($isReturnAsString = false)
    {
        if (is_array($this->addedMessage) && $isReturnAsString) {
            return implode(' | ', $this->addedMessage);
        } else {
            return $this->addedMessage;
        }
    }

    /**
     * Adds message for RefineryException
     *
     * @param string|array $addedMessage
     */
    public function setAddedMessage($addedMessage)
    {
        $this->addedMessage = $addedMessage;
    }

    /**
     * Gets status code from RefineryException
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets status code for RefineryException
     *
     * @param int $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = (int) $statusCode;
    }
}
