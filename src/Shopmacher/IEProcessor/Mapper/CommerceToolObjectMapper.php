<?php

namespace Shopmacher\IEProcessor\Mapper;

use Shopmacher\IEProcessor\MapperConfig;

/**
 * Class CommerceToolObjectMapper
 * @package Shopmacher\IEProcessor\Mapper
 */
class CommerceToolObjectMapper implements MapperInterface
{
    /**
     * @var MapperConfig
     */
    private $map;

    /**
     * CommerceToolObjectMapper constructor.
     * @param ArrayMapper $arrayMapper
     * @param MapperConfig|null $map
     */
    public function __construct(ArrayMapper $arrayMapper, MapperConfig $map = null)
    {
        $this->map = $map;
    }

    /**
     * @param $map
     * @param $data
     * @return mixed
     */
    public function mapping($data, $map = null)
    {
        $result = [];

        if (!$map && !$this->map) {
            return $data;
        }

        $arrMap = $map ? $map : $this->map->toArray();

        foreach ((array) $arrMap as $class => $map) {
            if (is_array($map)) {
                $result[$class] = forward_static_call_array([$class, 'fromArray'], [$data]);
            } else {
                $result[$map] = forward_static_call_array([$map, 'fromArray'], [$data]);
            }
        }

        return $result;
    }
}
