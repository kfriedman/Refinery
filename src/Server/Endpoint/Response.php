<?php
namespace NYPL\Refinery\Server\Endpoint;

/**
 * Class used to build Endpoint responses. Primarily outputted by the Refinery
 * Server.
 *
 * @package NYPL\Refinery\Server
 */
class Response
{
    /**
     * The name of the key that should be used for the primary data payload.
     */
    const DEFAULT_DATA_KEY = 'data';

    /**
     * The name of the key that should be used for the primary data payload.
     */
    const DEFAULT_INCLUDED_KEY = 'included';

    /**
     * The primary data payload (if any).
     *
     * @var array
     */
    private $data = array();

    /**
     * The included data payload (if any).
     *
     * @var array
     */
    private $included = array();

    /**
     * The HTML response (if any).
     *
     * @var string
     */
    private $html = '';

    /**
     * The count of records in the data payload.
     *
     * @var int
     */
    private $count = 0;

    /**
     * The current page from the entire data set.
     *
     * @var int
     */
    private $page = 0;

    /**
     * The start of the data payload in the entire data set.
     *
     * @var int
     */
    private $start = 0;

    /**
     * The number of resources to display per page.
     *
     * @var int|null
     */
    private $perPage;

    /**
     * @var int
     */
    private $totalPages = 0;

    /**
     * The HTTP status code of the response.
     *
     * @var int
     */
    private $statusCode;

    /**
     * An array of debug information in the response.
     *
     * @var array
     */
    private $debugArray = array();

    /**
     * The name of the key that should be used for the primary data payload.
     *
     * @var string
     */
    private $dataKey = self::DEFAULT_DATA_KEY;

    /**
     * @var array
     */
    private $metaNotices = array();

    /**
     * @var array
     */
    private $otherTopLevel = array();

    /**
     * Getter for the primary data payload.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Setter for the primary data payload.
     *
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = (array) $data;
    }

    /**
     * Getter for the debug information.
     *
     * @return array
     */
    public function getDebugArray()
    {
        return $this->debugArray;
    }

    /**
     * Setter for the debug information.
     *
     * @param array $debugArray
     */
    public function setDebugArray(array $debugArray)
    {
        $this->debugArray[] = $debugArray;
    }

    /**
     * Getter for the count of records in the data payload.
     *
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Setter for the count of records in the data payload.
     *
     * @param int $count
     */
    public function setCount($count)
    {
        $this->count = (int) $count;

        $this->calculateTotalPages();
    }

    /**
     * Getter for the current page from the entire data set.
     *
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Setter for the current page from the entire data set.
     *
     * @param int $page
     */
    public function setPage($page)
    {
        $this->page = (int) $page;
    }

    /**
     * Getter for the start of the data payload in the entire data set.
     *
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Setter for the start of the data payload in the entire data set.
     *
     * @param int $start
     */
    public function setStart($start)
    {
        $this->start = (int) $start;
    }

    /**
     * Getter for the number of resources to display per page. If NULL, return
     * all records.
     *
     * @return int|null
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * Setter for the number of resources to display per page. If NULL, return
     * all records.
     *
     * @param int|null $perPage
     */
    public function setPerPage($perPage)
    {
        if ($perPage === null) {
            $this->perPage = $perPage;

        } else {
            $this->perPage = (int) $perPage;
        }

        $this->calculateTotalPages();
    }

    /**
     * Getter for the HTTP status code of the response.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Setter for the HTTP status code of the response.
     *
     * @param int $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = (int) $statusCode;
    }

    /**
     * Getter for the name of the key that should be used for the primary data
     * payload.
     *
     * @return string
     */
    public function getDataKey()
    {
        return $this->dataKey;
    }

    /**
     * Setter for the name of the key that should be used for the primary data
     * payload.
     *
     * @param string $dataKey
     */
    public function setDataKey($dataKey)
    {
        $this->dataKey = $dataKey;
    }

    /**
     * Getter for the HTML response.
     *
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Setter for the HTML response.
     *
     * @param string $html
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }

    /**
     * @return array
     */
    public function getIncluded()
    {
        return $this->included;
    }

    /**
     * @param array $included
     */
    public function setIncluded(array $included)
    {
        $this->included = $included;
    }

    /**
     * @return array
     */
    public function getMetaNotices()
    {
        return $this->metaNotices;
    }

    /**
     * @param array $metaNotices
     */
    public function setMetaNotices($metaNotices)
    {
        $this->metaNotices = $metaNotices;
    }

    /**
     * @return array
     */
    public function getOtherTopLevel()
    {
        return $this->otherTopLevel;
    }

    /**
     * @param array $otherTopLevel
     */
    public function setOtherTopLevel($otherTopLevel)
    {
        $this->otherTopLevel = $otherTopLevel;
    }

    /**
     * @param array $value
     */
    public function addOtherTopLevel(array $value)
    {
        $this->otherTopLevel += $value;
    }

    /**
     * @return int
     */
    public function calculateTotalPages()
    {
        if ($this->getCount() && $this->getPerPage()) {
            $this->totalPages = ceil($this->getCount() / $this->getPerPage());
        }
    }

    /**
     * @return int
     */
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * @param string|array $notice
     */
    public function addMetaNotice($notice)
    {
        if (is_array($notice)) {
            $this->metaNotices += $notice;
        } else {
            $this->metaNotices[] = $notice;
        }
    }
}