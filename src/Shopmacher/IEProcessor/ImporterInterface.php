<?php

namespace Shopmacher\IEProcessor;

/**
 * Interface ImporterInterface
 * @package Shopmacher\IEProcessor
 */
interface ImporterInterface
{
    /**
     * @param $source
     * @return mixed
     */
    public function import($source);
}
