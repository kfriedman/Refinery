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
abstract class ProfileTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
{
    /**
     * @return NDO\StaffProfile|NDO\Blog\BloggerProfile
     */
    abstract public function getNDO();

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\StaffProfile|NDO\Blog\BloggerProfile
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawData = $providerRawData->getRawDataArray();

        $ndo = $this->getNDO();

        $ndo->setRead(true);

        $ndo->setNdoID($rawData[self::ENHANCED_DATA]['profile_slug']);

        $ndo->setProfileSlug($rawData[self::ENHANCED_DATA]['profile_slug']);

        if (isset($rawData[self::ENHANCED_DATA]['picture_uri_absolute'])) {
            $headshotNDO = new NDO\Content\Image($rawData['field_person_headshot']['und'][0]['fid']);
            $ndo->setHeadshot($headshotNDO);
        }

        if ($location = $this->getValueFromRawData($providerRawData, 'field_person_nypl_location', 'target_id')) {
            $ndo->setLocation(new NDO\Location\Library($location));
        }

        if ($division = $this->getValueFromRawData($providerRawData, 'field_person_division', 'target_id')) {
            $ndo->setDivision(new NDO\Location\Division($division));
        }

        if ($rawData['field_profile_subject_expertise']) {
            $subjectFullGroup = new NDO\SubjectGroup();

            foreach ($rawData['field_profile_subject_expertise']['und'] as $subject) {
                $subjectFullGroup->append(new NDO\Term\Subject($subject['target_id']));
            }

            $ndo->setSubjects($subjectFullGroup);
        }

        if ($rawData['field_person_links']) {
            foreach ($rawData['field_person_links']['und'] as $link) {
                $ndo->addLink(new NDO\URI($link['url'], $link['title']));
            }
        }

        $ndo->setActive($rawData['status']);

        return $ndo;
    }
}
