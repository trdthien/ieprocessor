<?php

namespace Shopmacher\IEProcessor\Test;

use PHPUnit\Framework\TestCase;
use Shopmacher\IEProcessor\Mapper\YmlMapperConfigLoader;

/**
 * Class YmlMapperConfigLoaderTest
 * @package Shopmacher\IEProcessor\Test
 */
class YmlMapperConfigLoaderTest extends TestCase
{
    public function testLoadConfig()
    {
        $loader = new YmlMapperConfigLoader(__DIR__ . DIRECTORY_SEPARATOR . 'config-mapper.yml');
        $map = $loader->getMap();

        self::assertEquals([
            'product' => [
                'name' => 'name_',
                'slug' => 'slug_',
                'description' => [
                    'en' => 'foo',
                    'de' => 'bar'
                ]
            ],
            'variant' => [
                'sku' => '1A92',
                'images' => [
                    'image' => [
                        'url' => 'http://thumb.com'
                    ]
                ]
            ]
        ], $map->toArray());
    }
}
