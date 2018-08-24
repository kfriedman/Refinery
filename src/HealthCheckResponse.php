<?php
namespace NYPL\Refinery;

use NYPL\Refinery\Exception\RefineryException;

/**
 * Object used to store response for Provider health checks.
 *
 * @package NYPL\Refinery
 */
class HealthCheckResponse
{
    /**
     * Whether the Provider is healthy or not.
     *
     * @var bool
     */
    protected $healthy = false;

    /**
     * Message to explain the health of the Provider.
     *
     * @var string
     */
    protected $message = '';

    /**
     * Constructor for the response.
     *
     * @param bool   $healthy
     * @param string $message
     */
    public function __construct($healthy = false, $message = '')
    {
        $this->setHealthy($healthy);
        $this->setMessage($message);
    }

    /**
     * Getter for whether the Provider is healthy or not.
     *
     * @return boolean
     */
    public function isHealthy()
    {
        return $this->healthy;
    }

    /**
     * Setter for whether the Provider is healthy or not.
     *
     * @param bool $healthy
     *
     * @throws RefineryException
     */
    public function setHealthy($healthy)
    {
        if (!is_bool($healthy)) {
            throw new RefineryException('Healthy status must be true or false');
        }

        $this->healthy = $healthy;
    }

    /**
     * Getter for message to explain the health of the Provider.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Setter for message to explain the health of the Provider.
     *
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}