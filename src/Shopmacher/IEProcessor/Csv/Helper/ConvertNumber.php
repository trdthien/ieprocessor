<?php

namespace Shopmacher\IEProcessor\Csv\Helper;

/**
 * Class ConvertNumber
 * @package Shopmacher\IEProcessor\Csv\Helper
 */
class ConvertNumber implements ConverterInterface
{
    const PATTERN = '/^number\(%([^%\(\)]+)%\)$/';

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
        return (int) $value;
    }
}
