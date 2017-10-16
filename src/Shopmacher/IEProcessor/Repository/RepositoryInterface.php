<?php

namespace Shopmacher\IEProcessor\Repository;

/**
 * Interface RepositoryInterface
 * @package Shopmacher\IEProcessor\Repository
 */
interface RepositoryInterface
{
    /**
     * @param array $collection
     * @return mixed
     */
    public function save($collection);

    public function load();
}
