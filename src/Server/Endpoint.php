<?php
namespace NYPL\Refinery\Server;

use NYPL\Refinery\Config\Config;
use NYPL\Refinery\Server\Endpoint\Response;

/**
 * Abstract class for all Refinery server endpoints.
 *
 * @package NYPL\Refinery\Server
 */
abstract class Endpoint
{
    /**
     * Whether debugging is currently enabled on the Refinery server or not.
     *
     * @var bool
     */
    private $debug = false;

    /**
     * Raw data that was used to build this endpoint.
     *
     * @var array
     */
    private $rawData = array();

    /**
     * The Response object for this endpoint.
     *
     * @var Response
     */
    private $response;

    /**
     * The base URL used for building URLs to access the server.
     *
     * @var string
     */
    private $baseURL = '';

    /**
     * Constructor method to initialize the response property with a Response.
     */
    public function __construct()
    {
        $this->response = new Response();
    }

    /**
     * Check if debug is enabled for this Endpoint.
     *
     * @return boolean
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * Setter for the debug setting.
     *
     * @param boolean $debug
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    /**
     * Getter for the raw data array.
     *
     * @return array
     */
    public function getRawData()
    {
        return $this->rawData;
    }

    /**
     * Setter for the raw data array.
     *
     * @param array $rawData
     */
    public function setRawData(array $rawData)
    {
        $this->rawData = $rawData;
    }

    /**
     * Getter for the Response object.
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Setter for the Response object.
     *
     * @param Response $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * Getter for the base URL property.
     *
     * @return string
     */
    public function getBaseURL()
    {
        return $this->baseURL;
    }

    /**
     * Setter for the base URL property.
     *
     * @param string $baseURL
     */
    public function setBaseURL($baseURL)
    {
        $this->baseURL = $baseURL;
    }

    /**
     * Construct a URL with protocol and host given a base URL.
     *
     * @return null|string
     */
    public function getFullURL()
    {
        if (Config::getItem('Server.URL')) {
            return Config::getItem('Server.URL') . '/' . $this->getBaseURL();
        }

        return null;
    }
}