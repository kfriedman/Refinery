<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerCurrentTranslator;

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
class FeatureTranslator extends D7RefineryServerTranslator implements
  RESTAPITranslator\ReadInterface
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
        return $provider->clientGet('node/feature/' . $ndoFilter->getFilterID(), null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\Content\Feature
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawData = $providerRawData->getRawDataArray();

        $ndo = new NDO\Content\Feature();

        $ndo->setRead(true);

        $ndo->setNdoID((int) $rawData['nid']);
        $ndo->setTitle($rawData['title']);
        if ($rawData['field_feature_text']) {
            $ndo->setBody($rawData['field_feature_text']['und'][0]['safe_value']);
        }
        $ndo->setSortOrder($rawData['field_weight']['und'][0]['value']);

        if (isset($rawData[self::ENHANCED_DATA]['field_feature_link_absolute'])) {
            $ndo->setUri(new NDO\URI($rawData[self::ENHANCED_DATA]['field_feature_link_absolute']));
        }

        if (isset($rawData[self::ENHANCED_DATA]['feature_image_uri'])) {
            $ndo->setImage(new NDO\Content\Image(null, new NDO\URI($rawData[self::ENHANCED_DATA]['feature_image_uri'])));
        }

        return $ndo;
    }
}
