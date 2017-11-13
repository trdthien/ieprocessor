<?php

namespace Shopmacher\IEProcessor\Test;

use PHPUnit\Framework\TestCase;
use Shopmacher\IEProcessor\Model\Node;
use Shopmacher\IEProcessor\Model\NodeCollection;
use Shopmacher\IEProcessor\Model\NodeToFlatArrayBuilder;

/**
 * Class NodeToFlatArrayBuilderTest
 * @package Shopmacher\IEProcessor\Test
 */
class NodeToFlatArrayBuilderTest extends TestCase
{
    public function testBuilderWithASimpleNode()
    {
        $node = Node::ofKeyAndValue('foo', 'bar');
        $actual = NodeToFlatArrayBuilder::build($node);

        self::assertEquals(['foo' => 'bar'], $actual);
    }

    public function testBuilderWithACollectionOfSimpleNodes()
    {
        $nodes = new NodeCollection();
        $nodes->add(
            Node::ofKeyAndValue('foo', '_foo')
        )->add(
            Node::ofKeyAndValue('bar', '_bar')
        );

        $actual = NodeToFlatArrayBuilder::build($nodes);

        self::assertEquals(
            [
                'foo' => '_foo',
                'bar' => '_bar'
            ],
            $actual
        );
    }

    public function testBuilderWithANodeHasAChildrenAsCollectionOfSimpleNode()
    {
        $node = new Node('foo');

        $node->addChildren(Node::ofKeyAndValue('bar', '_bar'));
        $node->addChildren(Node::ofKeyAndValue('baz', '_baz'));

        $actual = NodeToFlatArrayBuilder::build($node);

        self::assertEquals(
            [
                'foo.bar' => '_bar',
                'foo.baz' => '_baz'
            ],
            $actual
        );
    }

    public function testBuilderWithANodeWithChildrenAsComplexCollection()
    {
        $node = new Node('foo');

        $node->addChildren(Node::ofKeyAndValue('bar', '_bar'));
        $node->addChildren(
            Node::of('baz')
                ->addChildren(
                    Node::ofKeyAndValue('baz1', '_baz1')
                )
                ->addChildren(
                    Node::of('baz2')
                        ->addChildren(
                            Node::ofKeyAndValue('baz21', '_baz21')
                        )
                        ->addChildren(
                            Node::ofKeyAndValue('baz22', '_baz22')
                        )
                )
        );

        $actual = NodeToFlatArrayBuilder::build($node);

        self::assertEquals(
            [
                'foo.bar' => '_bar',
                'foo.baz.baz1' => '_baz1',
                'foo.baz.baz2.baz21' => '_baz21',
                'foo.baz.baz2.baz22' => '_baz22'
            ],
            $actual
        );
    }
}
