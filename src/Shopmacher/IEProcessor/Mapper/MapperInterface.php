<?php

namespace Shopmacher\IEProcessor\Mapper;

/**
 * Interface MapperInterface
 * @package Shopmacher\IEProcessor\Mapper
 */
interface MapperInterface
{
    /**
     * @param $data
     * @param $map
     * @return mixed
     */
    public function mapping($data, $map = null);
}
