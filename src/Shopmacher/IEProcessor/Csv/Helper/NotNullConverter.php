<?php

namespace Shopmacher\IEProcessor\Csv\Helper;

/**
 * Class NotNullConverter
 * @package Shopmacher\IEProcessor\Csv\Helper
 */
class NotNullConverter implements ConverterInterface
{
    const PATTERN = '/^not_null\((.*)\)$/';

    /**
     * @param string $key
     * @return bool|mixed
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
     * @return mixed
     */
    public function apply($value, $raw)
    {
        return $value;
    }
}
