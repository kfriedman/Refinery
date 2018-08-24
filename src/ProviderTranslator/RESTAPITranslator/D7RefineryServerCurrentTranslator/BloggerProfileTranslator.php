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
class BloggerProfileTranslator extends ProfileTranslator
{
    use BlogTraitTranslator;

    /**
     * @return NDO\Blog\BloggerProfile
     */
    public function getNDO()
    {
        return new NDO\Blog\BloggerProfile();
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
        return $provider->clientGet('profile/blog/' . $this->translateFromIdToUri($ndoFilter->getFilterID()), null, null, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\Blog\BloggerProfile
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawData = $providerRawData->getRawDataArray();

        $ndo = parent::translate($providerRawData);

        $ndo->setNdoID($this->getValueFromRawData($providerRawData, 'uri_relative', null, true));

        if ($personId = $this->getValueFromRawData($providerRawData, 'person_id')) {
            $ndo->setAuthor(new NDO\Person\Author($personId));
        }

        // Setting the Profile Text
        if (!empty($rawData['field_profile_blog_biography'])) {
            $ndo->setProfileText(
                new NDO\TextGroup(
                    new NDO\Text\TextSingle(
                        $rawData['field_profile_blog_biography'][self::UNDEFINED_LANGUAGE_CODE][0]['value']
                    )
                )
            );
        }

        $this->setBlogPosts($providerRawData, $ndo, 'blog-profiles');

        return $ndo;
    }
}
