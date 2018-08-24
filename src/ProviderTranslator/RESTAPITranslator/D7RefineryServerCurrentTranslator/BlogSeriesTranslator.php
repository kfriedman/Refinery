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
class BlogSeriesTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
        return $provider->clientGet('node/channel/' . $this->translateFromIdToUri($ndoFilter->getFilterID()), null, null, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return \NYPL\Refinery\NDO\Blog\BlogSeries
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\Blog\BlogSeries();

        $ndo->setRead(true);

        $ndo->setNdoID($this->getValueFromRawData($providerRawData, 'uri_relative', null, true));

        $ndo->setTitle($this->getTextGroupFromRawData($providerRawData, 'title'));

        $ndo->setBody($this->getTextGroupFromRawData($providerRawData, 'body', 'safe_value', 'safe_summary'));

        $ndo->setType($this->getValueFromRawData($providerRawData, 'field_channel_content_type'));

        if ($rssUri = $this->getValueFromRawData($providerRawData, 'field_channel_feedburner_url')) {
            $ndo->setRssUri(new NDO\URI($rssUri));
        }

        if ($channelImage = $this->getValueFromRawData($providerRawData, 'field_channel_image', 'fid')) {
            $ndo->setImage(new NDO\Content\Image($channelImage));
        }

        if ($this->getValueFromRawData($providerRawData, 'field_audience_taxonomy', 'tid')) {
            $ndo->setAudience($this->setNDOGroupFromField($providerRawData, 'field_audience_taxonomy', new NDO\AudienceGroup(), new NDO\Term\Audience(), 'tid'));
        }

        $this->setBlogPosts($providerRawData, $ndo, 'blog-series');

//        // Setting the Subjects
//        if (!empty($rawData['field_subject_taxonomy']['und'])) {
//            $subjects = new NDO\SubjectGroup();
//            foreach($rawData['field_subject_taxonomy']['und'] as $subject) {
//                $subjects->append(
//                    new NDO\Term\Subject($subject['tid'])
//                );
//            }
//            $ndo->setSubjects($subjects);
//        }
//
//        // Setting the parent
//        if (isset($rawData['field_channel_parent']['und'][0]['nid'])) {
//            $ndo->setParent(
//                new NDO\Blog\BlogSeries($rawData['field_channel_parent']['und'][0]['nid'])
//            );
//        }

        return $ndo;
    }
}
