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
class NodeGroupTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
        return $provider->clientGet('node', null, $ndoFilter, $allowEmptyResults);
    }

    /**
     * @param NDOFilter $ndoFilter
     *
     * @return NDOFilter
     */
    protected function translateFilter(NDOFilter $ndoFilter)
    {
        if ($aliasFilter = $ndoFilter->getQueryParameter('filter')->getValue('alias')) {
            $ndoFilter->addQueryParameter('filter[_enhanced][uri_relative]', $aliasFilter);

            $ndoFilter->getQueryParameter('filter')->updateValue('alias', null);
        }
        if ($relationshipsFilter = $ndoFilter->getQueryParameter('filter')->getValue('relationships')) {
            foreach ($relationshipsFilter as $key => $value) {
                switch ($key) {
                    case 'blog-series':
                        $ndoFilter->addQueryParameter('filter[_enhanced][channel_uri_relative]', $this->translateFromIdToUri($value, $key));
                        break;
                    case 'blog-subjects':
                        $ndoFilter->addQueryParameter('filter[_enhanced][subjects_uri_relative]', $this->translateFromIdToUri($value, $key));
                        break;
                    case 'blog-profiles':
                        $ndoFilter->addQueryParameter('filter[_enhanced][person_uri_relative]', $this->translateFromIdToUri($value, $key));
                        break;
                }
            }

            $ndoFilter->getQueryParameter('filter')->updateValue('relationships', null);
        }
        if ($ndoFilter->getFilterID()) {
            $ndoFilter->addQueryParameter('filter[uuid]', implode('|', $ndoFilter->getFilterID()));
        }

        return $ndoFilter;
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\Content\Node
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawDataArray = $providerRawData->getRawDataArray();

        $ndo = new NDO\NodeGroup();

        foreach ($rawDataArray as $rawData) {
            $providerTranslator = new NodeTranslator();
            $ndo->append($providerTranslator->translate(new ProviderRawData($rawData)));
        }

        return $ndo;
    }
}
