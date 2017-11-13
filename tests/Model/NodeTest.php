<?php

namespace Shopmacher\IEProcessor\Test;

use PHPUnit\Framework\TestCase;
use Shopmacher\IEProcessor\Model\Node;
use Shopmacher\IEProcessor\Model\NodeCollection;
use Shopmacher\IEProcessor\Model\TransformArrayAbleInterface;

/**
 * Class NodeTest
 * @package Shopmacher\IEProcessor\Test
 */
class NodeTest extends TestCase
{
    public function testConstructNodeFromSimpleKeyValueArray()
    {
        $node = Node::fromArray(
            [
                '_bar' => 'bar',
            ],
            [
                'foo' => '%_bar%'
            ]
        );

        self::assertEquals('foo', $node->getKey());
        self::assertEquals('%_bar%', $node->getChildren());
    }

    public function testConstructNodeFromTwoLevelArray()
    {
        $node = Node::fromArray(
            [
                '_bar' => 'bar',
            ],
            [
                'foo' => [
                    'boo' => '%_bar%'
                ]
            ]
        );

        self::assertEquals('foo', $node->getKey());
        self::assertInstanceOf(NodeCollection::class, $node->getChildren());
        self::assertInstanceOf(TransformArrayAbleInterface::class, $node->getChildren());
        self::assertEquals(
            [
                'foo' => [
                    'boo' => '%_bar%'
                ]
            ],
            $node->toArray()
        );
    }

    public function testConstructNodeWithManyChildNode()
    {
        $node = Node::fromArray(
            [
                [
                    '_bar' => 'bar',
                ]
            ],
            [
                'foo' => [
                    'boo' => [
                        'baz1' => [
                            'name' => '%_baz1%'
                        ],
                        'baz2' => [
                            'name' => '%_bar2%'
                        ]
                    ]
                ]
            ]
        );

        self::assertEquals('foo', $node->getKey());
        self::assertInstanceOf(NodeCollection::class, $node->getChildren());
        self::assertEquals(
            [
                'foo' => [
                    'boo' => [
                        'baz1' => [
                            'name' => '%_baz1%'
                        ],
                        'baz2' => [
                            'name' => '%_bar2%'
                        ]
                    ]
                ]
            ],
            $node->toArray()
        );
    }

    public function nodeCompareData()
    {
        return [
            // case 1
            'Same key, different id' => [
                // node
                [
                    'id' => '1',
                    'key' => '1'
                ],
                // compare node
                [
                    'id' => '2',
                    'key' => '1'
                ],
                false
            ],
            'Same key, same id' => [
                // node
                [
                    'id' => '1',
                    'key' => '1'
                ],
                // compare node
                [
                    'id' => '1',
                    'key' => '1'
                ],
                true
            ],
            'Same key, same null id' => [
                // node
                [
                    'id' => null,
                    'key' => '1'
                ],
                // compare node
                [
                    'id' => null,
                    'key' => '1'
                ],
                true
            ],
            'Different key, same id' => [
                // node
                [
                    'id' => '1',
                    'key' => '1'
                ],
                // compare node
                [
                    'id' => '1',
                    'key' => '2'
                ],
                false
            ],
            'Different key, different id' => [
                // node
                [
                    'id' => '1',
                    'key' => '2'
                ],
                // compare node
                [
                    'id' => '3',
                    'key' => '4'
                ],
                false
            ]
        ];
    }

    /**
     * @dataProvider nodeCompareData
     */
    public function testTwoNodeIsUnEqualWhenTheyHaveDifferentId($a, $b, $expected)
    {
        $node = Node::ofKeyAndId($a['key'], $a['id']);
        $anotherNode = Node::ofKeyAndId($b['key'], $b['id']);
        self::assertEquals($expected, $node->isSame($anotherNode));
    }
}
