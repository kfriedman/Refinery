<?php
namespace NYPL\Refinery\ProviderTranslator\RemoteJSONTranslator\StaffPicksServerTranslator;

use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Provider\RemoteJSON;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\RemoteJSONTranslator;
use NYPL\Refinery\ProviderTranslator\RemoteJSONTranslator\StaffPicksServerTranslator;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class StaffPickTranslator extends StaffPicksServerTranslator
    implements RemoteJSONTranslator\ReadInterface
{
    /**
     * @param RemoteJSON $provider
     * @param NDOFilter  $ndoFilter
     * @param bool       $allowEmptyResults
     *
     * @return string
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    public function read(RemoteJSON $provider, NDOFilter $ndoFilter, $allowEmptyResults = false)
    {
        $rawData = $provider->clientGet('manifest.yaml', $ndoFilter, null, $allowEmptyResults);

        if (isset($rawData['picks'][$ndoFilter->getFilterID()])) {
            return $rawData['picks'][$ndoFilter->getFilterID()];
        } else {
            throw new RefineryException('Pick requested (' . $ndoFilter->getFilterID().  ') was not found', 404);
        }
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\StaffPick
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawDataArray = $providerRawData->getRawDataArray();

        $ndo = new NDO\StaffPick();

        $ndo->setNdoID($rawDataArray['id']);
        $ndo->setText($rawDataArray['text']);
        $ndo->setPickerName($rawDataArray['name']);
        $ndo->setLocation($rawDataArray['location']);
        $ndo->setSort($rawDataArray['sort']);

        if (isset($rawDataArray['_relationships']['item'])) {
            $ndo->setItem(new NDO\StaffPick\StaffPickItem($rawDataArray['_relationships']['item']));
        }

        if (isset($rawDataArray['_relationships']['list'])) {
            $ndo->setList(new NDO\StaffPick\StaffPickList($rawDataArray['_relationships']['list']));
        }

        if (isset($rawDataArray['_relationships']['age'])) {
            $ndo->setAge(new NDO\StaffPick\StaffPickAge($rawDataArray['_relationships']['age']));
        }

        $ndo->setFeature($rawDataArray['feature']);

        return $ndo;
    }
}
