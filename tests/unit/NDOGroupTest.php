<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\NDO\BasicPageGroup;

class NDOGroupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers NYPL\Refinery\NDOGroup::initializeItems
     */
    public function testItemsIsInitialized()
    {
        $ndo = \Mockery::mock('\NYPL\Refinery\NDO\Content\Page\BasicPage');
        $ndo->shouldReceive('getNdoID')->andReturn('ndo');
        $ndo->shouldReceive('getNdoType')->andReturn('BasicPage');

        $ndo2 = \Mockery::mock('\NYPL\Refinery\NDO\Content\Page\BasicPage');
        $ndo2->shouldReceive('getNdoID')->andReturn('ndo2');
        $ndo2->shouldReceive('getNdoType')->andReturn('BasicPage');

        $ndo3 = \Mockery::mock('\NYPL\Refinery\NDO\Content\Page\BasicPage');
        $ndo3->shouldReceive('getNdoID')->andReturn('ndo3');
        $ndo3->shouldReceive('getNdoType')->andReturn('BasicPage');

        $group = new BasicPageGroup(array($ndo, $ndo2));
        $group->append($ndo3);

        $this->assertInstanceOf('\\ArrayIterator', $group->items);
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testItemTypeMatches()
    {
        $ndo = \Mockery::mock('\NYPL\Refinery\NDO\Content\Page\BasicPage');
        $ndo->shouldReceive('getNdoType')->andReturn('NotMatching');

        $group = new BasicPageGroup();
        $group->append($ndo);
    }


    public function testItemAddedToItem()
    {
        $ndo = \Mockery::mock('\NYPL\Refinery\NDO\Content\Page\BasicPage');
        $ndo->shouldReceive('getNdoID')->andReturn('ndo');
        $ndo->shouldReceive('getNdoType')->andReturn('BasicPage');

        $group = new BasicPageGroup();
        $group->append($ndo);

        $this->assertSame($ndo, $group->items->current());
    }

    public function testSearchItemsByIndex()
    {
        $ndo = \Mockery::mock('\NYPL\Refinery\NDO\Content\Page\BasicPage')->shouldDeferMissing();
        $ndo->shouldReceive('getNdoType')->andReturn('BasicPage');
        $ndo->setTitle('Page Title');

        $group = new BasicPageGroup();
        $group->append($ndo);

        $matches = $group->searchItemsByIndex('title', 'Page Title');

        $this->assertInstanceOf('\\ArrayIterator', $matches);
        $this->assertNotEmpty($matches);
    }
}
