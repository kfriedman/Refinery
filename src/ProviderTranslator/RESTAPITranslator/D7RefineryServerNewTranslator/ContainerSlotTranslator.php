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
class ContainerSlotTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
        return $provider->clientGet('taxonomy/term/containers_slots/' . $ndoFilter->getFilterID(), null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\SiteDatum\ContainerSlot
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\SiteDatum\ContainerSlot($this->getValueFromRawData($providerRawData, 'uuid'));

        $ndo->setRead(true);

        // Setting the container
        if ($container = $this->getValueFromRawData($providerRawData, 'field_rel_container', 'uuid')) {
            $ndo->setContainer(new NDO\SiteDatum\Container($container));
        }

        // Setting the current item
        $scheduledFeaturedItemFilter = new NDOFilter();
        $scheduledFeaturedItemFilter->addQueryParameter('filter', array('current-schedules' => array('slot-uuid' => $ndo->getNdoID())));

        /**
         * @var NDO\SiteDatum\ScheduledFeaturedItemGroup $scheduledFeaturedItem
         */
        if ($currentItem = NDOReader::readNDO($providerRawData->getProvider(), new NDO\SiteDatum\ScheduledFeaturedItemGroup(), $scheduledFeaturedItemFilter, null, true)) {
            $ndo->setCurrentItem($currentItem->items->current());
        } else {
            if ($defaultFeaturedItemUUID = $this->getValueFromRawData($providerRawData, 'field_rel_def_featured_item', 'uuid')) {
                if ($currentItem = NDOReader::readNDO($providerRawData->getProvider(), new NDO\SiteDatum\FeaturedItem(), new NDOFilter($defaultFeaturedItemUUID), null, true)) {
                    $ndo->setCurrentItem($currentItem);
                }
            }
        }

        $ndo->setSortOrder($this->getValueFromRawData($providerRawData, 'field_sort_order'));

        return $ndo;
    }

}
