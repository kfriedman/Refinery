<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerNewTranslator;

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
class UriAliasTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
        return $provider->clientGet('admin/url_aliases/' . $ndoFilter->getFilterID(), null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\Admin\UriAlias
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\Admin\UriAlias($this->getValueFromRawData($providerRawData, 'pid'));

        $ndo->setRead(true);

        // Setting the source
        if ($source = $this->getValueFromRawData($providerRawData, 'source')) {
            $ndo->setSource($source);
        }

        // Setting the alias
        if ($alias = $this->getValueFromRawData($providerRawData, 'alias')) {
            $ndo->setAlias($alias);
        }

        // Setting the language
        if ($language = $this->getValueFromRawData($providerRawData, 'language')) {
            $ndo->setLanguage($language);
        }

        if ($node = $this->getValueFromRawData($providerRawData, 'node', null, true)) {
            switch ($node['type']) {
                case 'landing_page':
                    $ndo->setRelatedPage(new NDO\Content\Page\LandingPage($node['uuid']));

                    $ndo->setPageGlobal(new NDO\PageGlobal('default'));
                    break;
            }
        }

        return $ndo;
    }
}
