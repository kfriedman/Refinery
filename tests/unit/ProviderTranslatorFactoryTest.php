<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\NDO\Content\Page\BlogPost;
use NYPL\Refinery\ProviderTranslatorFactory;

class ProviderTranslatorFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testTranslatorExists()
    {
        $provider = \Mockery::mock('NYPL\Refinery\Provider\RESTAPI\D7RefineryServerCurrent');
        $provider->shouldReceive('getName')->andReturn('Fake Provider Name');

        ProviderTranslatorFactory::createProviderTranslator($provider, new BlogPost(), 'read');
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testMethodExists()
    {
        $provider = \Mockery::mock('NYPL\Refinery\Provider\RESTAPI\D7RefineryServerCurrent');
        $provider->shouldReceive('getName')->andReturn('NYPL\Refinery\Provider\RESTAPI\D7RefineryServerCurrent');

        ProviderTranslatorFactory::createProviderTranslator($provider, new BlogPost(), 'Fake Method');
    }

    public function testFactoryReturnsTranslator()
    {
        $provider = \Mockery::mock('NYPL\Refinery\Provider\RESTAPI\D7RefineryServerCurrent');
        $provider->shouldReceive('getName')->andReturn('NYPL\Refinery\Provider\RESTAPI\D7RefineryServerCurrent');

        $translator = ProviderTranslatorFactory::createProviderTranslator($provider, new BlogPost(), 'read');
        $this->assertInstanceOf('NYPL\Refinery\ProviderTranslatorInterface', $translator);
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testNoTranslatorMethod()
    {
        $provider = \Mockery::mock('NYPL\Refinery\Provider\RESTAPI\D7RefineryServerCurrent');
        $provider->shouldReceive('getName')->andReturn('NYPL\Refinery\Provider\RESTAPI\D7RefineryServerCurrent');

        $translator = ProviderTranslatorFactory::createProviderTranslator($provider, new BlogPost(), 'conflate');
        $this->assertInstanceOf('NYPL\Refinery\ProviderTranslatorInterface', $translator);
    }
}
