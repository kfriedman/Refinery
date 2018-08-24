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
class FooterItemTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
        return $provider->clientGet('taxonomy/term/footer_nav/' . $ndoFilter->getFilterID(), null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\SiteDatum\FooterItem
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\SiteDatum\FooterItem($this->getValueFromRawData($providerRawData, 'uuid'));

        $ndo->setRead(true);

        $ndo->setName($this->getTextGroupFromRawData($providerRawData, 'name_field'));
        $ndo->setLink($this->getTextGroupFromRawData($providerRawData, 'field_url', 'url'));

        if ($parentUUID = $this->getValueFromRawData($providerRawData, 'parent_uuid', null, true)) {
            $ndo->setParent(new NDO\SiteDatum\FooterItem($parentUUID));
        }

        $ndo->setSort($this->getValueFromRawData($providerRawData, 'weight'));

        return $ndo;
    }
}
