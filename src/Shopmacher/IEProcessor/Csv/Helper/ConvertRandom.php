<?php

namespace Shopmacher\IEProcessor\Csv\Helper;

/**
 * Class ConvertRandom
 * @package Shopmacher\IEProcessor\Csv\Helper
 */
class ConvertRandom implements ConverterInterface
{
    const PATTERN = '/^random\(([0-9]+-[0-9]+)\)$/';

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
        $range = explode('-', $value);
        return rand($range[0], $range[1]);
    }
}
