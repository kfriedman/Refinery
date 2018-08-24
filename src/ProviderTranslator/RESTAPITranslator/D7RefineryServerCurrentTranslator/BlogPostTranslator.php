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
class BlogPostTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
     * @return NDO\Content\Page\BlogPost
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\Content\Page\BlogPost();

        $rawData = $providerRawData->getRawDataArray();

        $ndo->setNdoID((int) $rawData['nid']);
        $ndo->setTitle($rawData['title']);
        $ndo->setBodyFull($rawData['body']['und'][0]['safe_value']);
        $ndo->setBodyShort($rawData[self::ENHANCED_DATA]['summary_calculated']);

        $ndo->setURI(new NDO\URI($rawData[self::ENHANCED_DATA]['uri_absolute']));

        $ndo->setRelativeURI($rawData[self::ENHANCED_DATA]['uri_relative']);

        /*
        $ndo->setRelatedDCItems(NDOReader::readNDO(new RESTAPI\DC(), new NDO\DCItemGroup(), new NDOFilter()));
        */

        $ndo->setEmbeddedContent(new NDO\ContentGroup());

        $ndo->setDateCreated(new NDO\LocalDateTime('@' . $rawData['created'], new \DateTimeZone($this->getTimeZone())));

        if (isset($rawData['field_image'])) {
            $this->addImages($ndo->getEmbeddedContent(), $rawData['field_image']['und']);
        }

        $author = new NDO\Person\Author();

        if ($rawData[self::ENHANCED_DATA]['user']['field_last_name']) {
            $author->setLastName($rawData[self::ENHANCED_DATA]['user']['field_last_name']['und'][0]['value']);
            $author->setFirstName($rawData[self::ENHANCED_DATA]['user']['field_first_name']['und'][0]['value']);

            $author->setDisplayName($author->getFirstName() . ' ' . $author->getLastName());
        } else {
            if (isset($rawData[self::ENHANCED_DATA]['user']['profile_author_name'])) {
                $author->setDisplayName($rawData[self::ENHANCED_DATA]['user']['profile_author_name']);
            }
        }

        if ($rawData[self::ENHANCED_DATA]['user']['field_title_position']) {
            $author->setTitle($rawData[self::ENHANCED_DATA]['user']['field_title_position']['und'][0]['value']);
        } else {
            if (isset($rawData[self::ENHANCED_DATA]['user']['profile_position'])) {
                $author->setTitle($rawData[self::ENHANCED_DATA]['user']['profile_position']);
            }
        }

        if ($rawData[self::ENHANCED_DATA]['user']['field_division_unit']) {
            $author->setLocation($rawData[self::ENHANCED_DATA]['user']['field_division_unit']['und'][0]['value']);
        } else {
            if (isset($rawData[self::ENHANCED_DATA]['user']['profile_location'])) {
                $author->setLocation($rawData[self::ENHANCED_DATA]['user']['profile_location']);
            }
        }

        $authorGroup = new NDO\AuthorGroup();
        $authorGroup->append($author);

        $ndo->setAuthors($authorGroup);

        if (filter_var($rawData[self::ENHANCED_DATA]['extracted_image'], FILTER_VALIDATE_URL)) {
            $ndo->setHighlightImage(new NDO\Content\Image(null, new NDO\URI($rawData[self::ENHANCED_DATA]['extracted_image'])));
        }

        return $ndo;
    }

    /**
     * Common method used to add an image NDO to a ContentGroup NDO.
     *
     * @param NDO\ContentGroup  $contentGroup
     * @param array             $imageArray
     */
    protected function addImages(NDO\ContentGroup $contentGroup, array $imageArray)
    {
        foreach ($imageArray as $image) {
            $contentGroup->append(new NDO\Content\Image($image['uuid'], new NDO\URI($image['uri_external'])));
        }
    }

}