<?php

namespace Shopmacher\IEProcessor\Csv\Helper;

/**
 * Class ConvertCentAmount
 * @package Shopmacher\IEProcessor\Csv\Helper
 */
class ConvertCentAmount implements ConverterInterface
{
    const PATTERN = '/^cent\((.*)\)$/';

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
        $value = (float) $value;
        return (int) (string) $value * 100;
    }
}
