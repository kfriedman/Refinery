<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerNewTranslator;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerTranslator;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class MegaMenuItemTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
{
    const DEFAULT_FEATURED_ITEM_ID_PREFIX = 'default-';

    const DEFAULT_FEATURED_ITEM_TYPE = 'featured_item';

    /**
     * @param NDOFilter $ndoFilter
     *
     * @return bool
     */
    public function isDefaultFeaturedItem(NDOFilter $ndoFilter)
    {
        if (strpos($ndoFilter->getFilterID(), 'default-') !== false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $itemId
     *
     * @return mixed
     */
    public function transformDefaultFeaturedItemId($itemId = '')
    {
        $itemId = explode(self::DEFAULT_FEATURED_ITEM_ID_PREFIX, $itemId);

        return array_pop($itemId);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return bool
     */
    public function isDefaultFeaturedItemRawData(ProviderRawData $providerRawData)
    {
        if ($providerRawData->getRawDataArray()['type'] == self::DEFAULT_FEATURED_ITEM_TYPE) {
            return true;
        } else {
            return false;
        }
    }

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
        if ($this->isDefaultFeaturedItem($ndoFilter)) {
            return $provider->clientGet(
                'node/featured_item/' . $this->transformDefaultFeaturedItemId($ndoFilter->getFilterID()),
                null,
                $ndoFilter,
                $allowEmptyResults
            );
        } else {
            return $provider->clientGet(
                'node/scheduled_featured_item/' . $ndoFilter->getFilterID(),
                null,
                $ndoFilter,
                $allowEmptyResults
            );
        }
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\SiteDatum\MegaMenuItem
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\SiteDatum\MegaMenuItem();

        $uuid = $this->getValueFromRawData($providerRawData, 'uuid');

        if ($this->isDefaultFeaturedItemRawData($providerRawData)) {
            $ndo->setNdoID(self::DEFAULT_FEATURED_ITEM_ID_PREFIX . $uuid);
            $ndo->setDefault(true);
        } else {
            $ndo->setNdoID($uuid);
        }

        $ndo->setHeadline($this->getTextGroupFromRawData($providerRawData, 'title_field'));
        $ndo->setLink($this->getTextGroupFromRawData($providerRawData, 'field_url', 'url'));

        if ($category = $this->getTextGroupFromRawData($providerRawData, 'field_fi_category')) {
            $ndo->setCategory($category);
        }

        if ($description = $this->getTextGroupFromRawData($providerRawData, 'field_fi_description')) {
            $ndo->setDescription($description);
        }

        $this->setRelatedContent($providerRawData, $ndo);
        $this->setRelatedImages($providerRawData, $ndo);

        if (!$this->isDefaultFeaturedItemRawData($providerRawData)) {
            $this->setRelatedDates($providerRawData, $ndo);
            $this->setRelatedMegaMenuPane($providerRawData, $ndo);
        }

        return $ndo;
    }

    /**
     * @param ProviderRawData            $providerRawData
     * @param NDO\SiteDatum\MegaMenuItem $ndo
     */
    public function setRelatedContent(ProviderRawData $providerRawData, NDO\SiteDatum\MegaMenuItem $ndo)
    {
        if ($relatedNID = $this->getValueFromRawData($providerRawData, 'field_related_nid')) {
            if ($relatedNodeType = $this->getValueFromRawData($providerRawData, 'field_related_node_type_notrans')) {
                switch ($relatedNodeType) {
                    case 'page':
                        $relatedContentNDO = new NDO\Content\Node($relatedNID);
                        break;
                    case 'blog':
                        $relatedContentNDO = new NDO\Content\Node\Blog($relatedNID);
                        break;
                    case 'event_program':
                        $relatedContentNDO = new NDO\Content\Node\EventProgram($relatedNID);
                        break;
                    case 'event_exhibition':
                        $relatedContentNDO = new NDO\Content\Node\EventExhibition($relatedNID);
                        break;
                }

                if (isset($relatedContentNDO)) {
                    $oldDrupal = array('dev.www.aws.nypl.org', 'qa.www.aws.nypl.org', 'www.nypl.org');
                    $parsedURL = parse_url($this->getValueFromRawData($providerRawData, 'field_url', 'url'));

                    $relatedContentNDO->setEnvironmentName('production');

                    if (in_array($parsedURL['host'], $oldDrupal)) {
                        $relatedContentNDO->setProvider(new RESTAPI\D7RefineryServerCurrent());
                    }

                    $ndo->setRelatedContent($relatedContentNDO);
                }
            }
        }
    }

    /**
     * @param ProviderRawData            $providerRawData
     * @param NDO\SiteDatum\MegaMenuItem $ndo
     */
    protected function setRelatedImages(ProviderRawData $providerRawData, NDO\SiteDatum\MegaMenuItem $ndo)
    {
        if ($this->getValueFromRawData($providerRawData, 'field_fi_square_image', null)) {
            $imageGroup = new NDO\EnhancedImageGroup();
            $imageGroup->append(
                new NDO\Content\EnhancedImage($this->getValueFromRawData($providerRawData, 'uuid') . ':' . 'field_fi_square_image')
            );
            $ndo->setImages($imageGroup);
        }
    }

    /**
     * @param ProviderRawData            $providerRawData
     * @param NDO\SiteDatum\MegaMenuItem $ndo
     */
    protected function setRelatedDates(ProviderRawData $providerRawData, NDO\SiteDatum\MegaMenuItem $ndo)
    {
        if ($schedules = $this->getValueFromRawData($providerRawData, 'field_sfi_schedule', null)) {
            if (isset($schedules[0]['field_sfi_date']['und'][0])) {
                $dates = $schedules[0]['field_sfi_date']['und'][0];

                // Start date
                $ndo->setDisplayDateStart(
                    new NDO\LocalDateTime($dates['value'], new \DateTimeZone($dates['timezone_db']))
                );

                // End date
                $ndo->setDisplayDateEnd(
                    new NDO\LocalDateTime($dates['value2'], new \DateTimeZone($dates['timezone_db']))
                );
            }

            $ndo->setCurrent(true);
        }
    }

    /**
     * @param ProviderRawData            $providerRawData
     * @param NDO\SiteDatum\MegaMenuItem $ndo
     */
    protected function setRelatedMegaMenuPane(ProviderRawData $providerRawData, NDO\SiteDatum\MegaMenuItem $ndo)
    {
        if ($schedule = $this->getValueFromRawData($providerRawData, 'field_sfi_schedule', null)) {
            if (isset($schedule[0]['field_sfi_slot']['und'][0]['uuid'])) {
                $containerSlotUUID = $schedule[0]['field_sfi_slot']['und'][0]['uuid'];
                $ndo->setMegaMenuPane(new NDO\SiteDatum\MegaMenuPane($containerSlotUUID));
            }
        }
    }
}
