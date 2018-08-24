<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\Config\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    private static $mockConfigulaObject;

    public static function setUpBeforeClass()
    {
        self::$mockConfigulaObject = \Mockery::mock('Configula\Config');

        self::$mockConfigulaObject->shouldReceive('getItem')->withArgs(array('Environment', \Mockery::any()))->andReturn('development');

        self::$mockConfigulaObject->shouldReceive('getItem')->with('BadEnvironment')->andReturn(null);
        self::$mockConfigulaObject->shouldReceive('getItem')->with('GoodEnvironment')->andReturn(array());

        self::$mockConfigulaObject->shouldReceive('getItem')->withArgs(array('development.Server.Performance', \Mockery::any()))->andReturn(array());
        self::$mockConfigulaObject->shouldReceive('getItem')->withArgs(array('development.Cache.Enabled', \Mockery::any()))->andReturn(array());
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testGettingItemBeforeInitializingConfigFails()
    {
        Config::getItem('Test item');
    }

    public function testDefaultEnvironmentConstantIsValid()
    {
        $this->assertInternalType('string', Config::DEFAULT_ENVIRONMENT);
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testInitializingWithBadEnvironmentNameFails()
    {
        Config::initialize(self::$mockConfigulaObject, 'BadEnvironment');
    }

    public function testInitializingWithGoodEnvironmentName()
    {
        Config::initialize(self::$mockConfigulaObject, 'GoodEnvironment');
        $this->assertInstanceOf('Configula\Config', \PHPUnit_Framework_Assert::getStaticAttribute('NYPL\Refinery\Config\Config', 'configulaObject'));
    }

    /**
     * @depends testInitializingWithGoodEnvironmentName
     */
    public function testGetWithReturnValueNullReturnsFalse()
    {
        self::$mockConfigulaObject->shouldReceive('getItem')->with('NullReturnTest', \Mockery::any())->andReturn(null);

        $this->assertSame(Config::getItem('NullReturnTest', null, true), false);
    }

    /**
     * @depends testInitializingWithGoodEnvironmentName
     */
    public function testGetWithoutEnvironment()
    {
        self::$mockConfigulaObject->shouldReceive('getItem')->with('NoEnvironment', \Mockery::any())->andReturn('DefaultValue');

        $this->assertSame(Config::getItem('NoEnvironment', null, true), 'DefaultValue');
    }

    /**
     * @depends testInitializingWithGoodEnvironmentName
     */
    public function testInitializeSetsEnvironment()
    {
        $this->assertGreaterThan(0, strlen(Config::getEnvironment()));
    }

    /**
     * @depends testInitializingWithGoodEnvironmentName
     */
    public function testGetItemWorksInDefaultEnvironment()
    {
        self::$mockConfigulaObject->shouldReceive('getItem')->with(Config::DEFAULT_ENVIRONMENT)->andReturn(array());

        Config::initialize(self::$mockConfigulaObject);

        self::$mockConfigulaObject->shouldReceive('getItem')->with(Config::DEFAULT_ENVIRONMENT . '.Item', '')->andReturn('DefaultValue');
        $this->assertSame(Config::getItem('Item'), 'DefaultValue');
    }

    /**
     * @depends testInitializingWithGoodEnvironmentName
     */
    public function testGetItemReturnsRightValues()
    {
        self::$mockConfigulaObject->shouldReceive('getItem')->with(Config::getEnvironment() . '.Item1', '')->andReturn('TestValue');
        self::$mockConfigulaObject->shouldReceive('getItem')->with(Config::getEnvironment() . '.Item2', 'DefaultValue')->andReturn('DefaultValue');

        $this->assertSame(Config::getItem('Item1'), 'TestValue');
        $this->assertSame(Config::getItem('Item2', 'DefaultValue'), 'DefaultValue');
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testCheckExtensionRequiredThrowsException()
    {
        self::$mockConfigulaObject->shouldReceive('getItem')->with('Extensions.RequiredExtension', \Mockery::any())->andReturn('RequiredExtension');

        Config::initialize(self::$mockConfigulaObject);

        Config::checkExtension('RequiredExtension');
    }

    public function testCheckExtensionRequiredRunning()
    {
        self::$mockConfigulaObject->shouldReceive('getItem')->with('Extensions.redis', \Mockery::any())->andReturn('redis');

        Config::initialize(self::$mockConfigulaObject);

        Config::checkExtension('redis');
    }
}
