<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\NDOFilter;

class NDOFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $id = 100;
        $ndoFilter = new NDOFilter($id);

        $this->assertSame($id, $ndoFilter->getFilterID());
    }

    public function testGettersAndSetters()
    {
        $ndoFilter = new NDOFilter();

        $testMultiException = new TestMultiException();

        $testMultiException->expectException(new RefineryException());
        try {
            $start = null;
            $ndoFilter->setStart($start);
            $this->assertSame(0, $ndoFilter->getStart());
        } catch (\Exception $exception) {
            $testMultiException->clearException($exception);
        }

        $testMultiException->expectException(new RefineryException());
        try {
            $start = -1;
            $ndoFilter->setStart($start);
        } catch (\Exception $exception) {
            $testMultiException->clearException($exception);
        }

        $start = 10;
        $ndoFilter->setStart($start);
        $this->assertSame($start, $ndoFilter->getStart());
    }

    public function testSetGetPage()
    {
        $ndoFilter = new NDOFilter();

        $ndoFilter->setPage(0);
        $page = $ndoFilter->getPage();

        $this->assertSame(0, $page);
    }

    public function testSetGetPerPage()
    {
        $ndoFilter = new NDOFilter();

        $ndoFilter->setPerPage(0);
        $perPage = $ndoFilter->getPerPage();

        $this->assertSame(0, $perPage);
    }

    public function testQueryParameters()
    {
        $ndoFilter = new NDOFilter();

        $ndoFilter->addQueryParameter('title', 'Node Title');
        $isNull = $ndoFilter->getQueryParameter('body');
        $isObject = $ndoFilter->getQueryParameter('title');
        $isQParam = $ndoFilter->getQueryParameterArray();

        $this->assertSame(null, $isNull);
        $this->assertInstanceOf('\\NYPL\\Refinery\\QueryParameter', $isObject);
        $this->assertInternalType('array', $isQParam);
    }

    public function testFilterArray()
    {
        $ndoFilter = new NDOFilter();

        $ndoFilter->addFilter('bundle', 'blog');
        $isNull = $ndoFilter->getFilterName('field');
        $filter = $ndoFilter->getFilterName('bundle');
        $isFilter = $ndoFilter->getFilterArray();

        $this->assertSame(null, $isNull);
        $this->assertSame('blog', $filter);
        $this->assertInternalType('array', $isFilter);
    }

    public function testIncludeArray()
    {
        $ndoFilter = new NDOFilter();

        $ndoFilter->addInclude('divisions');
        $include = $ndoFilter->getIncludeArray();
        $isFalse = $ndoFilter->checkInclude('date_range');
        $isTrue = $ndoFilter->checkInclude('divisions');

        $this->assertInternalType('array', $include);
        $this->assertTrue($isTrue);
        $this->assertFalse($isFalse);
    }
}
