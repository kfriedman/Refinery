<?php
namespace NYPL\Refinery\Server\Endpoint;

/**
 * Interface for Endpoint GET requests.
 *
 * @package NYPL\Refinery\Server
 */
interface PostInterface
{
    /**
     * The method that responds to GET requests.
     *
     * @return mixed
     */
    public function post();
}