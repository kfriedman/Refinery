<?php
namespace NYPL\Refinery\Server\Endpoint\api\nypl\ndo\v0_1;

use NYPL\Refinery\DIManager;
use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\ProviderInitializer;
use NYPL\Refinery\Server\Endpoint;

/**
 * API Endpoint
 *
 * @package NYPL\Refinery\Server\Endpoint
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class IndexEndpoint extends BaseEndpoint implements
  Endpoint\GetInterface
{
    /**
     * @param NDOFilter $filter
     * @param string    $baseNDONameWithNamespace
     *
     * @return Provider
     * @throws RefineryException
     */
    public function getProvider(NDOFilter $filter, $baseNDONameWithNamespace = '')
    {
        $urlArray = $filter->getUrlArray();

        /**
         * @var Provider $provider
         */
        switch ($urlArray[0]) {
            case 'staff-picks':
                $provider = DIManager::get('StaffPicksServer');
                ProviderInitializer::initializeProvider($provider);
                break;

            case 'site-data':
                $provider = DIManager::get('D7RefineryServerNew');
                ProviderInitializer::initializeProvider($provider);
                break;

            case 'admin':
            case 'content':
            case 'book-lists':
            case 'hybrid-pages':
            case 'searches':
                $provider = $this->getDefaultProvider($baseNDONameWithNamespace);
                break;

            case 'solr-event':
            case 'solr-events':
                $provider = DIManager::get('SolrEvent');
                ProviderInitializer::initializeProvider($provider);
                break;

            default:
                $provider = DIManager::get('D7RefineryServerCurrent');
                ProviderInitializer::initializeProvider($provider);
                break;
        }

        return $provider;
    }

    /**
     * @param string $baseNDONameWithNamespace
     *
     * @return Provider
     * @throws RefineryException
     */
    public function getDefaultProvider($baseNDONameWithNamespace = '')
    {
        /**
         * @var NDO $ndo
         */
        $ndo = new $baseNDONameWithNamespace;

        if ($ndo->getSupportedReadProviders()) {
            /**
             * @var Provider $provider
             */
            $provider = current($ndo->getSupportedReadProviders());

            ProviderInitializer::initializeProvider($provider);

            return $provider;
        } else {
            throw new RefineryException('NDO (' . $baseNDONameWithNamespace . ') does not have a provider');
        }
    }

    /**
     * @param NDOFilter $filter
     *
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    public function get(NDOFilter $filter)
    {
        $this->setBaseURL('api/nypl/ndo/v0.1');

        parent::get($filter);
    }
}
