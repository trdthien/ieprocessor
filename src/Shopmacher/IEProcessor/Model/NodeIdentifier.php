<?php

namespace Shopmacher\IEProcessor\Model;

/**
 * Class NodeIdentifier
 * @package Shopmacher\IEProcessor\Model
 */
class NodeIdentifier
{
    /**
     * @param $key
     * @param null $id
     * @return string
     */
    public static function create($key, $id = null)
    {
        return md5(sprintf(
            '%s-%s',
            $key,
            print_r($id, true)
        ));
    }
}
