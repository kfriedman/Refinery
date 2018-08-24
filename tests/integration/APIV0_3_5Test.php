<?php
namespace NYPL\Refinery\Tests\Integration;

class APITestV0_3_5 extends APIBase
{
    public $version = '0.3.5';

    public function testAPI($usesIndex = false)
    {
         parent::testAPI(true);
    }
}
