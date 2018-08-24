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
class HeaderItemTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
        return $provider->clientGet(
            'taxonomy/term/header_nav/' . $ndoFilter->getFilterID(),
            null,
            $ndoFilter,
            $allowEmptyResults
        );
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\SiteDatum\HeaderItem
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\SiteDatum\HeaderItem($this->getValueFromRawData($providerRawData, 'uuid'));

        $ndo->setName($this->getTextGroupFromRawData($providerRawData, 'name_field'));
        $ndo->setLink($this->getTextGroupFromRawData($providerRawData, 'field_url', 'url'));

        if ($parentUUID = $this->getValueFromRawData($providerRawData, 'parent_uuid', null, true)) {
            $ndo->setParent(new NDO\SiteDatum\HeaderItem($parentUUID));
        }

        if ($childrenUUIDs = $this->getValueFromRawData($providerRawData, 'children_uuids', null, true)) {
            foreach ($childrenUUIDs as $childUUID) {
                $ndo->setChild($childUUID);
            }
        }

        $ndo->setSort($this->getValueFromRawData($providerRawData, 'weight'));

        if ($relatedContainer = $this->getValueFromRawData($providerRawData, 'field_ers_container', 'uuid')) {
            $relatedFilter = new NDOFilter();
            $relatedFilter->addQueryParameter('filter', array('field_rel_container' => $relatedContainer));

            /**
             * @var NDO\SiteDatum\MegaMenuPaneGroup $megaMenuPanes
             */
            if ($megaMenuPanes = NDOReader::readNDO(
                $providerRawData->getProvider(),
                new NDO\SiteDatum\MegaMenuPaneGroup(),
                $relatedFilter,
                null,
                true
            )) {
                $ndo->setRelatedMegaMenuPanes($megaMenuPanes);
            }

            /**
             * @var NDO\SiteDatum\ContainerSlotGroup $containerSlots
             */
            if ($containerSlots = NDOReader::readNDO(
                $providerRawData->getProvider(),
                new NDO\SiteDatum\ContainerSlotGroup(),
                $relatedFilter,
                null,
                true
            )) {
                $ndo->setRelatedContainerSlots($containerSlots);
            }
        }

        return $ndo;
    }
}
