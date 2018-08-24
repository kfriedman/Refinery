<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\NDO\BasicPageGroup;
use NYPL\Refinery\NDO\Content\Page\BasicPage;
use NYPL\Refinery\NDO\Content\Page\BlogPost;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider\RESTAPI\D7RefineryServerCurrent;
use NYPL\Refinery\ProviderHandler\NDOReader;

class NDOReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Provider
     */
    protected $mocked_d7_basic_page_provider;

    protected function setUp()
    {
        $provider = \Mockery::mock('NYPL\Refinery\Provider\RESTAPI\D7RefineryServerCurrent');
        $provider->shouldReceive('readNDO')->andReturn(new BasicPage());
        $provider->shouldReceive('isDebug')->andReturn(false);

        $provider->shouldReceive('setProviderRawData');

        $this->mocked_d7_basic_page_provider = $provider;
    }

    public function testReadNDOReturnsNDOObject()
    {
        $ndo = new BasicPage();
        $returned_ndo = NDOReader::readNDO($this->mocked_d7_basic_page_provider, $ndo, new NDOFilter());
        $this->assertInstanceOf('NYPL\Refinery\NDO', $returned_ndo);
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testReadNDOReturnsWrongNDOType()
    {
        $ndo = new BlogPost();
        NDOReader::readNDO($this->mocked_d7_basic_page_provider, $ndo, new NDOFilter());
    }

    public function testReadNDOReturnsSameNDOType()
    {
        $ndo = new BasicPage();
        $returned_ndo = NDOReader::readNDO($this->mocked_d7_basic_page_provider, $ndo, new NDOFilter());
        $this->assertInstanceOf(get_class($ndo), $returned_ndo);
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testProviderHasRequiredParameters()
    {
        $ndo = new BasicPageGroup();
        $provider = new D7RefineryServerCurrent();

        NDOReader::readNDO($provider, $ndo, new NDOFilter());
    }

    /**
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testReadNDOExpectsProviderObject()
    {
        $ndo = new BasicPageGroup();
        NDOReader::readNDO('This is not a provider object', $ndo, new NDOFilter());
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testRawDataPassedInConstructorNotJSON()
    {
        $ndo = new BasicPage();
        NDOReader::readNDO($this->mocked_d7_basic_page_provider, $ndo, new NDOFilter(), 'String not JSON');
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testRawDataPassedInConstructorIsNotString()
    {
        $ndo = new BasicPage();
        NDOReader::readNDO($this->mocked_d7_basic_page_provider, $ndo, new NDOFilter(), 1);
    }

    public function testRawDataPassedInConstructorIsValidJSON()
    {
        $ndo = new BasicPage();
        $returned_ndo = NDOReader::readNDO($this->mocked_d7_basic_page_provider, $ndo, new NDOFilter(), '{ "test" : 1 }');
        $this->assertInstanceOf(get_class($ndo), $returned_ndo);
    }

    public function testRawDataPassedInConstructorIsArray()
    {
        $ndo = new BasicPage();
        $returned_ndo = NDOReader::readNDO($this->mocked_d7_basic_page_provider, $ndo, new NDOFilter(), array('test' => 1));
        $this->assertInstanceOf(get_class($ndo), $returned_ndo);
    }
}