<?php

namespace Shopmacher\IEProcessor\Test;

use PHPUnit\Framework\TestCase;
use Shopmacher\IEProcessor\Mapper\ArrayMapper;
use Shopmacher\IEProcessor\Mapper\CommerceToolObjectMapper;
use Shopmacher\IEProcessor\MapperConfig;
use Shopmacher\IEProcessor\Test\Mapper\Dummy\CommerceToolsObjectDraft;

/**
 * Class CommerceToolsObjectMapperTest
 * @package Shopmacher\IEProcessor\Test
 */
class CommerceToolsObjectMapperTest extends TestCase
{
    public function testMappingReturnSameDataIfNoConfigMaps()
    {
        $mapper = new CommerceToolObjectMapper(new ArrayMapper());
        $result = $mapper->mapping(['bla']);
        self::assertEquals(['bla'], $result);
    }

    public function testMappingWithInlineMap()
    {
        $mapper = new CommerceToolObjectMapper(new ArrayMapper());
        $result = $mapper->mapping([], CommerceToolsObjectDraft::class);
        self::assertInstanceOf(CommerceToolsObjectDraft::class, $result[CommerceToolsObjectDraft::class]);
    }

    public function testMappingWithConfigMap()
    {
        $map = new MapperConfig([
            CommerceToolsObjectDraft::class => [],
        ]);
        $mapper = new CommerceToolObjectMapper(new ArrayMapper(), $map);
        $result = $mapper->mapping([]);
        self::assertEquals(1, count($result));
        self::assertInstanceOf(CommerceToolsObjectDraft::class, $result[CommerceToolsObjectDraft::class]);
    }
}
