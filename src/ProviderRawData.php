<?php
namespace NYPL\Refinery;

use NYPL\Refinery\Exception\RefineryException;

/**
 * Object used to store raw data returned by a Provider.
 *
 * @package NYPL\Refinery
 */
class ProviderRawData
{
    /**
     * An array of the complete response returned by the Provider. This may
     * included meta data such as record counts, paging, etc.
     *
     * @var array
     */
    protected $rawDataAllArray = array();

    /**
     * A subset of $this->rawDataAllArray containing the primary data payload.
     *
     * @var array
     */
    protected $rawDataArray = array();

    /**
     * A subset of $this->rawDataAllArray containing the meta information
     * returned by the Provider.
     *
     * @var array
     */
    protected $metaData = array();

    /**
     * Whether empty results/data is acceptable for this instance and should
     * not be considered an error.
     *
     * @var bool
     */
    protected $allowEmptyResults = false;

    /**
     * The NDOFilter that was used to generate this raw data.
     *
     * @var NDOFilter
     */
    protected $ndoFilter;

    /**
     * The Provider used to generate this raw data.
     *
     * @var Provider
     */
    protected $provider;

    /**
     * Initialize the object.
     *
     * @param null|string|array $rawData           The raw data that was returned by the Provider.
     * @param string            $rawDataKey        The key that should be used to parse the data payload from raw data (for $this->$rawDataArray).
     * @param array             $metaData          The meta data returned by the Provider.
     * @param bool              $allowEmptyResults Whether empty results/data is acceptable for this instance.
     * @param NDOFilter         $ndoFilter         The NDOFilter that was used to generate this raw data.
     * @param Provider          $provider          Provider used to generate this raw data.
     *
     * @throws RefineryException
     */
    public function __construct($rawData = null, $rawDataKey = '', $metaData = array(), $allowEmptyResults = false, NDOFilter $ndoFilter = null, Provider $provider = null)
    {
        $this->setAllowEmptyResults($allowEmptyResults);

        if ($rawData) {
            $this->parseRawData($rawData, $rawDataKey);
        }

        if ($metaData) {
            $this->setMetaData($metaData);
        }

        if ($ndoFilter) {
            $this->setNdoFilter($ndoFilter);
        }

        if ($provider) {
            $this->setProvider($provider);
        }
    }

    /**
     * Getter the array of all raw data returned by the Provider
     *
     * @return array
     */
    public function getRawDataArray()
    {
        return $this->rawDataArray;
    }

    /**
     * Setter the array of all raw data returned by the Provider
     *
     * @param array $rawDataArray
     */
    public function setRawDataArray(array $rawDataArray)
    {
        $this->rawDataArray = $rawDataArray;
    }

    /**
     * Parse the raw data returned by the Provider into corresponding properties.
     *
     * @param array|string $rawData    The raw data that was returned by the Provider.
     * @param string       $rawDataKey The key that should used to parse the data payload from raw data.
     *
     * @throws RefineryException
     */
    protected function parseRawData($rawData, $rawDataKey = '')
    {
        $rawDataType = gettype($rawData);

        switch ($rawDataType) {
            case 'string':
                $this->parseRawDataJSONString($rawData, $rawDataKey);
                break;
            case 'array':
                $this->parseRawDataArray($rawData, $rawDataKey);
                break;
            case 'object':
                $this->parseObject($rawData);
                break;
            default:
                throw new RefineryException('Raw data provided (' . $rawDataType . ') was not array or string.');
                break;
        }
    }

    /**
     * @param \SimpleXMLElement $rawData
     */
    protected function parseObject(\SimpleXMLElement $rawData)
    {
        $this->setRawDataAllArray(array($rawData));

        $this->setRawDataArray(array($rawData));
    }

    /**
     * Parse the raw data JSON string and set corresponding properties.
     *
     * @param string $rawData    The raw data JSON string that was returned by the Provider.
     * @param string $rawDataKey The key that should used to parse the data payload from raw data.
     *
     * @throws RefineryException
     */
    protected function parseRawDataJSONString($rawData = '', $rawDataKey = '')
    {
        $this->setRawDataAllArray(json_decode($rawData, true));

        $rawDataPayload = $this->parseRawDataJSONStringDataPayload($rawData, $rawDataKey);

        if (!is_array($rawDataPayload)) {
            throw new RefineryException('Raw data provided was not JSON', 0, $rawDataPayload);
        }

        $this->setRawDataArray($rawDataPayload);
    }

    /**
     * Parse the raw data array set corresponding properties.
     *
     * @param array  $rawData    The raw data array that was returned by the Provider.
     * @param string $rawDataKey The key that should used to parse the data payload from raw data.
     *
     * @throws RefineryException
     */
    protected function parseRawDataArray(array $rawData, $rawDataKey = '')
    {
        $this->setRawDataAllArray($rawData);

        if ($rawDataKey) {
            if (!isset($rawData[$rawDataKey])) {
                if (!$this->isAllowEmptyResults()) {
                    throw new RefineryException('Raw data key (' . $rawDataKey . ') was not found', 0, $rawData);
                }
            } else {
                if (is_array($rawData[$rawDataKey])) {
                    $this->setRawDataArray($rawData[$rawDataKey]);
                }
            }
        } else {
            $this->setRawDataArray($rawData);
        }
    }

    /**
     * Parse the data payload component of the raw data JSON string.
     *
     * @param string $rawData    The raw data array that was returned by the Provider.
     * @param string $rawDataKey The key that should used to parse the data payload from raw data.
     *
     * @return mixed
     *
     * @throws RefineryException
     */
    protected function parseRawDataJSONStringDataPayload($rawData = '', $rawDataKey = '')
    {
        $rawData = json_decode($rawData, true);

        if ($rawDataKey) {
            if (isset($rawData[$rawDataKey])) {
                $rawData = $rawData[$rawDataKey];
            } else {
                if ($this->isAllowEmptyResults()) {
                    $rawData = array();
                } else {
                    throw new RefineryException('Raw data key (' . $rawDataKey . ') was not found', 0, $rawData);
                }
            }
        }

        return $rawData;
    }

    /**
     * @return array
     */
    public function getRawDataAllArray()
    {
        return $this->rawDataAllArray;
    }

    /**
     * @param array $rawDataAllArray
     */
    public function setRawDataAllArray(array $rawDataAllArray = null)
    {
        $this->rawDataAllArray = $rawDataAllArray;
    }

    /**
     * @return array
     */
    public function getMetaData()
    {
        return $this->metaData;
    }

    /**
     * @param array $metaData
     */
    public function setMetaData(array $metaData)
    {
        $this->metaData = $metaData;
    }

    /**
     * @return boolean
     */
    public function isAllowEmptyResults()
    {
        return $this->allowEmptyResults;
    }

    /**
     * @param boolean $allowEmptyResults
     */
    public function setAllowEmptyResults($allowEmptyResults)
    {
        $this->allowEmptyResults = $allowEmptyResults;
    }

    /**
     * @return NDOFilter
     */
    public function getNdoFilter()
    {
        return $this->ndoFilter;
    }

    /**
     * @param NDOFilter $ndoFilter
     */
    public function setNdoFilter(NDOFilter $ndoFilter)
    {
        $this->ndoFilter = $ndoFilter;
    }

    /**
     * @return Provider
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param Provider $provider
     */
    public function setProvider(Provider $provider)
    {
        $this->provider = $provider;
    }
}
