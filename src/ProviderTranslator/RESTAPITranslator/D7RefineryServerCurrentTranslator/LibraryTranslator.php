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
class LibraryTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
        if (is_numeric($ndoFilter->getFilterID())) {
            return $provider->clientGet('other/location/' . $ndoFilter->getFilterID(), null, $ndoFilter, $allowEmptyResults);
        }

        $ndoFilter->addQueryParameter('filter[_enhanced][slug]', $ndoFilter->getFilterID());

        return $provider->clientGet('other/location', null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\Location\Library
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\Location\Library();

        $rawData = $providerRawData->getRawDataArray();

        // Check if the raw data is an array and return the single object
        if (isset($rawData[0])) {
            $rawData = $rawData[0];
        }

        $metaData = $providerRawData->getMetaData();

        $ndo->setNdoID((int) $rawData['tid']);

        $ndo->setFullName($rawData[self::ENHANCED_DATA]['name_space']);
        $ndo->setShortName($rawData['name']);

        $ndo->setSymbol($rawData['symbol']);

        if ($slug = $rawData[self::ENHANCED_DATA]['slug']) {
            $ndo->setNdoID($slug);
        } else {
            $ndo->setNdoID($rawData['tid']);
        }

        $ndo->setAboutURI(new NDO\URI('http://' . $metaData['host'] . '/' . $rawData[self::ENHANCED_DATA]['path_about']));
        $ndo->setBlogURI(new NDO\URI('http://' . $metaData['host'] . '/' . $rawData[self::ENHANCED_DATA]['path_blog']));
        $ndo->setEventsURI(new NDO\URI('http://' . $metaData['host'] . '/' . $rawData[self::ENHANCED_DATA]['path_events']));

        $ndo->setAccessibility($rawData[self::ENHANCED_DATA]['access']);
        $ndo->setAccessibilityNote($rawData['accessibility_note']);

        $ndo->setPhone($rawData[self::ENHANCED_DATA]['phone_formatted']);
        $ndo->setFax($rawData[self::ENHANCED_DATA]['fax_formatted']);
        $ndo->setTty($rawData[self::ENHANCED_DATA]['tty_formatted']);
        $ndo->setEmail($rawData['email']);

        $ndo->setCrossStreet($rawData['xstreet']);

        $address = new NDO\Content\Address();
        $address->setAddress1($rawData['address']);
        $address->setCity($rawData['city']);
        $address->setRegion($rawData['region']);
        $address->setPostalCode($rawData[self::ENHANCED_DATA]['zipcode_short']);
        $address->setLatitude($rawData['latitude']);
        $address->setLongitude($rawData['longitude']);

        $ndo->setAddress($address);

        if (isset($rawData['division_referral']['bibliocommons'])) {
            $ndo->setCatalogURI(new NDO\URI($rawData['division_referral']['bibliocommons']));
        }
        if (isset($rawData['division_referral']['contact_url'])) {
            $ndo->setContactURI(new NDO\URI($rawData['division_referral']['contact_url']));
        }
        if (isset($rawData['division_referral']['concierge_url'])) {
            $ndo->setConciergeURI(new NDO\URI($rawData['division_referral']['concierge_url']));
        }

        if (isset($rawData[self::ENHANCED_DATA]['images'])) {
            foreach ($rawData[self::ENHANCED_DATA]['images'] as $imageArray) {
                switch ($imageArray['name']) {
                    case 'exterior':
                        $ndo->setExteriorImage(new NDO\Content\Image($imageArray['fid'], new NDO\URI($imageArray['uri'])));
                        break;
                    case 'interior':
                        $ndo->setInteriorImage(new NDO\Content\Image($imageArray['fid'], new NDO\URI($imageArray['uri'])));
                        break;
                }
            }
        }

        $ndo->setSlug($rawData[self::ENHANCED_DATA]['slug']);

        $ndo->setLocationType($rawData[self::ENHANCED_DATA]['location_type']);

        $ndo->setSlug($rawData[self::ENHANCED_DATA]['slug']);

        $relatedLinks = new NDO\URIGroup();

        if ($rawData['socialmedia']) {
            foreach ($rawData['socialmedia'] as $description => $fullURI) {
                if ($fullURI) {
                    $relatedLinks->append(new NDO\URI($fullURI, $description));
                }
            }
        }

        $ndo->setRelatedLinks($relatedLinks);

        $synonyms = trim($rawData['synonyms']);

        if ($synonyms) {
            $ndo->setSynonyms(explode('|', $synonyms));
        }

        return $ndo;
    }
}
