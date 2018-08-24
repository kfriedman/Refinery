<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerNewTranslator;

use NYPL\Refinery\NDO\Content\EnhancedImage;
use NYPL\Refinery\NDO\Text\TextSingle;
use NYPL\Refinery\NDO\TextGroup;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderRawData;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class EnhancedImageTranslator extends ImageTranslator
{
    protected function getNdo()
    {
        return new EnhancedImage();
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
        return $provider->clientGet('node_file/' . $ndoFilter->getFilterID(), null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return EnhancedImage
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawData = $providerRawData->getRawDataArray();

        /**
         * @var EnhancedImage $nodeImage
         */
        $nodeImage = parent::translate($providerRawData);

        $nodeImage->setNdoID($rawData[self::ENHANCED_DATA]['node_uuid'] . ':' . $rawData[self::ENHANCED_DATA]['field_name']);

        $nodeImage->setMimeType($rawData['filemime']);

        $altTextGroup = new TextGroup();

        foreach ($rawData[self::ENHANCED_DATA]['node_data'] as $languageCode => $nodeData) {
            $nodeData = current($nodeData);

            if (isset($nodeData['width'])) {
                $nodeImage->setWidth($nodeData['width']);
            }

            if (isset($nodeData['height'])) {
                $nodeImage->setHeight($nodeData['height']);
            }

            if (isset($nodeData['alt'])) {
                if ($nodeData['alt']) {
                    $altText = new TextSingle($nodeData['alt'], $languageCode);

                    $altTextGroup->append($altText);
                }
            }
        }

        if ($altTextGroup->itemsExist()) {
            $nodeImage->setAltText($altTextGroup);
        }

        return $nodeImage;
    }
}
