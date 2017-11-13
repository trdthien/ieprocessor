<?php

namespace Shopmacher\IEProcessor\Test\Csv;

use PHPUnit\Framework\TestCase;
use Shopmacher\IEProcessor\Csv\Helper\ConverterInterface;
use Shopmacher\IEProcessor\Csv\Helper\StackConverters;

/**
 * Class StackConvertersTest
 * @package Shopmacher\IEProcessor\Test\Csv
 */
class StackConvertersTest extends TestCase
{
    public function testStackConverterReturnKeyAsRawDoesNotHaveThatKey()
    {
        $stackConverters = StackConverters::instance();
        $actual = $stackConverters::execute($expected = 'foo', ['bar' => 'baz']);
        self::assertEquals($expected, $actual);
    }

    public function testStackConverterReturnAnAccordingValueToTheKeyInRaw()
    {
        $stackConverters = StackConverters::instance();
        $actual = $stackConverters::execute('%bar%', ['bar' => $expected = 'baz']);
        self::assertEquals($expected, $actual);
    }
}
