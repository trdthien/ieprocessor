<?php

namespace Shopmacher\IEProcessor\Csv\Helper;

/**
 * Class ConvertString
 * @package Shopmacher\IEProcessor\Csv\Helper
 */
class ConvertNullToZeroString implements ConverterInterface
{
    const PATTERN = '/^null_to_zero\((.*)\)$/';

    /**
     * @inheritdoc
     */
    public function matching(&$key)
    {
        if (preg_match(self::PATTERN, $key)) {
            $key = preg_replace(self::PATTERN, '$1', $key);
            return true;
        }

        return false;
    }

    /**
     * @param mixed $value
     * @param array $raw
     * @return int
     */
    public function apply($value, $raw)
    {
        return (string) (float) $value;
    }
}
