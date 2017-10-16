<?php

namespace Shopmacher\IEProcessor\Test;

use PHPUnit\Framework\TestCase;
use Shopmacher\IEProcessor\Mapper\ObjectMapper;
use Shopmacher\IEProcessor\Test\Mapper\Dummy\Foo;

/**
 * Class ArrayObjectMapperTest
 * @package Shopmacher\IEProcessor\Test
 */
class ObjectMapperTest extends TestCase
{
    public function testMappingReturnObject()
    {
        $fooId = 'foo-id';
        $barId = 'bar-id';
        $barName = 'bar-name';

        $mapper = new ObjectMapper();
        $collection = $mapper->mapping([
            [
                'id' => $fooId,
                'bar' => [
                    'id' => $barId,
                    'name' => $barName
                ],
                'bars' => [
                    [
                        'id' => $barId,
                        'name' => $barName
                    ],
                    [
                        'id' => 'bar-id-2',
                        'name' => 'bar-name-2'
                    ]
                ]
            ]
        ], Foo::class);

        $object = $collection[0];
        self::assertInstanceOf(Foo::class, $object);
        self::assertEquals($fooId, $object->getId());
        self::assertEquals($barId, $object->getBar()->getId());
        self::assertEquals(2, count($object->getBars()));
        self::assertEquals('bar-id-2', $object->getBars()[1]->getId());
    }
}
