<?php

namespace Shopmacher\IEProcessor\Mapper;

use Shopmacher\IEProcessor\MapperConfig;

/**
 * Interface MapperConfigLoaderInterface
 * @package Shopmacher\Base\IEProcessor\Mapper
 */
interface MapperConfigLoaderInterface
{
    /**
     * @param $file
     */
    public function load($file);

    /**
     * @return MapperConfig
     */
    public function getMap();
}
