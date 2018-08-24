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
class ImageTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
{
    protected function getNdo()
    {
        return new NDO\Content\Image();
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
        return $provider->clientGet('file/' . $ndoFilter->getFilterID(), null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\Content\Image
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = $this->getNdo();

        $ndo->setNdoID($this->getValueFromRawData($providerRawData, 'uuid'));

        $ndo->setRead(true);

        if ($uri = $this->getValueFromRawData($providerRawData, 'preferred_derivative_uri', null, true)) {
            $ndo->setUri(new NDO\URI($uri));
        } elseif ($uri = $this->getValueFromRawData($providerRawData, 'file_uri_absolute', null, true)) {
            $ndo->setUri(new NDO\URI($uri));
        }

        $ndo->setFileSize($this->getValueFromRawData($providerRawData, 'filesize'));

        if ($timestamp = $this->getValueFromRawData($providerRawData, 'timestamp', null, true)) {
            $ndo->setDateCreated(new NDO\LocalDateTime($timestamp));
        }

        return $ndo;
    }
}
