<?php

namespace Shopmacher\IEProcessor\Test;

use PHPUnit\Framework\TestCase;
use Shopmacher\IEProcessor\Mapper\ArrayMapper;

/**
 * Class ArrayArrayMapperTest
 * @package Shopmacher\IEProcessor\Test
 */
class ArrayMapperTest extends TestCase
{
    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            [
                [
                    [
                        'bar' => 'quick',
                        'baz' => 'fox'
                    ],
                    [
                        'bar' => 'jumped',
                        'baz' => 'over'
                    ]
                ],
                [
                    'foo' => [
                        'bar' => null,
                        'baz' => null
                    ]
                ],
                [
                    [
                        'foo' => [
                            'bar' => 'quick',
                            'baz' => 'fox'
                        ]
                    ],
                    [
                        'foo' => [
                            'bar' => 'jumped',
                            'baz' => 'over'
                        ]
                    ]
                ]
            ],
            [
                [
                    [
                        'baz' => 'fox'
                    ],
                    [
                        'baz' => 'jumped',
                    ]
                ],
                [
                    'foo' => [
                        'bar' => [
                            'baz' => null
                        ],
                    ]
                ],
                [
                    [
                        'foo' => [
                            'bar' => ['baz' => 'fox']
                        ]
                    ],
                    [
                        'foo' => [
                            'bar' => ['baz' => 'jumped'],
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testMapping($data, $map, $expected)
    {
        $mapper = new ArrayMapper();
        $result = $mapper->mapping($data, $map);

        self::assertEquals($expected, $result);
    }
}
