<?php

namespace Shopmacher\IEProcessor\Mapper;

use Shopmacher\IEProcessor\MapperConfig;

/**
 * Class ArrayArrayMapper
 * @package Shopmacher\IEProcessor\Mapper
 */
class ArrayMapper implements MapperInterface
{
    /**
     * @var MapperConfig
     */
    private $map;

    /**
     * ArrayArrayMapper constructor.
     * @param MapperConfig $map
     */
    public function __construct(MapperConfig $map = null)
    {
        $this->map = $map;
    }

    /**
     * @inheritdoc
     */
    public function mapping($data, $map = null)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException('parameters must be array');
        }

        if (!$map && !$this->map) {
            return $data;
        }

        $map = $map? $map : $this->map->toArray();

        $result = [];

        foreach ($data as $i => $row) {
            foreach ($map as $key => $value) {
                if (is_array($value)) {
                    $mappedValue = $this->mapping([$row], $value)[0];
                } elseif (preg_match('/%(.*)%/', $value)) {
                    $value = str_replace('%', '', $value);
                    $mappedValue = $row[$value];
                } else {
                    $mappedValue = $value;
                }
                $result[$i][$key] = $mappedValue;
            }
        }

        return $result;
    }
}
