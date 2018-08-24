<?php
namespace NYPL\Refinery\Server\Endpoint\api\nypl\locations\v1_0\locations;

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
     * @return NDO\Location\Library
     * @throws RefineryException
     */
    public function getLocationNDO(NDOFilter $filter)
    {
        if (!$filter->getFilterID()) {
            throw new RefineryException('Library was not specified', 400);
        } else {
            $this->setBaseURL('api/nypl/locations/v1.0/locations');

            if (is_numeric($filter->getFilterID())) {
                return NDOReader::readNDO($this->getProvider(), new NDO\Location\Library(), $filter, null, true);
            } else {
                $locationFilter = new NDOFilter();
                $locationFilter->addQueryParameter('filter[_enhanced][slug]', strtolower($filter->getFilterID()));

                /**
                 * @var NDO\LibraryGroup $libraryGroupNDO
                 */
                $libraryGroupNDO = NDOReader::readNDO($this->getProvider(), new NDO\LibraryGroup(), $locationFilter);

                return $libraryGroupNDO->items->current();
            }
        }
    }
}
