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
class LandingPageTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
            'content/pages/landing_page/' . $ndoFilter->getFilterID(),
            null,
            $ndoFilter,
            $allowEmptyResults
        );
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\Content\Page\LandingPage
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\Content\Page\LandingPage($this->getValueFromRawData($providerRawData, 'uuid'));

        if ($heroSectionHeader = $this->getTextGroupFromRawData($providerRawData, 'field_ts_headline')) {
            $ndo->setHeroSectionHeader($heroSectionHeader);
        }

        if ($headerHeadline = $this->getTextGroupFromRawData($providerRawData, 'title_field')) {
            $ndo->setHeroHeadline($headerHeadline);
        }

        if ($heroBody = $this->getTextGroupFromRawData($providerRawData, 'field_ls_text1', 'value')) {
            $ndo->setHeroBody($heroBody);
        }

        if ($bodyHtml = $this->getTextGroupFromRawData($providerRawData, 'body', 'value')) {
            $ndo->setBodyHtml($bodyHtml);
        }

        if ($bodyYaml = $this->getTextGroupFromRawData($providerRawData, 'field_ls_yaml', 'value')) {
            $ndo->setBodyYaml($bodyYaml);
        }

        // Setting the headline image
        if ($headlineImage = $this->getValueFromRawData($providerRawData, 'field_imgs_headline', 'uuid')) {
            $ndo->setHeroImage(new NDO\Content\Image($headlineImage));
        }

        // Setting the body images
        if ($images = $this->getValueFromRawData($providerRawData, 'field_imgm_body', null)) {
            $imageGroup = new NDO\ImageGroup();
            foreach ($images as $language => $imageData) {
                $imageGroup->append(
                    new NDO\Content\Image(
                        $imageData['uuid'],
                        null,
                        $this->getTextGroupFromRawData($providerRawData, 'field_imgm_body', 'alt')
                    ),
                    false,
                    $language
                );
            }
            $ndo->setImages($imageGroup);
        }

        if (($enhanced = $this->getValueFromRawData($providerRawData, '_enhanced')) &&
            !empty($enhanced['uri_relative'])
        ) {
            $urlAlias = ltrim($enhanced['uri_relative'], '/');

            $urlAliasFilter = new NDOFilter();
            $urlAliasFilter->addQueryParameter('filter', array('alias' => $urlAlias));

            /**
             * @var NDO\UriAliasGroup $uriAliases
             */
            if ($uriAliases = NDOReader::readNDO($providerRawData->getProvider(), new NDO\UriAliasGroup(), $urlAliasFilter, null, true)) {
                $ndo->setUrlAlias($uriAliases->items->current());
            }
        }

        $headerItemFilter = new NDOFilter();
        $headerItemFilter->addQueryParameter('filter', array('relationships' => array('landing-page' => $ndo->getNdoID())));
        if ($headerItemGroup = NDOReader::readNDO($providerRawData->getProvider(), new NDO\SiteDatum\HeaderItemGroup(), $headerItemFilter, null, true)) {
            /**
             * @var NDO\SiteDatum\HeaderItemGroup $headerItemGroup
             * @var NDO\SiteDatum\HeaderItem $headerItem
             */
            $headerItem = $headerItemGroup->items->current();

            $ndo->setRelatedHeaderItem($headerItem);

            // Setting the parent related HeaderItem
            if ($parentHeaderItem = $headerItem->getParent()) {
                $ndo->setParentRelatedHeaderItem($parentHeaderItem);
            }
        }

        return $ndo;
    }
}
