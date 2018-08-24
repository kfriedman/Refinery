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
class RegularPageTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
     * @return NDO\Content\Node\RegularPage
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\Content\Node\RegularPage();

        $ndo->setNdoID($this->getValueFromRawData($providerRawData, 'nid'));

        $ndo->setTitle($this->getTextGroupFromRawData($providerRawData, 'title'));

        $ndo->setBody($this->getTextGroupFromRawData($providerRawData, 'body', 'safe_value', 'safe_summary'));

        if ($uri = $this->getValueFromRawData($providerRawData, 'uri_absolute', null, true)) {
            $ndo->setURI(new NDO\URI($uri));
        }

        if ($personID = $this->getValueFromRawData($providerRawData, array('user', 'person', 'person_id'), null, true)) {
            $author = new NDO\Person($personID);

            if ($providerRawData->getProvider()) {
                $author->setProvider($providerRawData->getProvider());
                $author->setEnvironmentName('production');
            }

            $ndo->setAuthors(new NDO\PersonGroup($author));
        }

        return $ndo;
    }
}
