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
class AuthorGroupTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
        if ($ndoFilter->getFilterID()) {
            $urlFilter = new NDOFilter();
            $urlFilter->addQueryParameter('filter[person_id]', implode('|', $ndoFilter->getFilterID()));

            return $provider->clientGet('person', null, $urlFilter, $allowEmptyResults);
        } else {
            return $provider->clientGet('person', null, $ndoFilter, $allowEmptyResults);
        }
    }


    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\AuthorGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        return $this->getNDOGroupFromRawData($providerRawData, new NDO\AuthorGroup(), new AuthorTranslator());
    }
}