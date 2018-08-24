<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerNewTranslator;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerTranslator;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerCurrentTranslator;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class FeaturedItemTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
{
    /**
     * @var NDO\SiteDatum\FeaturedItem
     */
    protected $returnNDO;

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
        return $provider->clientGet('node/featured_item/' . $ndoFilter->getFilterID(), null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\SiteDatum\FeaturedItem
     */
    public function translate(ProviderRawData $providerRawData)
    {
        if (!$this->getReturnNDO()) {
            $this->setReturnNDO(new NDO\SiteDatum\FeaturedItem());
        }

        $ndo = clone $this->getReturnNDO();

        $ndo->setNdoID($this->getValueFromRawData($providerRawData, 'uuid'));

        $ndo->setRead(true);

        $ndo->setCategory($this->getTextGroupFromRawData($providerRawData, 'field_fi_category'));
        $ndo->setTitle($this->getTextGroupFromRawData($providerRawData, 'title_field'));
        $ndo->setLocation($this->getValueFromRawData($providerRawData, 'field_fi_location_notrans'));
        $ndo->setBannerShortTitle($this->getTextGroupFromRawData($providerRawData, 'field_fi_banner_short_title'));
        $ndo->setUrl($this->getValueFromRawData($providerRawData, 'field_url', 'url'));
        $ndo->setPersonFirstName($this->getValueFromRawData($providerRawData, 'field_person_first_name_notrans'));
        $ndo->setPersonLastName($this->getValueFromRawData($providerRawData, 'field_person_last_name_notrans'));
        $ndo->setPersonTitle($this->getValueFromRawData($providerRawData, 'field_person_title_notrans'));
        $ndo->setMediaSeries($this->getTextGroupFromRawData($providerRawData, 'field_fi_media_series'));
        $ndo->setDescription($this->getTextGroupFromRawData($providerRawData, 'field_fi_description'));
        $ndo->setDate($this->getTextGroupFromRawData($providerRawData, 'field_fi_date_text'));

        $ndo->setAuthorName($this->getValueFromRawData($providerRawData, 'field_ts_name'));

        $ndo->setAudience($this->getTextGroupFromRawData($providerRawData, 'field_ts_audience'));
        $ndo->setGenre($this->getTextGroupFromRawData($providerRawData, 'field_ts_genre'));

        // Setting the Banner image
        if ($this->getValueFromRawData($providerRawData, 'field_fi_banner_image', 'uuid')) {
            $ndo->setBannerImage(new NDO\Content\EnhancedImage($ndo->getNdoID() . ':' . 'field_fi_banner_image'));
        }

        // Setting the Banner image
        if ($this->getValueFromRawData($providerRawData, 'field_fi_banner_mobile_image', 'uuid')) {
            $ndo->setMobileBannerImage(new NDO\Content\EnhancedImage($ndo->getNdoID() . ':' . 'field_fi_banner_mobile_image'));
        }

        // Setting the Rectangular Image
        if ($this->getValueFromRawData($providerRawData, 'field_fi_square_image', 'uuid')) {
            $ndo->setSquareImage(new NDO\Content\EnhancedImage($ndo->getNdoID() . ':' . 'field_fi_square_image'));
        }

        // Setting the Rectangular Image
        if ($this->getValueFromRawData($providerRawData, 'field_fi_rect_image', 'uuid')) {
            $ndo->setRectangularImage(new NDO\Content\EnhancedImage($ndo->getNdoID() . ':' . 'field_fi_rect_image'));
        }

        // Setting the Book Cover Image
        if ($this->getValueFromRawData($providerRawData, 'field_fi_bookcover_image', 'uuid')) {
            $ndo->setBookCoverImage(new NDO\Content\EnhancedImage($ndo->getNdoID() . ':' . 'field_fi_bookcover_image'));
        }

        // Setting the Display Containers
        if ($displayContainers = $this->getValueFromRawData($providerRawData, 'field_display_containers', 'uuid')) {
            $ndo->setContainers(new NDO\SiteDatum\ContainerGroup($displayContainers));
        }

        // Setting the Media Type
        if ($mediaTypeUUID = $this->getValueFromRawData($providerRawData, 'field_media_type', 'uuid')) {
            $ndo->setMediaType(new NDO\Term\MediaType($mediaTypeUUID));
        }

        // Setting the Related Node
        if ($nodeType = $this->getValueFromRawData($providerRawData, 'field_related_node_type_notrans')) {
            switch ($nodeType) {
                case 'blog':
                    $blogNdo = new NDO\Content\Node\Blog($this->getValueFromRawData($providerRawData, 'field_related_nid'));
                    $blogNdo->setProvider(new RESTAPI\D7RefineryServerCurrent());

                    $ndo->setRelatedNode($blogNdo);
                    break;
            }
        }

        return $ndo;
    }

    /**
     * @return NDO\SiteDatum\FeaturedItem
     */
    public function getReturnNDO()
    {
        return $this->returnNDO;
    }

    /**
     * @param NDO\SiteDatum\FeaturedItem $returnNDO
     */
    public function setReturnNDO(NDO\SiteDatum\FeaturedItem $returnNDO)
    {
        $this->returnNDO = $returnNDO;
    }

    /**
     * return void
     */
    public function clearReturnNDO()
    {
        $this->returnNDO = null;
    }
}
