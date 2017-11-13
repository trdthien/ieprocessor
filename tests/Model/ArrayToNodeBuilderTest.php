<?php

namespace Shopmacher\IEProcessor\Test;

use PHPUnit\Framework\TestCase;
use Shopmacher\IEProcessor\Model\ArrayToNodeBuilder;
use Shopmacher\IEProcessor\Model\NodeCollection;

/**
 * Class ArrayToNodeTest
 * @package Shopmacher\IEProcessor\Test
 */
class ArrayToNodeBuilderTest extends TestCase
{
    public function testArrayToNodeReturnNodeAsSimpleValue()
    {
        $data = [
            'foo' => 'bar'
        ];

        $nodes = ArrayToNodeBuilder::build($data);

        self::assertInstanceOf(NodeCollection::class, $nodes);
        self::assertEquals($data, $nodes->toArray());
    }

    public function testArrayToNodeWithDeepArray()
    {
        $data = [
            'foo' => [
                'bar' => '_bar',
                'baz' => [
                    'baz1' => '_baz1',
                    'baz2' => '_baz2',
                ]
            ]
        ];

        $node = ArrayToNodeBuilder::build($data);

        self::assertInstanceOf(NodeCollection::class, $node);
        self::assertEquals($data, $node->toArray());
    }
}
