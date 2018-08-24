<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerNewTranslator;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderHandler\NDOReader;
use NYPL\Refinery\ProviderInitializer;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerTranslator;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class BlogIndexTranslator extends D7RefineryServerTranslator implements RESTAPITranslator\ReadInterface
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
     * @return NDO\HybridPage\BlogIndex
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $ndo = new NDO\HybridPage\BlogIndex($providerRawData->getNdoFilter()->getFilterID());

        $ndo->setHeaderCollection(new NDO\SiteDatum\Collection('39b4d794-75f9-485b-a170-01003f05a2c0'));

        $provider = new RESTAPI\D7RefineryServerCurrent();
        ProviderInitializer::initializeProvider($provider);

        /**
         * @var NDO\Content\Node\BlogGroup $blogGroup
         */
        if ($blogGroup = NDOReader::readNDO($provider, new NDO\Content\Node\BlogGroup(), new NDOFilter(), null, true)) {
            $blogGroup->setProvider($provider);

            $ndo->setFirstPageBlogPosts($blogGroup);
        }

        /**
         * @var NDO\Blog\BlogSubjectGroup $blogSubjectGroup
         */
        if ($blogSubjectGroup = NDOReader::readNDO($provider, new NDO\Blog\BlogSubjectGroup(), new NDOFilter(), null, true)) {
            $blogSubjectGroup->setProvider($provider);

            $ndo->setBlogSubjects($blogSubjectGroup);
        }

        return $ndo;
    }
}
