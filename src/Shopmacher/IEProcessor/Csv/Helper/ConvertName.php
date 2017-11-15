<?php

namespace Shopmacher\IEProcessor\Csv\Helper;

/**
 * Class ConvertName
 * @package Shopmacher\IEProcessor\Csv\Helper
 */
class ConvertName implements ConverterInterface
{
    const PATTERN = '/product_name\(var=(.*)\)$/';

    /**
     * @param string $key
     * @return bool
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
     */
    public function apply($value, $raw)
    {
        preg_match_all('/%([^%-]+)%/', $value, $matched);
        if ($vars = $matched[1]) {
            $name = isset($raw[$vars[0]]) ? $raw[$vars[0]]: $vars[0];
            $segment = isset($raw[$vars[1]]) ? $raw[$vars[1]] : $vars[1];
            $series = isset($raw[$vars[2]]) ? $raw[$vars[2]] : $vars[2];
            $size = isset($raw[$vars[3]]) ? $raw[$vars[3]] : $vars[3];
            $specVolume = !empty($raw[$vars[4]]) ? $raw[$vars[4]] : '';
            return sprintf(
                "%s %s %s %s %s",
                $name,
                $segment,
                $series,
                $size,
                $specVolume
            );
        }
        return $value;
    }

    private function clean($str)
    {
        $str = preg_replace('/\s+/', '-', $str);
        // Removes special chars.
        return strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', $str));
    }
}
