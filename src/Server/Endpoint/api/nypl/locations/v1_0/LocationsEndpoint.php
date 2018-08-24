<?php
namespace NYPL\Refinery\Server\Endpoint\api\nypl\locations\v1_0;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\ProviderHandler\NDOReader;
use NYPL\Refinery\ProviderInitializer;
use NYPL\Refinery\Server\Endpoint;

/**
 * API Endpoint
 *
 * @package NYPL\Refinery\Server\Endpoint
 */
class LocationsEndpoint extends Endpoint implements Endpoint\GetInterface
{
    /**
     * @param NDOFilter $filter
     *
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    public function get(NDOFilter $filter)
    {
        $provider = ProviderInitializer::initializeProvider(new Provider\RESTAPI\D7RefineryServerCurrent());

        /**
         * @var NDO\LibraryGroup $ndo
         */
        $ndo = NDOReader::readNDO($provider, new NDO\LibraryGroup(), $filter);

        $rawDataArray = $provider->getProviderRawData()->getRawDataArray();

        $formattedEndpoint = array();

        $count = 0;

        $filter->addInclude('alertsForEmbedded');
        $filter->addInclude('amenities');

        /**
         * @var $locationNDO NDO\Location\Library
         */
        foreach ($ndo->items as $locationNDO) {
            $endpoint = new Endpoint\api\nypl\locations\v1_0\locations\IndexEndpoint();
            $endpoint->setBaseURL('api/nypl/locations/v1.0/locations');

            $formattedEndpoint[] = $endpoint->getFormattedEndpoint($locationNDO, $rawDataArray[$count], $filter);
            ++$count;
        }

        if ($this->isDebug()) {
            $this->getResponse()->setDebugArray($provider->getProviderRawData()->getRawDataArray());
        }

        $this->getResponse()->setCount($provider->getCount());
        $this->getResponse()->setPage($provider->getPage());
        $this->getResponse()->setPerPage($provider->getPerPage());

        $this->getResponse()->setDataKey('locations');
        $this->getResponse()->setData($formattedEndpoint);
    }
}