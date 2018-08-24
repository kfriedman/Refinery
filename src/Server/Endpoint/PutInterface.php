<?php
namespace NYPL\Refinery\Server\Endpoint;

use NYPL\Refinery\NDOFilter;

/**
 * Interface for Endpoint PUT requests.
 *
 * @package NYPL\Refinery\Server
 */
interface PutInterface
{
    /**
     * The method that responds to PUT requests.
     *
     * @param NDOFilter $filter
     */
    public function put(NDOFilter $filter);
}