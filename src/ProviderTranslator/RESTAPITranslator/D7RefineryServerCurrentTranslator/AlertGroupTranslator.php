<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerCurrentTranslator;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\FilterableInterface;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerTranslator;
use NYPL\Refinery\QueryParameter;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class AlertGroupTranslator extends D7RefineryServerTranslator
    implements RESTAPITranslator\ReadInterface, FilterableInterface
{
    /**
     * @param NDOFilter $ndoFilter
     */
    public function translateFilter(NDOFilter $ndoFilter)
    {
        $filter = $ndoFilter->getQueryParameter('filter');

        $newFilter = array();

        foreach ($filter->getValue() as $filterName => $filterValue) {
            switch ($filterName) {
                case 'scope':
                    if ($filterValue == 'all') {
                        $filterValue = 'all|home';
                    }
                    $newFilter['field_alert_type'] = $filterValue;
                    break;
                default:
                    $newFilter[$filterName] = $filterValue;
                    break;
            }
        }

        $queryParameter = new QueryParameter();
        $queryParameter->setValue($newFilter);

        $ndoFilter->setQueryParameterArray(array('filter' => $queryParameter));
    }

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
        if ($ndoFilter->getQueryParameter('filter')->getValue()) {
            $this->translateFilter($ndoFilter);
        }

        return $provider->clientGet('node/alert_message', null, $ndoFilter, true);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\AlertGroup
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawDataArray = $providerRawData->getRawDataArray();

        $ndo = new NDO\AlertGroup();

        foreach ($rawDataArray as $rawData) {
            $providerTranslator = new AlertTranslator();
            $ndo->append($providerTranslator->translate(new ProviderRawData($rawData)));
        }

        return $ndo;
    }

    /**
     * @param NDOFilter       $ndoFilter
     * @param ProviderRawData $providerRawData
     */
    public function applyFilter(NDOFilter $ndoFilter, ProviderRawData $providerRawData)
    {
        if ($ndoFilter->getFilterName('tid')) {
            $filteredRawData = array();

            $rawDataArray = $providerRawData->getRawDataArray();

            foreach ($rawDataArray as $rawData) {
                if ($this->checkAlertAppliesToLocation($ndoFilter->getFilterName('tid'), $rawData)) {
                    $filteredRawData[] = $rawData;
                }
            }

            $providerRawData->setRawDataArray($filteredRawData);
        }
    }

    protected function checkAlertDateIsUpcoming(array $rawData)
    {
        if (!$rawData['field_date_range']) {
            return true;
        } else {
            $endDate = new \DateTime($rawData['field_date_range']['und'][0]['value2'], new \DateTimeZone($rawData['field_date_range']['und'][0]['timezone_db']));

            if ($endDate->format('H:i') == '00:00') {
                if ($endDate->add(new \DateInterval('P1D')) >= new \DateTime()) {
                    return true;
                }
            } else {
                if ($endDate >= new \DateTime()) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function checkAlertAppliesToLocation($tid, array $rawData)
    {
        if (!$rawData['field_alert_locations']) {
            return true;
        } else {
            foreach ($rawData['field_alert_locations']['und'] as $searchValue) {
                if (in_array($tid, $searchValue)) {
                    return true;
                }
            }
        }

        return false;
    }
}
