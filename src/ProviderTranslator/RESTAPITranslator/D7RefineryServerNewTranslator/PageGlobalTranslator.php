<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerNewTranslator;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderHandler\NDOReader;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerTranslator;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class PageGlobalTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
        return null;
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\Content\Page\LandingPage
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\PageGlobal($providerRawData->getNdoFilter()->getFilterID());

        $headerItemFilter = new NDOFilter();
        $headerItemFilter->addQueryParameter('filter', array('relationships' => array('parent' => 'null')));
        if ($headerItemGroup = NDOReader::readNDO($providerRawData->getProvider(), new NDO\SiteDatum\HeaderItemGroup(), $headerItemFilter, null, true)) {
            $ndo->setTopLevelHeaderItems($headerItemGroup);
        }

        $footerItemFilter = new NDOFilter();
        $footerItemFilter->addQueryParameter('filter', array('relationships' => array('parent' => 'null')));
        if ($footerItemGroup = NDOReader::readNDO($providerRawData->getProvider(), new NDO\SiteDatum\FooterItemGroup(), $footerItemFilter, null, true)) {
            $ndo->setTopLevelFooterItems($footerItemGroup);
        }

        return $ndo;
    }
}
