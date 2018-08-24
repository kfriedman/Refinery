<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\QueryParameter;

class QueryParameterTest extends \PHPUnit_Framework_TestCase
{
    public function testIfQueryStringNotEqual()
    {
        $queryParam = new QueryParameter();
        // Set operator
        $queryParam->setOperator('!=');
        // Set value
        $queryParam->setValue('ramses');

        $comparison = $queryParam->getValueWithOperator();

        $this->assertSame('not:ramses', $comparison);
    }

    public function testIfQueryStringEqual()
    {
        $queryParam = new QueryParameter();
        // Set operator
        $queryParam->setOperator('=');
        // Set value
        $queryParam->setValue('ramses');

        $comparison = $queryParam->getValueWithOperator();

        $this->assertSame('ramses', $comparison);
    }

    /**
     * @expectedException \NYPL\Refinery\Exception\RefineryException
     */
    public function testBadOperator()
    {
        $queryParam = new QueryParameter();
        // Set operator
        $queryParam->setOperator('<>');
        // Set value
        $queryParam->setValue('ramses');

        $queryParam->getValueWithOperator();
    }
}