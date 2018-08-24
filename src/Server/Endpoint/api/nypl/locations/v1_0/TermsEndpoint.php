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
class TermsEndpoint extends Endpoint implements Endpoint\GetInterface
{
    /**
     * @param NDOFilter $filter
     *
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    public function get(NDOFilter $filter)
    {
        $formattedEndpoint = array();

        $provider = ProviderInitializer::initializeProvider(new Provider\RESTAPI\D7RefineryServerCurrent());

        /**
         * @var NDO\MediaGroup $mediaGroupNDO
         */
        $mediaGroupNDO = NDOReader::readNDO($provider, new NDO\MediaGroup(), $filter, null, true);

        if ($this->isDebug()) {
            $this->getResponse()->setDebugArray($provider->getProviderRawData()->getRawDataArray());
        }

        $formattedEndpoint[] = EndpointFormatter::getFormattedMediaEndpoint($mediaGroupNDO);

        /**
         * @var NDO\SubjectOtherGroup $subjectGroupNDO
         */
        $subjectGroupNDO = NDOReader::readNDO($provider, new NDO\SubjectOtherGroup(), $filter, null, true);

        if ($this->isDebug()) {
            $this->getResponse()->setDebugArray($provider->getProviderRawData()->getRawDataArray());
        }

        $formattedEndpoint[] = EndpointFormatter::getFormattedSubjectsEndpoint($subjectGroupNDO);

        $this->getResponse()->setCount(count($formattedEndpoint));
        $this->getResponse()->setPage($provider->getPage());
        $this->getResponse()->setPerPage($provider->getPerPage());

        $this->getResponse()->setDataKey('terms');
        $this->getResponse()->setData($formattedEndpoint);
    }
}