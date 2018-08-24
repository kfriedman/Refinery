<?php
namespace NYPL\Refinery\Server\Endpoint\api\nypl\locations;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Server\Endpoint;

/**
 * API Endpoint
 *
 * @package NYPL\Refinery\Server\Endpoint
 */
class IndexEndpoint extends Endpoint implements Endpoint\GetInterface
{
    /**
     * @param NDOFilter $filter
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function get(NDOFilter $filter)
    {
        $this->getResponse()->setHtml(file_get_contents('../templates/locations_api.tpl.php'));
    }
}
