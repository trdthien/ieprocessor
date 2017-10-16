<?php

namespace Shopmacher\IEProcessor;

/**
 * Class MapperConfig
 * @package Shopmacher\IEProcessor
 */
class MapperConfig
{
    /**
     * @var array
     */
    private $map;

    /**
     * MapperConfig constructor.
     * @param array $configuration
     */
    public function __construct($configuration = [])
    {
        $this->map = $configuration;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->map;
    }
}
