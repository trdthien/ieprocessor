<?php

namespace Shopmacher\IEProcessor\Csv\Helper;


class ConvertBoolean implements ConverterInterface
{
    const PATTERN = '/^bool\((.*)\)$/';

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
     * @return bool
     */
    public function apply($value, $raw)
    {
        return boolval($value);
    }
}
