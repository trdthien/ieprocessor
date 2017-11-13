<?php

namespace Shopmacher\IEProcessor\Csv\Helper;

/**
 * Interface ConverterInterface
 * @package Shopmacher\IEProcessor\Csv\Helper
 */
interface ConverterInterface
{
    /**
     * @param string $key
     * @return string
     */
    public function matching(&$key);

    /**
     * @param mixed $value
     * @param array $raw
     * @return mixed
     */
    public function apply($value, $raw);
}
