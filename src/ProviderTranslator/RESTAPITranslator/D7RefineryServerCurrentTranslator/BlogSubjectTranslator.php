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
class BlogSubjectTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
        return $provider->clientGet('taxonomy/term/blog_subjects/' . $this->translateFromIdToUri($ndoFilter->getFilterID()), null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\Blog\BlogSubject
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\Blog\BlogSubject($this->getValueFromRawData($providerRawData, 'uri_relative', null, true));

        // Setting the name
        $ndo->setName($this->getValueFromRawData($providerRawData, 'name'));

        // Setting the UUID
        $ndo->setUuid($this->getValueFromRawData($providerRawData, 'uuid'));

        // Setting the sort order
        $ndo->setSortOrder($this->getValueFromRawData($providerRawData, 'weight'));

        // Setting the url alias
        $ndo->setAlias($this->getValueFromRawData($providerRawData, 'uri_relative', null, true));

        if ($parentUriRelative = $this->getValueFromRawData($providerRawData, 'parent_uri_relative', null, true)) {
            $parentSubject = new NDO\Blog\BlogSubject($parentUriRelative);
            $ndo->setParent($parentSubject);
        }

        $this->setBlogPosts($providerRawData, $ndo, 'blog-subjects');

        return $ndo;
    }

}
