<?php
namespace NYPL\Refinery\Tests\Integration;

use NYPL\Refinery\Server;
use NYPL\Refinery\Config\Config;
use NYPL\Refinery\Cache\CacheData;
use NYPL\Refinery\Cache\CacheData\RawData;
use NYPL\Refinery\Cache\CacheClient;

abstract class APIBase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $version = '';

    const FIXTURE_ENVIRONMENT = 'production';

    protected function setUp()
    {
        if (!$this->version) {
            throw new \Exception('Version was not set');
        }

        Config::initialize(new \Configula\Config(__DIR__ . '/../../config/app'));

        Config::addOverrideItem('DefaultProviders.D7RefineryServerCurrent.Host', Config::getItem(self::FIXTURE_ENVIRONMENT . '.DefaultProviders.D7RefineryServerCurrent.Host', null, true));

        Config::addOverrideItem('DefaultProviders.StaffPicksServer.Host', Config::getItem(self::FIXTURE_ENVIRONMENT . '.DefaultProviders.StaffPicksServer.Host', null, true));
        Config::addOverrideItem('DefaultProviders.StaffPicksServer.BaseURL', Config::getItem(self::FIXTURE_ENVIRONMENT . '.DefaultProviders.StaffPicksServer.BaseURL', null, true));

        Config::addOverrideItem('Cache.RawData.Enabled', true);
        Config::addOverrideItem('Cache.CacheNDO.Enabled', false);
        Config::addOverrideItem('Cache.CachedResponse.Enabled', false);

        if (Config::getItem('Environment', null, true) != 'local') {
            throw new \Exception('Test currently running in ' . Config::getItem('Environment', null, true) . ' environment and must be run in local environment');
        }
    }

    protected function tearDown()
    {
        Config::reset();
        CacheClient::disconnect();
    }

    public function testAPI($usesIndex = false)
    {
        $fixtureResponseLocation = dirname(__FILE__) . '/fixtures/' . $this->version . '/response';
        $fixtureRawDataLocation = dirname(__FILE__) . '/fixtures/' . $this->version . '/rawdata';

        if (!file_exists($fixtureResponseLocation) || !file_exists($fixtureRawDataLocation)) {
            throw new \Exception('Required directory for v' . $this->version . ' does not exist');
        }

        $cacheKeys = CacheClient::keys('RawData:*');

        foreach ($cacheKeys as $cacheKey) {
            CacheClient::del($cacheKey);
        }

        if ($usesIndex) {
            $rawDataArray = explode("\n", file_get_contents($fixtureRawDataLocation . '/index.txt'));
        } else {
            $rawDataArray = array_slice(scandir($fixtureRawDataLocation), 2);
        }

        foreach ($rawDataArray as $rawDataKey) {
            if ($rawDataKey) {
                try {
                    if ($usesIndex) {
                        $fileName = sha1($rawDataKey);
                        $rawDataURL = $rawDataKey;
                    } else {
                        $fileName = $rawDataKey;
                        $rawDataURL = urldecode($rawDataKey);
                    }

                    $fixtureRawData = json_decode(file_get_contents($fixtureRawDataLocation . '/' . $fileName), true);

                    $rawData = new RawData($rawDataURL);
                    $rawData->save($fixtureRawData);
                } catch (\Exception $exception) {
                    throw new \Exception('Unable to process file: ' . $rawDataKey . ' for URL (' . $rawDataURL . ') (' . $exception->getMessage() . ')');
                }
            }
        }

        $fixtureList = Config::getItem('Testing.Integration.URLS', null, true);

        foreach ($fixtureList as $fixtureListItem) {
            if ($usesIndex) {
                $fixtureLocation = $fixtureResponseLocation . '/' . sha1($fixtureListItem);
            } else {
                $fixtureLocation = $fixtureResponseLocation . '/' . urlencode($fixtureListItem);
            }

            if (file_exists($fixtureLocation)) {
                $urlComponents = parse_url($fixtureListItem);

                if (isset($urlComponents['query'])) {
                    parse_str($urlComponents['query'], $queryParameters);
                } else {
                    $queryParameters = array();
                }

                $response = Server::processRequest(explode('/', $urlComponents['path']), 'GET', $queryParameters);

                $fixture = json_decode(file_get_contents($fixtureLocation), true);

                $this->assertEquals($fixture, $response->getData(), 'Test for v' . $this->version . ' failed: ' . $fixtureListItem . ' on ' . Config::getItem('DefaultProviders.D7RefineryServerCurrent.Host') . ' (' . $fixtureLocation . print_r($response->getData(), true) . ')');
            } else {
                echo 'No fixture found for ' . $fixtureListItem . ' in v' . $this->version . "\n";
            }
        }
    }
}
