<?php
namespace NYPL\Refinery\Server\Endpoint\api\nypl\locations\v1_0\amenities;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\ProviderHandler\NDOReader;
use NYPL\Refinery\ProviderInitializer;
use NYPL\Refinery\Server\Endpoint;
use NYPL\Refinery\Server\Endpoint\api\nypl\locations\v1_0\EndpointFormatter;

/**
 * API Endpoint
 *
 * @package NYPL\Refinery\Server\Endpoint
 */
class IndexEndpoint extends Endpoint implements Endpoint\GetInterface
{
    /**
     * @param NDOFilter $filter
     *
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    public function get(NDOFilter $filter)
    {
        $this->setBaseURL('api/nypl/locations/v1.0/amenities');

        $provider = ProviderInitializer::initializeProvider(new Provider\RESTAPI\D7RefineryServerCurrent());

        /**
         * @var NDO\Content\Amenity $amenityNDO
         */
        $amenityNDO = NDOReader::readNDO($provider, new NDO\Content\Amenity(), $filter);

        if ($this->isDebug()) {
            $this->getResponse()->setDebugArray($provider->getProviderRawData()->getRawDataArray());
        }

        if ($amenityNDO) {
            $formattedEndpoint = EndpointFormatter::getFormattedAmenityEndpoint($this, $amenityNDO, true);
        } else {
            $formattedEndpoint = array();
        }

        $this->getResponse()->setCount(count($formattedEndpoint));
        $this->getResponse()->setPage($provider->getPage());
        $this->getResponse()->setPerPage($provider->getPerPage());

        $this->getResponse()->setDataKey('amenity');
        $this->getResponse()->setData($formattedEndpoint);
    }
}