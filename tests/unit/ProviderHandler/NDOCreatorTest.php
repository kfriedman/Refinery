<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\NDO\Content\Page\BlogPost;
use NYPL\Refinery\ProviderHandler\NDOCreator;
use NYPL\Refinery\NDO\Content\Page\BasicPage;

class NDOCreatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Provider
     */
    protected $mocked_d7_basic_page_provider;

    protected function setUp()
    {
        $provider = \Mockery::mock('NYPL\Refinery\Provider\RESTAPI\D7RefineryServerCurrent');
        $provider->shouldReceive('createNDO')->andReturn(new BasicPage());
        $provider->shouldReceive('isDebug')->andReturn(false);

        $provider->shouldReceive('setProviderRawData');

        $this->mocked_d7_basic_page_provider = $provider;
    }

    public function testRawDataIsCreated()
    {
        $ndo = new BasicPage();
        $rawData = file_get_contents(__DIR__.'/../Fixtures/JSON/basicpage.json');
        $rawDataArray = json_decode($rawData);

        NDOCreator::createNDO($this->mocked_d7_basic_page_provider, $ndo, (array) $rawDataArray);
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testWrongNDOTypeGiven()
    {
        $ndo = new BlogPost();
        NDOCreator::createNDO($this->mocked_d7_basic_page_provider, $ndo);
    }
}
?>