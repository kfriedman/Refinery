<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator;

use NYPL\Refinery\Config\Config;
use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\Helpers\UrlHelper;
use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\ProviderTranslatorInterface;

/**
 * RESTAPITranslator Interface to support UPDATE operations for NDOs.
 *
 * @package NYPL\Refinery
 */
abstract class SolrEventTranslator implements ProviderTranslatorInterface
{
    /**
     * Timezone to use for update operations.
     */
    const TIMEZONE = 'America/New_York';

    /**
     * @var string Undefined language code
     */
    const UNDEFINED_LANGUAGE_CODE = 'und';

    /**
     * @var string Default language code
     */
    const DEFAULT_LANGUAGE_CODE = 'eng';

    /**
     * Getter for the timezone constant.
     *
     * @return string
     */
    public function getTimeZone()
    {
        return self::TIMEZONE;
    }

    /**
     * Builds the Solr URL based on the filter
     *
     * @param  NDOFilter $ndoFilter
     * @return string
     */
    public function getUrlFromFilter(NDOFilter $ndoFilter)
    {
        // Checking the parameters
        $parameters = $ndoFilter->getQueryParameter('filter')->getValue();
        $reflectedClass = new \ReflectionClass(NDO\SolrEvent\Search::class);
        $ndo = new NDO\SolrEvent\Search();

        foreach ($parameters as $name => $value) {

            // Converting the "facet.field" param name
            if ('facet.field' == $name) {
                $name = 'facetFields';
            }

            // Checking if the setter method is valid
            $setterName = 'set' . $name;
            if (!$reflectedClass->hasMethod($setterName)) {
                throw new RefineryException('Filter parameter (filter[' . $name . ']) specified is not valid');
            }

            // Setting the parameter value to the NDO
            $ndo->$setterName($value);
        }

        // Getting the values for the filter. It returns the default values if the value wasn't set in the URL
        $ndoParams = ['q', 'fq', 'sort', 'start', 'rows'];
        foreach ($ndoParams as $param) {
            $method = 'get'.ucfirst($param);
            $parameters[$param] = $ndo->$method();
        }

        // Setting the facet fields
        $parameters['facet.field'] = $ndo->getFacetFields();

        // Default params
        $defaultParams = [
            'wt'             => 'json',
            'defType'        => 'edismax',
            'qf'             => 'title_idx^6 body_idx^4 series^3 event_type^2 event_topic^2 audience^2',
            'pf'             => 'title_idx^1 body_idx',
            'facet'          => 'true',
            'facet.limit'    => '-1',
            'facet.mincount' => '1'
        ];

        // Merging the whole parameters
        $parameters = array_merge($parameters, $defaultParams);

        // Building the parameters
        $url_params = UrlHelper::buildQueryWithNoIndex($parameters);

        // Building the URL
        return 'select?'.$url_params;
    }
}
