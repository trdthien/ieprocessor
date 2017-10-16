<?php

namespace Shopmacher\IEProcessor\Mapper;

/**
 * Class SupportType
 * @package Shopmacher\IEProcessor\Mapper
 */
class SupportType
{
    /**
     * Type ARRAY
     */
    const TYPE_ARRAY = 'array';
    /**
     * Type JSON
     */
    const TYPE_JSON = 'json';
    /**
     * Type Object
     */
    const TYPE_OBJECT = 'object';

    /**
     * @return array
     */
    public static function getSupports()
    {
        return [
            self::TYPE_ARRAY,
            self::TYPE_JSON,
            self::TYPE_OBJECT
        ];
    }

    /**
     * @param $type
     * @return bool
     */
    public static function checkSupport($type)
    {
        return in_array($type, self::getSupports());
    }
}
