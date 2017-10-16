<?php

namespace Shopmacher\IEProcessor\Parser;

/**
 * Interface ParserInterface
 * @package Shopmacher\Base\IEProcessor\Parser
 */
interface ParserInterface
{
    /**
     * @param string $file
     * @return array
     */
    public function parse($file);

    /**
     * @param array $collection
     * @param $target
     * @return mixed
     */
    public function write($collection, $target);
}
