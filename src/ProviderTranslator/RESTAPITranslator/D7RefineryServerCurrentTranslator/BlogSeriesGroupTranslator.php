<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerCurrentTranslator;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerTranslator;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class BlogSeriesGroupTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
{
    use BlogTraitTranslator;

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

            return $provider->clientGet('node/channel', null, $urlFilter, $allowEmptyResults);
        } else {
            $ndoFilter->addQueryParameter('filter[field_channel_content_type]', 'blog');

            return $provider->clientGet('node/channel', null, $ndoFilter, $allowEmptyResults);
        }
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return \NYPL\Refinery\NDO\Blog\BlogSeriesGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        return $this->getNDOGroupFromRawData($providerRawData, new NDO\Blog\BlogSeriesGroup(), new BlogSeriesTranslator());
    }
}
