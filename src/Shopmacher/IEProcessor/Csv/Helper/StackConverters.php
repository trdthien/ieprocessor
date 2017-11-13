<?php

namespace Shopmacher\IEProcessor\Csv\Helper;

/**
 * Class StackConverters
 * @package Shopmacher\IEProcessor\Csv\Helper
 */
class StackConverters
{
    private static $instance;
    /**
     * @var ConverterInterface[]
     */
    private static $converters;

    /**
     * @return StackConverters
     */
    public static function instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
            self::register(new DefaultConverter());
        }

        return self::$instance;
    }

    /**
     * @param ConverterInterface $converter
     */
    public static function register(ConverterInterface $converter)
    {
        if (!isset(self::$instance)) {
            self::instance();
        }

        self::$converters[] = $converter;
    }

    /**
     * @param $key
     * @param $raw
     * @return mixed
     */
    public static function execute($key, $raw)
    {
        $value = $key;

        if (!empty(self::$converters)) {
            foreach (self::$converters as $converter) {
                if ($converter->matching($key)) {
                    $value = self::execute($key, $raw);
                    return $converter->apply($value, $raw);
                }
            }
        }

        return $value;
    }
}
