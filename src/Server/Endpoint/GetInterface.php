<?php
namespace NYPL\Refinery\Server\Endpoint;

use NYPL\Refinery\NDOFilter;

/**
 * Interface for Endpoint GET requests.
 *
 * @package NYPL\Refinery\Server
 */
interface GetInterface
{
    /**
     * The method that responds to GET requests.
     *
     * @param NDOFilter $filter
     */
    public function get(NDOFilter $filter);
}