<?php

namespace Shopmacher\IEProcessor\Model;

use Psr\Log\LoggerInterface;

/**
 * Trait TraitLogger
 * @package Shopmacher\IEProcessor\Model
 */
trait TraitLogger
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return LoggerInterface
     * @throws \Exception
     */
    public function getLogger()
    {
        if ($this->logger) {
            return $this->logger;
        }

        throw new \Exception('logger is not available');
    }
}
