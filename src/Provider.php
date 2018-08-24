<?php
namespace NYPL\Refinery;

use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderTranslator\FilterableInterface;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\CreateInterface;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\ReadInterface;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\UpdateInterface;

/**
 * Abstract class for all raw data Providers in the Refinery.
 *
 * @package NYPL\Refinery
 */
abstract class Provider
{
    /**
     * Get the count of objects returned by the Provider.
     *
     * @return int|null
     */
    abstract public function getCount();

    /**
     * Get the current page being returned by the Provider.
     *
     * @return int|null
     */
    abstract public function getPage();

    /**
     * Get the number of records per page returned by the Provider.
     *
     * @return int|null
     */
    abstract public function getPerPage();

    /**
     * Get the HTTP status code returned by the Provider.
     *
     * @return int|null
     */
    abstract public function getStatusCode();

    /**
     * Get the meta data returned by the Provider.
     *
     * @return array
     */
    abstract public function getProviderMetaData();

    /**
     * Checks to make sure the Provider is healthy.
     *
     * @return HealthCheckResponse
     */
    abstract public function checkHealth();

    /**
     * The ProviderRawData object created from the response returned by the
     * Provider.
     *
     * @var ProviderRawData
     */
    protected $providerRawData;

    /**
     * The key used to parse the data results returned by the Provider.
     *
     * @var string
     */
    protected $rawDataKey = '';

    /**
     * An array of non-critical meta notices
     *
     * @var array
     */
    protected $metaNotices = array();

    /**
     * @var bool
     */
    protected $initialized = false;

    /**
     * Read an NDO from the current Provider by instantiating a
     * ProviderTranslator to do the reading and translating.
     *
     * @param NDO       $ndo               The NDO that you want to read.
     * @param NDOFilter $ndoFilter         The filter that should be used to read the NDO.
     * @param bool      $allowEmptyResults Whether empty results returned by the Provider is considered an error.
     *
     * @return null|NDO
     * @throws RefineryException
     */
    public function readNDO(NDO $ndo, NDOFilter $ndoFilter = null, $allowEmptyResults = false)
    {
        $providerTranslator = ProviderTranslatorFactory::createProviderTranslator($this, $ndo, 'read');

        if (!$this->getProviderRawData()) {
            $this->setProviderRawData($this->readProviderRawData($providerTranslator, $ndo, $ndoFilter, $allowEmptyResults));
        }

        $this->filterProviderRawData($providerTranslator, $ndoFilter);

        $ndo = $this->translateProviderRawData($providerTranslator, $allowEmptyResults);

        if ($ndo instanceof NDOGroup) {
            $this->setNDOGroupCounts($ndo);
        }

        return $ndo;
    }

    /**
     * Update an NDO from the current Provider.
     *
     * @param NDO       $ndo       The NDO that you want to be the updated record.
     * @param NDOFilter $ndoFilter The filter used to determine the record to be updated on the Provider.
     *
     * @return NDO
     * @throws RefineryException
     */
    public function updateNDO(NDO $ndo, NDOFilter $ndoFilter)
    {
        /**
         * @var $providerTranslator ProviderTranslatorInterface|UpdateInterface
         */
        $providerTranslator = ProviderTranslatorFactory::createProviderTranslator($this, $ndo, 'update');

        if ($this->getProviderRawData()) {
            $ndo = $providerTranslator->translate($this->getProviderRawData());
        }

        /**
         * @var $this RESTAPI
         */
        $providerRawData = new ProviderRawData($providerTranslator->update($this, $ndo, $ndoFilter));

        $this->setProviderRawData($providerRawData);

        try {
            return $providerTranslator->translate($this->getProviderRawData());
        } catch (\Exception $exception) {
            throw new RefineryException('Unable to translate data: ' . $exception->getMessage());
        }
    }

    /**
     * Create an NDO on the current Provider.
     *
     * @param NDO $ndo The NDO that you want to create.
     *
     * @return NDO
     * @throws RefineryException
     */
    public function createNDO(NDO $ndo)
    {
        /**
         * @var $providerTranslator ProviderTranslatorInterface|CreateInterface
         */
        $providerTranslator = ProviderTranslatorFactory::createProviderTranslator($this, $ndo, 'create');

        if ($this->getProviderRawData()) {
            $ndo = $providerTranslator->translate($this->getProviderRawData());
        }

        /**
         * @var $this RESTAPI
         */
        $providerRawData = new ProviderRawData($providerTranslator->create($this, $ndo));

        $this->setProviderRawData($providerRawData);

        try {
            return $providerTranslator->translate($this->getProviderRawData());
        } catch (\Exception $exception) {
            throw new RefineryException('Unable to translate data: ' . $exception->getMessage());
        }
    }

    /**
     * Get the name of the current Provider.
     *
     * @return string
     */
    public function getName()
    {
        return get_class($this);
    }

    /**
     * Getter for the ProviderRawData object created from the response returned
     * by the Provider.
     *
     * @return ProviderRawData
     */
    public function getProviderRawData()
    {
        return $this->providerRawData;
    }

    /**
     * Setter for the ProviderRawData object created from the response returned
     * by the Provider.
     *
     * @param ProviderRawData $providerRawData
     */
    public function setProviderRawData(ProviderRawData $providerRawData = null)
    {
        $this->providerRawData = $providerRawData;
    }

    /**
     * Return the ProviderRawData object by having the ProviderTranslator
     * read the response from the Provider.
     *
     * @param ProviderTranslatorInterface|ReadInterface $providerTranslator The ProviderTranslator to do the reading.
     * @param NDO                                       $ndo                The NDO that you want to read.
     * @param NDOFilter                                 $ndoFilter          The filter that should be used to read the NDO.
     * @param bool                                      $allowEmptyResults  Whether empty results returned by the Provider is considered an error.
     *
     * @return null|ProviderRawData
     * @throws RefineryException
     */
    protected function readProviderRawData(ProviderTranslatorInterface $providerTranslator, NDO $ndo, NDOFilter $ndoFilter = null, $allowEmptyResults = false)
    {
        try {
            if (!$this->getProviderRawData()) {
                /**
                 * @var $this RESTAPI
                 */
                $rawData = $providerTranslator->read($this, $ndoFilter, $allowEmptyResults);

                return new ProviderRawData($rawData, $this->getRawDataKey(), $this->getProviderMetaData(), $allowEmptyResults, $ndoFilter, $this);
            }
        } catch (RefineryException $exception) {
            throw new RefineryException('Reading NDO (' . get_class($ndo) . ') raw data failed: ' . $exception->getMessage(), $exception->getStatusCode());
        }

        return null;
    }

    /**
     * Filter the ProviderRawData built from the Provider's response if it
     * implements the FilterableInterface.
     *
     * @param ProviderTranslatorInterface $providerTranslator
     * @param NDOFilter                   $ndoFilter
     *
     * @throws RefineryException
     */
    protected function filterProviderRawData(ProviderTranslatorInterface $providerTranslator, NDOFilter $ndoFilter = null)
    {
        if ($providerTranslator instanceof FilterableInterface) {
            $providerTranslator->applyFilter($ndoFilter, $this->getProviderRawData());

            $this->getProviderRawData()->setRawDataAllArray(array());
        }
    }

    /**
     * Translate the ProviderRawData built fom the Provider's response into an
     * NDO and return the NDO.
     *
     * @param ProviderTranslatorInterface $providerTranslator
     * @param bool                        $allowEmptyResults
     *
     * @return null|NDO
     * @throws RefineryException
     */
    protected function translateProviderRawData(ProviderTranslatorInterface $providerTranslator, $allowEmptyResults = false)
    {
        if (!$allowEmptyResults || $this->getProviderRawData()->getRawDataArray()) {
            try {
                return $providerTranslator->translate($this->getProviderRawData());
            } catch (\Exception $exception) {
                if ($this->getProviderRawData()) {
                    $rawData = $this->getProviderRawData()->getRawDataArray();
                } else {
                    $rawData = null;
                }

                throw new RefineryException('Unable to translate data: ' . $exception->getMessage() . ' in ' . $exception->getFile() . ' on line ' . $exception->getLine(), $rawData);
            }
        } else {
            return null;
        }
    }

    /**
     * @param NDOGroup $ndoGroup
     */
    protected function setNDOGroupCounts(NDOGroup $ndoGroup)
    {
        $ndoGroup->setPerPage($this->getPerPage());
        $ndoGroup->setPage($this->getPage());
        $ndoGroup->setCount($this->getCount());
    }

    /**
     * Getter for the key used to parse the data results returned by the
     * Provider.
     *
     * @return string
     */
    public function getRawDataKey()
    {
        return $this->rawDataKey;
    }

    /**
     * @return array
     */
    public function getMetaNotices()
    {
        return $this->metaNotices;
    }

    /**
     * @param string $errorMessage
     * @param string $debugMessage
     */
    public function addMetaNotice($errorMessage = '', $debugMessage = '')
    {
        $this->metaNotices[] = array(
            'title' => $errorMessage,
            'detail' => $debugMessage
        );
    }

    /**
     * @return boolean
     */
    public function isInitialized()
    {
        return $this->initialized;
    }

    /**
     * @param boolean $initialized
     */
    public function setInitialized($initialized)
    {
        $this->initialized = $initialized;
    }
}
