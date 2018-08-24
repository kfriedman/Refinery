<?php

namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator;

use NYPL\Refinery\Config\Config;
use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\Helpers\UrlHelper;
use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;

/**
 * @package NYPL\Refinery
 */
abstract class MetricsEventTranslator extends SolrEventTranslator
{
    /**
     * @var string
     */
    const FILTER_STATUS = 'status';

    /**
     * @var int
     */
    const DEFAULT_STATUS = 1;

    /**
     * @var string
     */
    const FILTER_AUTH = 'auth';

    /**
     * @var string
     */
    const FILTER_OUTPUT = 'output';

    /**
     * @var string
     */
    const OUTPUT_CSV = 'csv';

    /**
     * @var int
     */
    const CSV_MAX_ROWS = 10000;

    /**
     * @var string
     */
    protected $output;

    /**
     * Builds the Solr URL based on the filter
     *
     * @param  NDOFilter $ndoFilter
     * @return string
     */
    public function getUrlFromFilter(NDOFilter $ndoFilter, $start=0)
    {
        // Checking the auth
        $this->checkAuthorization($ndoFilter);

        $parameters = $ndoFilter->getQueryParameter('filter')->getValue();

        // Parameters 'year' required
        if (empty($parameters['year'])) {
            throw new RefineryException('"filter[year]" parameter must be specified');
        }

        // Parameters 'month' required
        if (empty($parameters['month'])) {
            throw new RefineryException('"filter[month]" parameter must be specified');
        }

        // Getting the output parameter
        if (isset($parameters[self::FILTER_OUTPUT]) && $parameters[self::FILTER_OUTPUT] == self::OUTPUT_CSV) {
            $this->output = self::OUTPUT_CSV;
        }

        // Building the date query
        $year  = $parameters['year'];
        $month = $parameters['month'];

        // Getting the last day of the month
        $lastDay = date('t', strtotime($year.'-'.$month.'-01'));

        // Building the filter query
        $parameters = array();
        $parameters['fq'][] = "date_time_start: [{$year}-{$month}-01T00:00:00Z TO {$year}-{$month}-{$lastDay}T23:59:59Z]";

        // Building the query
        $status = $this->getFilterStatus($ndoFilter);
        $parameters['fq'][] = is_null($status) ? 'status: 1' : 'status: '. $status;
        $q = '*:*';

        $parameters['fq'] = implode(' AND ', $parameters['fq']);

        // Default params
        $defaultParams = [
            'q'       => $q,
            'wt'      => 'json',
            'defType' => 'edismax',
            'start'   => (int) $start
        ];

        // Setting the number of rows
        if ($this->output == self::OUTPUT_CSV) {
            $defaultParams['rows'] = self::CSV_MAX_ROWS;
        }

        // Merging all the parameters
        $parameters = array_merge($parameters, $defaultParams);

        // Building the parameters
        $url_params = http_build_query($parameters);

        // Building the URL
        return 'select?'.$url_params;
    }

    /**
     * Checks the authorization token
     *
     * @param  NDOFilter $ndoFilter
     * @throws RefineryException
     */
    protected function checkAuthorization(NDOFilter $ndoFilter)
    {
        $parameters = $ndoFilter->getQueryParameter('filter')->getValue();

        // Checking the 'auth' parameter
        if (!isset($parameters[self::FILTER_AUTH])) {
            throw new RefineryException('Token authentication required');
        }

        $auth = $parameters[self::FILTER_AUTH];
        if (Config::getItem('DefaultProviders.EventMetrics.Auth') !== $auth) {
            throw new RefineryException('Invalid authentication token');
        }
    }

    /**
     * Gets the value of the status
     *
     * @param  NDOFilter $ndoFilter
     * @throws RefineryException
     */
    protected function getFilterStatus(NDOFilter $ndoFilter)
    {
        $parameters = $ndoFilter->getQueryParameter('filter')->getValue();

        if (isset($parameters[self::FILTER_STATUS])) {
            $status = $parameters[self::FILTER_STATUS];

            // Verfiying if the parameter has a valid format
            if ('0' != $status && '1' != $status) {
                throw new RefineryException("Invalid filter[status] parameter value: must be 0 or 1, '$status' given");
            }

            return $status;
        }

        return self::DEFAULT_STATUS;
    }

    /**
     * @param NDO\SolrEvent\EventMetrics $ndo
     *
     */
    protected function createCsvFile(NDO\SolrEvent\MetricsSearch $ndo)
    {
        // Adding the headers
        $filename = $this->generateCsvFilename($ndo);
        header('Content-type: text/csv');
        header("Content-Disposition: attachment; filename=$filename");
        header('Pragma: no-cache');
        header('Expires: 0');

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        // Setting the metrics_id field
        $csvFields = array(
            'metrics_id',
            'delta',
            'nid',
            'language',
            'title',
            'status',
            'description',
            'created',
            'changed',
            'date_time_start',
            'date_time_end',
            'date_status',
            'date_details',
            'related_division',
            'library_name',
            'location_phone',
            'external_location',
            'event_type',
            'event_topic',
            'series',
            'target_audience',
            'audience',
            'doe_activities',
            'school',
            'school_type',
            'grade',
            'capacity',
            'total_time',
            'grant_funder',
            'materials',
            'prep_time',
            'comments',
            'ignore_conflicts',
            'sponsor',
            'total_adults',
            'total_children',
            'total_young_adults',
            'resources',
            'class_size',
            'teacher_name',
            'teacher_email',
            'performer',
            'performer_type',
            'created_by',
            'modified_by'
        );

        // Output the column headings
        fputcsv($output, $csvFields);

        // Mapping each event row
        $eventMetrics = $ndo->getEventMetrics();

        if (!empty($eventMetrics)) {

            foreach ($eventMetrics->items as $eventMetric) {

                $row = array();
                foreach ($csvFields as $field) {

                    // Getting the value
                    $method = 'get'.self::fieldNameToCamelcase($field);
                    $value = $eventMetric->$method();

                    // If there is no values
                    if (is_null($value)) {
                        $value = '';
                    }

                    // If the value is an array
                    elseif (is_array($value)) {
                        $value = empty($value) ? '' : (implode(', ', $value));
                    }

                    // Case of datetime types
                    elseif (is_object($value) && 'NYPL\Refinery\NDO\LocalDateTime' == get_class($value)) {
                        $value = $value->getDateTime()->format('Y-m-d H:i:s');
                    }

                    $row[] = $value;
                }

                fputcsv($output, $row);
            }

        }

        exit(0);
    }

    /**
     * Converts a field name to camelcase format
     *
     * @param  string $fieldname Fieldname
     * @return string Field name in camelcase format
     */
    protected static function fieldNameToCamelcase($fieldname)
    {
        $pieces = explode('_', $fieldname);
        $pieces = array_map('ucfirst', $pieces);
        return implode('', $pieces);
    }

    /**
     * Checks if the content must be delivered as a CSV file
     *
     * @return boolean
     */
    protected function deliverAsCsvFile()
    {
        return self::OUTPUT_CSV == $this->output;
    }

    /**
     * Generates the CSV file name
     *
     * @param  NDO\SolrEvent\MetricsSearch $ndo
     * @return string CSV file name
     */
    protected function generateCsvFilename(NDO\SolrEvent\MetricsSearch $ndo)
    {
        $month = $ndo->getMonth();

        return 'metrics_'.($month < 10 ? '0' : '').$month.
            $ndo->getYear().
            ($ndo->getStatus() == 0 ? '_draft' : '').
            '.csv';
    }
}
