<?php

namespace Shopmacher\IEProcessor\Csv\Helper;

/**
 * Class ConvertSlug
 * @package Shopmacher\IEProcessor\Csv\Helper
 */
class ConvertSlug implements ConverterInterface
{
    const PATTERN = '/slug\(var=(.*)\)$/';

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
            $brand = isset($raw[$vars[0]]) ? $raw[$vars[0]] : $vars[0];
            $name = isset($raw[$vars[1]]) ? $raw[$vars[1]]: $vars[1];
            $segment = isset($raw[$vars[2]]) ? $raw[$vars[2]] : $vars[2];
            $series = isset($raw[$vars[3]]) ? $raw[$vars[3]] : $vars[3];
            $sku = isset($raw[$vars[4]]) ? $raw[$vars[4]] : $vars[4];
            $subCategory = isset($raw[$vars[5]]) ? $raw[$vars[5]] : $vars[5];
            return sprintf(
                "%s--%s-%s-%s--%s--%s",
                $this->clean($brand),
                $this->clean($name),
                $this->clean($segment),
                $this->clean($series),
                $sku,
                $subCategory
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
