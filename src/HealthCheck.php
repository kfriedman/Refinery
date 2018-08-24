<?php
namespace NYPL\Refinery;

use NYPL\Refinery\Helpers\ClassNameHelper;

/**
 * Abstract class used for all health checks
 *
 * @package NYPL\Refinery
 */
abstract class HealthCheck
{
    /**
     * Whether the check succeeded or not.
     *
     * @var bool|null
     */
    protected $succeeded;

    /**
     * Messages for tests that succeeded.
     *
     * @var array
     */
    protected $messagesSucceeded = array();

    /**
     * Messages for tests that failed.
     *
     * @var array
     */
    protected $messagesFailed = array();

    /**
     * The name of the check.
     *
     * @var string
     */
    protected $checkName = '';

    /**
     * Method to run the check.
     *
     * @return bool
     */
    abstract public function runCheck();

    /**
     * Set a successful test and the message.
     *
     * @param string $message
     *
     * @return bool
     */
    public function setSuccess($message = '')
    {
        if ($this->getSucceeded() !== false) {
            $this->setSucceeded(true);
        }

        if ($message) {
            $this->addMessageSucceeded($message);
        }

        return true;
    }

    /**
     * Set a failed test and the message.
     *
     * @param string $message
     *
     * @return bool
     */
    public function setFailure($message = '')
    {
        $this->setSucceeded(false);

        if ($message) {
            $this->addMessageFailed($message);
        }

        return false;
    }

    /**
     * Getter for whether the check succeeded or not.
     *
     * @return bool|null
     */
    public function getSucceeded()
    {
        return $this->succeeded;
    }

    /**
     * Setter for whether the check succeeded or not.
     *
     * @param bool|null $succeeded
     */
    public function setSucceeded($succeeded)
    {
        $this->succeeded = $succeeded;
    }

    /**
     * Getter for the name of the check.
     *
     * @return string
     */
    public function getCheckName()
    {
        if (!$this->checkName) {
            $this->setCheckName(ClassNameHelper::getNameWithoutNamespace(($this)));
        }

        return $this->checkName;
    }

    /**
     * Setter for the name of the check.
     *
     * @param string $checkName
     */
    public function setCheckName($checkName)
    {
        $this->checkName = $checkName;
    }

    /**
     * Getter for messages for tests that succeeded.
     *
     * @return array
     */
    public function getMessagesSucceeded()
    {
        return $this->messagesSucceeded;
    }

    /**
     * Getter for messages for tests that failed.
     *
     * @return array
     */
    public function getMessagesFailed()
    {
        return $this->messagesFailed;
    }

    /**
     * Add a message for a successful test.
     *
     * @param string $message
     */
    public function addMessageSucceeded($message = '')
    {
        $this->messagesSucceeded[] = $message;
    }

    /**
     * Add a message for a failed test.
     *
     * @param string $message
     */
    public function addMessageFailed($message = '')
    {
        $this->messagesFailed[] = $message;
    }
}