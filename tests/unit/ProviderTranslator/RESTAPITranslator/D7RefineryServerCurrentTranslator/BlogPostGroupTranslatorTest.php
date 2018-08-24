<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerCurrentTranslator\BlogPostGroupTranslator;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\NDOFilter;

class BlogPostGroupTranslatorTest extends \PHPUnit_Framework_TestCase
{
    protected $blogPostGroup;
    protected $rawData;

    public function setUp()
    {
        $this->blogPostGroup = new BlogPostGroupTranslator();
        $this->rawData = file_get_contents(__DIR__.'../../../../Fixtures/JSON/blogpostgroup.json');
    }

    public function testBlogPostGroupRead()
    {
        $provider = \Mockery::mock('\NYPL\Refinery\Provider\RESTAPI');
        $provider->shouldReceive('clientGet')->andReturn($this->rawData);
        $filter = \Mockery::mock('\NYPL\Refinery\NDOFilter');
        $filter->shouldReceive('getFilterID')->andReturn('9999999');

        $response = $this->blogPostGroup->read($provider, $filter);

        self::assertJson($response);
    }

    public function testBlogPostGroupTranslate()
    {
        $providerRawData= new ProviderRawData($this->rawData, '', array('this', 'that'), false, new NDOFilter());

        $ndo = $this->blogPostGroup->translate($providerRawData);

        self::assertInstanceOf('\\NYPL\\Refinery\\NDO', $ndo);
    }
}
?>