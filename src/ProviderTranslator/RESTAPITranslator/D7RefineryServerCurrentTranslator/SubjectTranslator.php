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
class SubjectTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
        return $provider->clientGet('taxonomy/term/vocabulary_4/' . $ndoFilter->getFilterID(), null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\Term\Subject
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\Term\Subject($this->getValueFromRawData($providerRawData, 'tid'));

        // Setting the name
        $ndo->setName($this->getValueFromRawData($providerRawData, 'name'));

        // Setting the UUID
        $ndo->setUuid($this->getValueFromRawData($providerRawData, 'uuid'));

        // Setting the sort order
        $ndo->setSortOrder($this->getValueFromRawData($providerRawData, 'weight'));

        // Setting the url alias
        $ndo->setAlias($this->getValueFromRawData($providerRawData, 'uri_relative', null, true));

        if ($parentSubject = $this->getValueFromRawData($providerRawData, 'parent_tid', null, true)) {
            $ndo->setParent(new NDO\Term\Subject($parentSubject));
        }

        return $ndo;
    }

}
