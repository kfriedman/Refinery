<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\Serializer;
use NYPL\Refinery\Config\Config;

class SerializerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!defined('CONFIG_FILE_DIRECTORY')) {
            define('CONFIG_FILE_DIRECTORY', __DIR__ . '/../../config/app');
        }
        if (!defined('ENVIRONMENT_VARIABLE_NAME')) {
            define('ENVIRONMENT_VARIABLE_NAME', 'SERVER_ENV');
        }

        Config::initialize(new \Configula\Config(CONFIG_FILE_DIRECTORY), getenv(ENVIRONMENT_VARIABLE_NAME));
    }

    public function testBSONEncoding()
    {
        $array = array('key' => 'value');

        $bson = Serializer::encode($array);

        $this->assertSame("   key    value  ", $bson);
        $this->assertSame(array('key' => 'value'), Serializer::decode($bson));
    }

    public function testJSONEncoding()
    {
        $array = array('key' => 'value');

        $json = Serializer::encode($array, 0);

        $this->assertSame('{"key":"value"}', $json);
        $this->assertSame(array('key' => 'value'), Serializer::decode($json, 0));
    }
}
?>