<?php

namespace Shopmacher\IEProcessor\Model;

/**
 * Interface ArrayConstructAbleInterface
 * @package Shopmacher\IEProcessor\Model
 */
interface ArrayConstructAbleInterface
{
    public function fromArray($data, $mapping);
}
