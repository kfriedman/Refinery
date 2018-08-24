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
class ContainerTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
        return $provider->clientGet('taxonomy/term/containers/' . $ndoFilter->getFilterID(), null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\SiteDatum\Container
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\SiteDatum\Container($this->getValueFromRawData($providerRawData, 'uuid'));

        $ndo->setRead(true);

        // Setting the name
        $ndo->setName($this->getTextGroupFromRawData($providerRawData, 'name_field'));
        $ndo->setSortOrder($this->getValueFromRawData($providerRawData, 'weight'));

        $ndo->setSlug($this->getValueFromRawData($providerRawData, 'field_ts_slug'));

        if ($link = $this->getValueFromRawData($providerRawData, 'field_url', 'url')) {
            $ndo->setLink(new NDO\URI($link));
        }

        // Setting the related Featured Item Types
        if ($relatedFeaturedItemTypes = $this->getValueFromRawData($providerRawData, 'field_rel_featured_item_types', 'uuid')) {
            $ndo->setRelatedFeaturedItemTypes(new NDO\SiteDatum\FeaturedItemTypeGroup($relatedFeaturedItemTypes));
        }

        if ($this->getValueFromRawData($providerRawData, 'depth', null, true) > 1) {
            // Setting the parent container
            if ($parentUUID = $this->getValueFromRawData($providerRawData, 'parent_uuid', null, true)) {
                $ndo->setParent(new NDO\SiteDatum\Container($parentUUID));
            }
        }

        // Setting the children containers
        if ($childrenUUIDs = $this->getValueFromRawData($providerRawData, 'children_uuids', null, true)) {
            foreach ($childrenUUIDs as $childUUID) {
                $ndo->setChild($childUUID);
            }
        }

        // Setting the container slots
        $containerSlotsFilter = new NDOFilter();
        $containerSlotsFilter->addQueryParameter('filter[field_rel_container]', $ndo->getNdoID());

        if ($containerSlots = NDOReader::readNDO($providerRawData->getProvider(), new NDO\SiteDatum\ContainerSlotGroup(), $containerSlotsFilter, null, true)) {
            $ndo->setSlots($containerSlots);
        }

        return $ndo;
    }

}
