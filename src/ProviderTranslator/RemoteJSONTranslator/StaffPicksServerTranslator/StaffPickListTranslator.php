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
class StaffPickListTranslator extends StaffPicksServerTranslator
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
        $rawData = $provider->clientGet('manifest.yaml', $ndoFilter);

        if (isset($rawData['lists'][$ndoFilter->getFilterID()])) {
            return $rawData['lists'][$ndoFilter->getFilterID()];
        } else {
            throw new RefineryException('List requested (' . $ndoFilter->getFilterID().  ') was not found', 404);
        }
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\StaffPick\StaffPickItem()
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawDataArray = $providerRawData->getRawDataArray();

        $ndo = new NDO\StaffPick\StaffPickList();

        $ndo->setRead(true);

        $ndo->setNdoID($rawDataArray['id']);
        $ndo->setListDate($rawDataArray['date']);
        $ndo->setListType($rawDataArray['type']);

        if (isset($rawDataArray['_relationships']['picks'])) {
            foreach ($rawDataArray['_relationships']['picks'] as $pickID) {
                $ndo->addPick(new NDO\StaffPick($pickID));
            }
        }

        if (isset($rawDataArray['_relationships']['features'])) {
            foreach ($rawDataArray['_relationships']['features'] as $pickID) {
                $ndo->addFeature(new NDO\StaffPick($pickID));
            }
        }

        if (isset($rawDataArray['_next'])) {
            $ndo->setNextList(new NDO\StaffPick\StaffPickList($rawDataArray['_next']));
        }

        if (isset($rawDataArray['_previous'])) {
            $ndo->setPreviousList(new NDO\StaffPick\StaffPickList($rawDataArray['_previous']));
        }

        return $ndo;
    }
}
