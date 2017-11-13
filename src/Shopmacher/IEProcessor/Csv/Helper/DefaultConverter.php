<?php

namespace Shopmacher\IEProcessor\Csv\Helper;

/**
 * Class DefaultConverter
 * @package Shopmacher\IEProcessor\Csv\Helper
 */
class DefaultConverter implements ConverterInterface
{
    const PATTERN = '/^%(.*)%$/';

    public function matching(&$key)
    {
        if (preg_match(self::PATTERN, $key)) {
            $key = preg_replace(self::PATTERN, '$1', $key);
            return true;
        }

        return false;
    }

    /**
     * @param mixed $key
     * @param array $raw
     * @return mixed
     */
    public function apply($key, $raw)
    {
        if (isset($raw[$key])) {
            return $raw[$key];
        }
        return $key;
    }
}
