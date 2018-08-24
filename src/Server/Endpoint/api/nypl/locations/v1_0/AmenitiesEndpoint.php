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
class AmenitiesEndpoint extends Endpoint implements Endpoint\GetInterface
{
    /**
     * @param NDOFilter $filter
     *
     * @throws \NYPL\Refinery\Exception\RefineryException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function get(NDOFilter $filter)
    {
        $this->setBaseURL('api/nypl/locations/v1.0/amenities');

        $provider = ProviderInitializer::initializeProvider(new Provider\RESTAPI\D7RefineryServerCurrent());

        $amenitiesFilter = new NDOFilter();
        $amenitiesFilter->setPerPage(null);

        /**
         * @var NDO\AmenityGroup $amenityGroupNDO
         */
        $amenityGroupNDO = NDOReader::readNDO($provider, new NDO\AmenityGroup(), $amenitiesFilter, null, true);

        if ($this->isDebug()) {
            $this->getResponse()->setDebugArray($provider->getProviderRawData()->getRawDataArray());
        }

        $formattedEndpoint = EndpointFormatter::getFormattedAmenitiesEndpoint($this, $amenityGroupNDO);

        $this->getResponse()->setCount(count($formattedEndpoint));
        $this->getResponse()->setPage($provider->getPage());
        $this->getResponse()->setPerPage($provider->getPerPage());

        $this->getResponse()->setDataKey('amenities');
        $this->getResponse()->setData($formattedEndpoint);
    }
}