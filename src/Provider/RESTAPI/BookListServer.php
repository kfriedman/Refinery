<?php
namespace NYPL\Refinery\Provider\RESTAPI;

use NYPL\Refinery\HealthCheckResponse;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider\RESTAPI;

/**
 * Class to create a D7RefineryServer Provider
 *
 * @package NYPL\Refinery\NDO
 */
class BookListServer extends RESTAPI
{
    protected $rawDataKey = null;

    /**
     * @var int
     */
    protected $count = 0;

    /**
     * @var null|int
     */
    protected $perPage = null;

    /**
     * @var null|int
     */
    protected $page = null;

    /**
     * @param int $count
     */
    public function setCount($count)
    {
        $this->count = (int) $count;
    }

    /**
     * @param int $perPage
     */
    public function setPerPage($perPage)
    {
        $this->perPage = (int) $perPage;
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        $this->page = (int) $page;
    }

    /**
     * Get the count of objects returned by the Provider.
     *
     * @return int|null
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Get the current page being returned by the Provider.
     *
     * @return int|null
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Get the number of records per page returned by the Provider.
     *
     * @return int|null
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * Get the HTTP status code returned by the Provider.
     *
     * @return int|null
     */
    public function getStatusCode()
    {
        // TODO: Implement getStatusCode() method.
    }

    /**
     * Get the meta data returned by the Provider.
     *
     * @return array
     */
    public function getProviderMetaData()
    {
        // TODO: Implement getProviderMetaData() method.
    }

    /**
     * Checks to make sure the Provider is healthy.
     *
     * @return HealthCheckResponse
     */
    public function checkHealth()
    {
        // TODO: Implement checkHealth() method.
    }

    /**
     * @param string    $rawData
     * @param NDOFilter $ndoFilter
     *
     * @return array
     */
    public function filterRawData($rawData, NDOFilter $ndoFilter)
    {
        if (is_string($rawData)) {
            $rawData = json_decode($rawData, true);
        }

        $this->setCount(count($rawData));

        if ($ndoFilter->getPerPage()) {
            if ($ndoFilter->getPage()) {
                $start = ($ndoFilter->getPage() - 1) * $ndoFilter->getPerPage();

                $this->setPage($ndoFilter->getPage());
            } else {
                $start = 0;

                $this->setPage(1);
            }

            $this->setPerPage($ndoFilter->getPerPage());

            $rawData = array_slice($rawData, $start, $ndoFilter->getPerPage());
        }

        return $rawData;
    }
}
