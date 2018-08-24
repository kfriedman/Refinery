<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\NDO\Content\Page\BasicPage;

class NDOTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testSettingPropertyThatExists()
    {
        $ndo = new BasicPage();
        $ndo->property_does_not_exist = null;
    }
}
