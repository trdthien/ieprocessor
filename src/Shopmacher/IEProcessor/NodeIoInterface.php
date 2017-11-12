<?php

namespace Shopmacher\IEProcessor;

use Shopmacher\IEProcessor\Model\NodeCollection;

/**
 * Interface NodeIoInterface
 * @package Shopmacher\IEProcessor
 */
interface NodeIoInterface
{
    /**
     * @param mixed $source
     * @return mixed
     */
    public function read($source = null);

    /**
     * @param NodeCollection $nodes
     * @param $target
     * @return mixed
     */
    public function write(NodeCollection $nodes, $target = null);
}
