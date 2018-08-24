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
class BlogTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
        return $provider->clientGet('node/blog/' . $ndoFilter->getFilterID(), null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return \NYPL\Refinery\NDO\Content\Node\Blog
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\Content\Node\Blog();

        $ndo->setNdoID($this->getValueFromRawData($providerRawData, 'uuid'));

        $ndo->setTitle($this->getTextGroupFromRawData($providerRawData, 'title'));

        $ndo->setBody($this->getTextGroupFromRawData($providerRawData, 'body', 'safe_value', 'safe_summary'));

        if ($uri = $this->getValueFromRawData($providerRawData, 'uri_absolute', null, true)) {
            $ndo->setUri(new NDO\URI($uri));
        }

        $ndo->setAlias($this->getValueFromRawData($providerRawData, 'uri_relative', null, true));

        if ($subjects = $this->getValueFromRawData($providerRawData, 'subjects_uri_relative', null, true)) {
            $blogSubjectGroup = new NDO\Blog\BlogSubjectGroup();
            foreach ($subjects as $subject) {
                $blogSubjectGroup->append(new NDO\Blog\BlogSubject($subject));
            }
            $ndo->setBlogSubjects($blogSubjectGroup);
        }

        if ($bloggerProfileGroup = $this->getValueFromRawData($providerRawData, 'person_uri_relative', null, true)) {
            $ndo->setBlogProfiles(new NDO\Blog\BloggerProfileGroup(new NDO\Blog\BloggerProfile($bloggerProfileGroup)));
        }

        if ($seriesArray = $this->getValueFromRawData($providerRawData, 'channel_uri_relative', null, true)) {
            if (!is_array($seriesArray)) {
                $seriesArray = array($seriesArray);
            }

            $blogSeriesGroup = new NDO\Blog\BlogSeriesGroup();
            foreach ($seriesArray as $series) {
                $blogSeriesGroup->append(new NDO\Blog\BlogSeries($series));
            }
            $ndo->setBlogSeries($blogSeriesGroup);
        }

        if ($personID = $this->getValueFromRawData($providerRawData, array('user', 'person', 'person_id'), null, true)) {
            $author = new NDO\Person\Author($personID);

            if ($providerRawData->getProvider()) {
                $author->setProvider($providerRawData->getProvider());
                $author->setEnvironmentName('production');
            }

            $ndo->setAuthors(new NDO\AuthorGroup($author));
        }

        if ($extractedImageUri = $this->getValueFromRawData($providerRawData, 'extracted_image', null, true)) {
            $ndo->setFeaturedImage(new NDO\URI($extractedImageUri));
        }

        $this->doCommonNodeTranslate($providerRawData, $ndo);

        return $ndo;
    }
}
