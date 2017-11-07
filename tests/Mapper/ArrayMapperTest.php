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
                        'baz' => 'fox'
                    ],
                    [
                        'baz' => 'jumped',
                    ]
                ],
                [
                    'foo' => [
                        'one' => [
                            'two' => '%baz%',
                        ],
                    ]
                ],
                [
                    [
                        'foo' => [
                            'one' => ['two' => 'fox']
                        ]
                    ],
                    [
                        'foo' => [
                            'one' => ['two' => 'jumped'],
                        ]
                    ]
                ]
            ],
            [
                [
                    [
                        'bar' => 'quick',
                        'baz' => 'fox'
                    ],
                    [
                        'bar' => 'jumped',
                        'baz' => 'over'
                    ],
                ],
                [
                    'foo' => [
                        'bar_' => '%bar%',
                        'baz_' => '%baz%'
                    ]
                ],
                [
                    [
                        'foo' => [
                            'bar_' => 'quick',
                            'baz_' => 'fox'
                        ]
                    ],
                    [
                        'foo' => [
                            'bar_' => 'jumped',
                            'baz_' => 'over'
                        ]
                    ]
                ]
            ],
            [
                [
                    [
                        'bar' => 'quick',
                        'baz' => 'fox'
                    ],
                    [
                        'bar' => 'jumped',
                        'baz' => 'over'
                    ],
                ],
                [
                    'foo' => '%bar%'
                ],
                [
                    [
                        'foo' => 'quick',
                    ],
                    [
                        'foo' => 'jumped',
                    ]
                ],
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
