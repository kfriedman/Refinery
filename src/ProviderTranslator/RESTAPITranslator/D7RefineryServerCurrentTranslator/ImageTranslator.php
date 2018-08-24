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
class ImageTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
        return $provider->clientGet('file/' . $ndoFilter->getFilterID(), null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\Content\Image
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawData = $providerRawData->getRawDataArray();

        $ndo = new NDO\Content\Image();

        $ndo->setNdoID($rawData['fid']);

        if (isset($rawData['metadata']['width'])) {
            $ndo->setWidth($rawData['metadata']['width']);
        }

        if (isset($rawData['metadata']['height'])) {
            $ndo->setHeight($rawData['metadata']['height']);
        }

        $ndo->setUri(new NDO\URI($rawData[self::ENHANCED_DATA]['file_uri_absolute']));

        if (isset($rawData['filesize'])) {
            if ($rawData['filesize']) {
                $ndo->setFileSize($rawData['filesize']);
            }
        }

        $ndo->setDateCreated(new NDO\LocalDateTime($rawData[self::ENHANCED_DATA]['timestamp']));

        if (isset($rawData['field_file_image_alt_text'])) {
            if ($rawData['field_file_image_alt_text']) {
                $ndo->setAltText(new NDO\TextGroup(array(new NDO\Text\TextSingle($rawData['field_file_image_alt_text']['und'][0]['value']))));
            }
        }

        return $ndo;
    }
}
