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
class MegaMenuPaneTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
     * @return NDO\SiteDatum\HeaderItem
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\SiteDatum\MegaMenuPane($this->getValueFromRawData($providerRawData, 'uuid'));

        $ndo->setSort($this->getValueFromRawData($providerRawData, 'weight'));

        $this->setCurrentMegaMenuItem($providerRawData, $ndo);
        $this->setDefaultMegaMenuItem($providerRawData, $ndo);

        return $ndo;
    }

    /**
     * @param ProviderRawData            $providerRawData
     * @param NDO\SiteDatum\MegaMenuPane $ndo
     */
    protected function setCurrentMegaMenuItem(ProviderRawData $providerRawData, NDO\SiteDatum\MegaMenuPane $ndo)
    {
        $filter = new NDOFilter();
        $filter->addQueryParameter('filter', array('container-slot' => $ndo->getNdoID()));

        /**
         * @var NDO\SiteDatum\MegaMenuItemGroup $megaMenuItemGroup
         */
        if ($megaMenuItemGroup = NDOReader::readNDO($providerRawData->getProvider(), new NDO\SiteDatum\MegaMenuItemGroup(), $filter, null, true)) {
            $ndo->setCurrentMegaMenuItem($megaMenuItemGroup->items->current());
        }
    }

    /**
     * @param ProviderRawData            $providerRawData
     * @param NDO\SiteDatum\MegaMenuPane $ndo
     */
    protected function setDefaultMegaMenuItem(ProviderRawData $providerRawData, NDO\SiteDatum\MegaMenuPane $ndo)
    {
        if ($defaultFeaturedItemUUID = $this->getValueFromRawData($providerRawData, 'field_rel_def_featured_item', 'uuid')) {
            $defaultFeaturedItemUUID = MegaMenuItemTranslator::DEFAULT_FEATURED_ITEM_ID_PREFIX . $defaultFeaturedItemUUID;

            $ndo->setDefaultMegaMenuItem(new NDO\SiteDatum\MegaMenuItem($defaultFeaturedItemUUID));
        }
    }
}
