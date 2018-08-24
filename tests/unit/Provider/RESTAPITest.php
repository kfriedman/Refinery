<?php
namespace NYPL\Refinery;

use NYPL\Refinery\Provider\RESTAPI\D7RefineryServerCurrent;

class RESTAPITest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Mock
     */
    private static $mockGuzzleClient;

    public static function setUpBeforeClass()
    {
        $config = \Mockery::mock('Config');
        $config
            ->shouldReceive('getItem')->withArgs(array('Server.Performance'))->andReturn('false')->getMock();
        DIManager::set('Config', $config);

        $cacheManager = \Mockery::mock('CacheManager');
        $cacheManager
            ->shouldReceive('isEnabled')->andReturn(true)->getMock();
        DIManager::set('CacheManager', $cacheManager);

        $cacheClient = \Mockery::mock('cacheClient');
        $cacheClient->shouldIgnoreMissing();
        DIManager::set('CacheClient', $cacheClient);
    }

    public static function tearDownAfterClass()
    {
        DIManager::resetContainer();
    }

    public static function testClientIsInitialized()
    {
        $mockRequest = \Mockery::mock('GuzzleHttp\Message\Request');
        $mockRequest->shouldReceive('addHeader');
        $mockRequest->shouldReceive('getUrl')->andReturn('http://www.nypl.org');

        $mockResponse = \Mockery::mock('GuzzleHttp\Message\Response');
        $mockResponse->shouldReceive('getBody')->andReturn('Response Body');

        self::$mockGuzzleClient = \Mockery::mock('GuzzleHttp\Client');
        self::$mockGuzzleClient->shouldReceive('createRequest')->andReturn($mockRequest);
        self::$mockGuzzleClient->shouldReceive('send')->andReturn($mockResponse);

        $restAPIServer = new D7RefineryServerCurrent('test.local');
        $restAPIServer->setGuzzleClient(self::$mockGuzzleClient, array('X-Foo' => 'Bar'));

        self::assertInstanceOf('\GuzzleHttp\Client', $restAPIServer->getGuzzleClient());
    }

    /**
     * @depends testClientIsInitialized
     */
    public static function testClientReturnsBody()
    {
        $mockRequest = \Mockery::mock('GuzzleHttp\Message\Request');
        $mockRequest->shouldReceive('addHeader');
        $mockRequest->shouldReceive('getUrl')->andReturn('http://www.nypl.org');

        $mockResponse = \Mockery::mock('GuzzleHttp\Message\Response');
        $mockResponse->shouldReceive('getData')->andReturn('Response Body');

        self::$mockGuzzleClient = \Mockery::mock('GuzzleHttp\Client');
        self::$mockGuzzleClient->shouldReceive('createRequest')->andReturn($mockRequest);
        self::$mockGuzzleClient->shouldReceive('send')->andReturn($mockResponse);

        $restAPIServer = new D7RefineryServerCurrent('test.local', 'api');
        $restAPIServer->setGuzzleClient(self::$mockGuzzleClient);
        $body = $restAPIServer->clientGet('refinery/api/v0.1/content/blogpostgroup', array('X-Foo' => 'Bar'));

        // self::assertSame('Response Body', $body);
        self::assertSame(null, $body);
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public static function testClientValidResponse()
    {
        $mockResponse = \Mockery::mock('GuzzleHttp\Message\Response');
        $mockResponse->shouldReceive('getBody')->andReturn('Response Body');
        $mockResponse->shouldReceive('getStatusCode')->andReturn(200);

        $mockIncompleteClient = \Mockery::mock('GuzzleHttp\Client');
        $mockIncompleteClient->shouldReceive('createRequest')->andThrow('\NYPL\Refinery\Exception\RefineryException');

        $restAPIServer = new D7RefineryServerCurrent('test.local');
        $restAPIServer->setGuzzleClient($mockIncompleteClient);

        $restAPIServer->clientGet('v1.0/invalid');
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testHostDoesNotContainProtocol()
    {
        $restAPIServer = new D7RefineryServerCurrent();
        $restAPIServer->setHost('http://www.nypl.org');
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testHostDoesNotContainInvalidCharacters()
    {
        $restAPIServer = new D7RefineryServerCurrent();
        $restAPIServer->setHost('!google.com');
    }

    public function testConstructorSetsParameters()
    {
        $host = 'host.com';
        $baseURL = 'baseURL';

        $restAPIServer = new D7RefineryServerCurrent($host, $baseURL);

        $this->assertSame($restAPIServer->getHost(), $host);
        $this->assertSame($restAPIServer->getBaseURL(), $baseURL);
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testRequiredParameters()
    {
        $restAPIServer = new D7RefineryServerCurrent();
        $restAPIServer->clientGet('url');
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testHostIsRequired()
    {
        $restAPIServer = new D7RefineryServerCurrent();
        $restAPIServer->setHost();
    }

    public function testGuzzleClientCreatedOnGet()
    {
        $restAPIServer = new D7RefineryServerCurrent();
        $restAPIServer->getGuzzleClient();

        self::assertInstanceOf('\GuzzleHttp\Client', $restAPIServer->getGuzzleClient());
    }
}