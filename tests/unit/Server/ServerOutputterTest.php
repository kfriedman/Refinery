<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\Server;
use NYPL\Refinery\Server\ServerOutputter;

class ServerOutputterTest
{
    protected $server;

    public function setUp()
    {
        $this->server = new Server();
    }

    public function testIfJSONIsOutput()
    {
        $array = array('key' => 'value');
        $json = ServerOutputter::outputAsJSON($array);

        $this->assertSame('{ "key": "value" }', $json);
    }
}
?>