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
class AlertsEndpoint extends Endpoint implements Endpoint\GetInterface
{
    /**
     * @param NDOFilter $filter
     *
     * @throws \NYPL\Refinery\Exception\RefineryException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function get(NDOFilter $filter)
    {
        $provider = ProviderInitializer::initializeProvider(new Provider\RESTAPI\D7RefineryServerCurrent());

        $alertFilter = new NDOFilter();
        $alertFilter->setPerPage(null);
        $alertFilter->addQueryParameter('filter[field_alert_type]', 'home|all');

        /**
         * @var NDO\AlertGroup $alertGroupNDO
         */
        $alertGroupNDO = NDOReader::readNDO($provider, new NDO\AlertGroup(), $alertFilter);

        if ($this->isDebug()) {
            $this->getResponse()->setDebugArray($provider->getProviderRawData()->getRawDataArray());
        }

        $formattedEndpoint = EndpointFormatter::getFormattedAlertsEndpoint($alertGroupNDO);

        $this->getResponse()->setCount(count($formattedEndpoint));
        $this->getResponse()->setPage($provider->getPage());
        $this->getResponse()->setPerPage($provider->getPerPage());

        $this->getResponse()->setDataKey('alerts');
        $this->getResponse()->setData($formattedEndpoint);
    }
}