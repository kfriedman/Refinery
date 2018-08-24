<?php
namespace NYPL\Refinery\Server\Endpoint\api\nypl\locations\v1_0\divisions;

use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\ProviderHandler\NDOReader;
use NYPL\Refinery\Server\Endpoint\api\nypl\locations\v1_0\LocationDivisionEndpoint;

/**
 * API Endpoint
 *
 * @package NYPL\Refinery\Server\Endpoint
 */
class IndexEndpoint extends LocationDivisionEndpoint
{
    /**
     * @param NDOFilter $filter
     *
     * @return mixed
     * @throws RefineryException
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    public function getLocationNDO(NDOFilter $filter)
    {
        if (!$filter->getFilterID()) {
            throw new RefineryException('Division was not specified', 400);
        } else {
            $this->setBaseURL('api/nypl/locations/v1.0/divisions');

            $slugFilter = new NDOFilter();
            $slugFilter->addQueryParameter('filter[_enhanced][slug]', strtolower($filter->getFilterID()));

            /**
             * @var NDO\DivisionGroup $divisionGroupNDO
             */
            $divisionGroupNDO = NDOReader::readNDO($this->getProvider(), new NDO\DivisionGroup(), $slugFilter);

            return $divisionGroupNDO->items->current();
        }
    }
}
