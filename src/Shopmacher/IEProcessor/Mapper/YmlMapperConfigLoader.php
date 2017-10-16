<?php

namespace Shopmacher\IEProcessor\Mapper;

use Shopmacher\IEProcessor\MapperConfig;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YmlMapperConfigLoader
 * @package Shopmacher\Base\IEProcessor\Mapper
 */
class YmlMapperConfigLoader implements MapperConfigLoaderInterface
{
    /**
     * @var MapperConfig
     */
    private $map;

    /**
     * YmlMapperConfigLoader constructor.
     * @param string $file
     */
    public function __construct($file = null)
    {
        $this->map = new MapperConfig(
            Yaml::parse(file_get_contents($file))
        );
    }

    /**
     * @param $file
     * @return MapperConfig
     */
    public function load($file)
    {
        return $map = new MapperConfig(
            Yaml::parse(file_get_contents($file))
        );
    }

    /**
     * @return MapperConfig
     */
    public function getMap()
    {
        return $this->map;
    }
}
