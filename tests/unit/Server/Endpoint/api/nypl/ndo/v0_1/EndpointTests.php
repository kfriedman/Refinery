<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\DIManager;
use NYPL\Refinery\NDO;
use NYPL\Refinery\Server\Endpoint;

class EndpointTests extends \PHPUnit_Framework_TestCase
{
    protected $endpointsToTest = array(
        'NYPL\Refinery\Server\Endpoint\api\nypl\ndo\v0_1\AlertgroupEndpoint',
        'NYPL\Refinery\Server\Endpoint\api\nypl\ndo\v0_1\StaffprofilegroupEndpoint'
    );

    protected $mockCount = 2;

    protected $mockPage = 100;

    protected $mockPerPage = 5;

    protected $mockStatusCode = 200;

    protected $mockDebugArray = array('key' => 'value');

    /**
     * @var \ArrayIterator
     */
    protected $mockNDOGroupItems;

    public function mockEndpoint(Endpoint $endpoint)
    {
        $endpoint->setDebug(true);

        $this->mockNDOGroupItems = new \ArrayIterator(array(\Mockery::mock('NYPL\Refinery\NDO'), \Mockery::mock('NYPL\Refinery\NDO')));

        $mockConfig = \Mockery::mock();
        $mockConfig->shouldIgnoreMissing();
        DIManager::set('Config', $mockConfig);

        if ($endpoint instanceof Endpoint\GetInterface) {
            $mockNDOGroup = \Mockery::mock('NYPL\Refinery\NGOGroup');
            $mockNDOGroup->items = $this->mockNDOGroupItems;

            $mockNDOReader = \Mockery::mock();
            $mockNDOReader
                ->shouldReceive('readNDO')->andReturn($mockNDOGroup);
            $mockNDOReader->shouldIgnoreMissing();
            DIManager::set('NDOReader', $mockNDOReader);

            $mockProvider = \Mockery::mock('NYPL\Refinery\Provider\RESTAPI\D7RefineryServerCurrent');
            $mockProvider
                ->shouldReceive('getCount')->andReturn($this->mockCount)->getMock()
                ->shouldReceive('getPage')->andReturn($this->mockPage)->getMock()
                ->shouldReceive('getPerPage')->andReturn($this->mockPerPage)->getMock()
                ->shouldReceive('getStatusCode')->andReturn($this->mockStatusCode)->getMock()
                ->shouldReceive('getProviderRawData->getRawDataArray')->andReturn($this->mockDebugArray)->getMock();
            $mockProvider->shouldIgnoreMissing();
            DIManager::set('D7RefineryServerCurrent', $mockProvider);

            $endpoint->get(\Mockery::mock('NYPL\Refinery\NDOFilter'));
        }

        DIManager::resetContainer();
    }

    /**
     * @param Endpoint|Endpoint\GetInterface $endpoint
     */
    protected function multiTestGetSetsCount(Endpoint $endpoint)
    {
        $this->assertSame($this->mockCount, $endpoint->getResponse()->getCount());
    }

    /**
     * @param Endpoint|Endpoint\GetInterface $endpoint
     */
    protected function multiTestGetSetsPage(Endpoint $endpoint)
    {
        $this->assertSame($this->mockPage, $endpoint->getResponse()->getPage());
    }

    /**
     * @param Endpoint|Endpoint\GetInterface $endpoint
     */
    protected function multiTestGetSetsPerPage(Endpoint $endpoint)
    {
        $this->assertSame($this->mockPerPage, $endpoint->getResponse()->getPerPage());
    }

    /**
     * @param Endpoint|Endpoint\GetInterface $endpoint
     */
    protected function multiTestGetSetsStatusCode(Endpoint $endpoint)
    {
        $this->assertSame($this->mockStatusCode, $endpoint->getResponse()->getStatusCode());
    }

    /**
     * @param Endpoint|Endpoint\GetInterface $endpoint
     */
    protected function multiTestGetSetsDebugArray(Endpoint $endpoint)
    {
        $this->assertSame(array($this->mockDebugArray), $endpoint->getResponse()->getDebugArray());
    }

    /**
     * @param Endpoint|Endpoint\GetInterface $endpoint
     */
    protected function multiTestGetSetsData(Endpoint $endpoint)
    {
        $this->assertSame((array) $this->mockNDOGroupItems, $endpoint->getResponse()->getData());
    }

    public function testRunEndpointTests()
    {
        foreach ($this->endpointsToTest as $endpointName) {
            /**
             * @var Endpoint $endpoint
             */
            $endpoint = new $endpointName();

            $this->mockEndpoint($endpoint);

            if ($endpoint instanceof Endpoint\GetInterface) {
                $this->multiTestGetSetsCount($endpoint);
                $this->multiTestGetSetsPage($endpoint);
                $this->multiTestGetSetsPerPage($endpoint);
                $this->multiTestGetSetsStatusCode($endpoint);
                $this->multiTestGetSetsDebugArray($endpoint);

                $this->multiTestGetSetsData($endpoint);
            }
        }
    }
}
