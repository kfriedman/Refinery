<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerNewTranslator;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderHandler\NDOReader;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerTranslator;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class CollectionTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
{
    /**
     * @param RESTAPI   $provider
     * @param NDOFilter $ndoFilter
     * @param bool      $allowEmptyResults
     *
     * @return string
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    public function read(RESTAPI $provider, NDOFilter $ndoFilter, $allowEmptyResults = false)
    {
        return $provider->clientGet('taxonomy/term/collections/' . $ndoFilter->getFilterID(), null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\SiteDatum\Collection
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\SiteDatum\Collection($this->getValueFromRawData($providerRawData, 'uuid'));

        $ndo->setRead(true);

        // Setting the name
        $ndo->setName($this->getTextGroupFromRawData($providerRawData, 'name_field'));

        // Creating the filter to get the related Containers related to the collection
        $containersFilter = new NDOFilter();
        $containersFilter->addQueryParameter('filter', array('relationships' => array('collection' => $this->getValueFromRawData($providerRawData, 'uuid'))));

        /**
         * Children container
         * @var $container NDO\SiteDatum\ContainerGroup
         */
        if ($container = NDOReader::readNDO($providerRawData->getProvider(), new NDO\SiteDatum\ContainerGroup(), $containersFilter, null, true)) {
            // Getting the collection container itself
            $container = $container->items->current();

            // Getting the top level containers
            $containersFilter = new NDOFilter();
            $containersFilter->addQueryParameter('filter[_enhanced][parent_uuid]', $container->getNdoID());

            if ($topLevelContainers = NDOReader::readNDO($providerRawData->getProvider(), new NDO\SiteDatum\ContainerGroup(), $containersFilter, null, true)) {
                $ndo->setTopLevelContainers($topLevelContainers);
            }
        }

        return $ndo;
    }
}
