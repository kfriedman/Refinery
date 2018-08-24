<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerCurrentTranslator;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class BloggerProfileGroupTranslator extends ProfileGroupTranslator
{
    use BlogTraitTranslator;

    /**
     * @return NDO\Blog\BloggerProfileGroup
     */
    public function getNDOGroup()
    {
        return new NDO\Blog\BloggerProfileGroup();
    }

    /**
     * @return BloggerProfileTranslator
     */
    public function getTranslator()
    {
        return new BloggerProfileTranslator();
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
        if (!$ndoFilter->getPerPage()) {
            $ndoFilter->setPerPage(25);
        }

        if ($ndoFilter->getFilterID()) {
            $urlFilter = new NDOFilter();
            $urlFilter->addQueryParameter('filter[_enhanced][uri_relative]', implode('|', $this->translateFromIdToUri($ndoFilter->getFilterID())));

            return $provider->clientGet('profile/blog', null, $urlFilter, $allowEmptyResults);
        } else {
            $ndoFilter->addQueryParameter('filter[status]', 1);

            return $provider->clientGet('profile/blog', null, $ndoFilter, $allowEmptyResults);
        }
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\Blog\BloggerProfileGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        return parent::translate($providerRawData);
    }
}
