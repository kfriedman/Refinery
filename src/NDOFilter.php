<?php
namespace NYPL\Refinery;

use NYPL\Refinery\Exception\RefineryException;

/**
 * Class to create a NDOFilter object
 *
 * The NDOFilter is use to filter results and track parameters sent/received
 * from Providers.
 *
 * @package NYPL\Refinery
 */
class NDOFilter
{
    /**
     * The primary ID to be used for this filter. This is equivalent to the
     * "primary key".
     *
     * @var string
     */
    protected $filterID = '';

    /**
     * The record offset of the results from the Provider.
     *
     * @var int
     */
    protected $start = 1;

    /**
     * The page offset of the results from the Provider.
     *
     * @var int
     */
    protected $page = 0;

    /**
     * The number of records to display per page from the Provider.
     *
     * @var null|int
     */
    protected $perPage = null;

    /**
     * An array that stores the query parameters for the request sent to the
     * Provider.
     *
     * @var array
     */
    protected $queryParameterArray = array();

    /**
     * An array that stores the filter used to filter records after they are
     * received from the Provider.
     *
     * @var array
     */
    protected $filterArray = array();

    /**
     * An array that stores the "include" requests for the current request.
     *
     * @var array
     */
    protected $includeArray = array();

    /**
     * @var array
     */
    protected $urlArray = array();

    /**
     * @var array
     */
    protected $fieldsArray = array();

    /**
     * The constructor for the NDOFilter. Primarily used to see the NDOFilter's
     * primary ID.
     *
     * @param string $filterID
     */
    public function __construct($filterID = '')
    {
        if ($filterID) {
            $this->setFilterID($filterID);
        }
    }

    /**
     * Getter for the primary ID.
     *
     * @return array|string
     */
    public function getFilterID()
    {
        return $this->filterID;
    }

    /**
     * Setter for the primary ID.
     *
     * @param string $filterID
     */
    public function setFilterID($filterID = '')
    {
        $this->filterID = $filterID;
    }

    /**
     * Getter for the record offset of the results from the Provider.
     *
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Setter for the the record offset of the results from the Provider.
     * Should always be an integer and not less than 1.
     *
     * @param int $start
     *
     * @throws RefineryException
     */
    public function setStart($start)
    {
        if (!is_int($start)) {
            throw new RefineryException('Start provided (' . $start . ') is not an integer', 400);
        }

        if ($start < 1) {
            throw new RefineryException('Start provided (' . $start . ') should not be less than zero', 400);
        }

        if ($start) {
            $this->start = $start;
        }
    }

    /**
     * Getter for the page offset of the results from the Provider.
     *
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Setter for the page offset of the results from the Provider.
     *
     * @param int $page
     */
    public function setPage($page)
    {
        $this->page = (int) $page;
    }

    /**
     * Getter for the the number of records to display per page from the
     * Provider.
     *
     * @return int|null
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * Setter for the the number of records to display per page from the
     * Provider.
     *
     * @param int|null $perPage
     */
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;
    }

    /**
     * Add a query parameter to the $this->queryParameterArray array.
     *
     * @param string $queryParameterName  The name of the parameter.
     * @param mixed  $queryParameterValue The value of the parameter.
     * @param string $queryValueOperator  The comparison operator that should be used for query parameters.
     */
    public function addQueryParameter($queryParameterName = '', $queryParameterValue = null, $queryValueOperator = '')
    {
        if (!$queryValueOperator) {
            $queryValueOperator = '=';
        }

        $queryParameter = new QueryParameter();

        $queryParameter->setOperator($queryValueOperator);
        $queryParameter->setValue($queryParameterValue);

        $this->queryParameterArray[$queryParameterName] = $queryParameter;
    }

    /**
     * Get the query parameter for a particular name.
     *
     * @param string $parameterName
     *
     * @return QueryParameter
     */
    public function getQueryParameter($parameterName = '')
    {
        if (isset($this->queryParameterArray[$parameterName])) {
            return $this->queryParameterArray[$parameterName];
        }

        return new QueryParameter();
    }

    /**
     * Getter for the entire query parameter array.
     *
     * @return array
     */
    public function getQueryParameterArray()
    {
        return $this->queryParameterArray;
    }

    /**
     * Setter for the entire query parameter array.
     *
     * @param array $queryParameterArray
     */
    public function setQueryParameterArray(array $queryParameterArray)
    {
        $this->queryParameterArray = $queryParameterArray;
    }

    /**
     * Add a filter to $this->filterArray.
     *
     * @param string $filterName
     * @param null   $filterValue
     */
    public function addFilter($filterName = '', $filterValue = null)
    {
        $this->filterArray[$filterName] = $filterValue;
    }

    /**
     * Get the filter for a particular name.
     *
     * @param string $filterName
     *
     * @return mixed
     */
    public function getFilterName($filterName = '')
    {
        if (isset($this->filterArray[$filterName])) {
            return $this->filterArray[$filterName];
        }

        return null;
    }

    /**
     * Getter for the entire filter array.
     *
     * @return array
     */
    public function getFilterArray()
    {
        return $this->filterArray;
    }

    /**
     * Setter for the entire filter array.
     *
     * @param array $filterArray
     */
    public function setFilterArray($filterArray)
    {
        $this->filterArray = $filterArray;
    }

    /**
     * Getter for the entire include array.
     *
     * @return array
     */
    public function getIncludeArray()
    {
        return $this->includeArray;
    }

    /**
     * Setter for the entire include array.
     *
     * @param array $includeArray
     */
    public function setIncludeArray($includeArray)
    {
        $this->includeArray = $includeArray;
    }

    /**
     * Add an include to the include array.
     *
     * @param string $includeName
     */
    public function addInclude($includeName = '')
    {
        $this->includeArray[] = $includeName;
    }

    /**
     * Check to see if the requested include has been added to the include
     * array.
     *
     * @param string $includeName
     *
     * @return bool
     */
    public function checkInclude($includeName = '')
    {
        if (in_array($includeName, $this->getIncludeArray())) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return array
     */
    public function getUrlArray()
    {
        return $this->urlArray;
    }

    /**
     * @param array $urlArray
     */
    public function setUrlArray(array $urlArray)
    {
        $this->urlArray = $urlArray;
    }

    /**
     * @return array
     */
    public function getFieldsArray()
    {
        return $this->fieldsArray;
    }

    /**
     * @param array $fieldsArray
     */
    public function setFieldsArray(array $fieldsArray)
    {
        $this->fieldsArray = $fieldsArray;
    }
}
