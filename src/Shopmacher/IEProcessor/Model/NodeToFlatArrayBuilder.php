<?php

namespace Shopmacher\IEProcessor\Model;

/**
 * Class NodeToFlatArrayBuilder
 * @package Shopmacher\IEProcessor\Model
 */
class NodeToFlatArrayBuilder
{
    /**
     * @param $nodes
     * @return array
     */
    public static function build($nodes, $rootKey = null)
    {
        $result = [];

        if ($nodes instanceof NodeCollection) {
            $merged = [];
            foreach ($nodes->toList() as $node) {
                $r = NodeToFlatArrayBuilder::build($node, $rootKey);
                $merged = array_merge($merged, reset($r));
            }
            $result[] = $merged;
        } elseif ($nodes instanceof Node) {
            $node = $nodes;
            if ($node->getChildren() instanceof NodeCollection) {
                $rootKey = $rootKey ? $rootKey . '.' . $node->getKey() : $node->getKey();
                $childNode = $node->getChildren();
                $r = NodeToFlatArrayBuilder::build($childNode, $rootKey);
                return $result[] = $r;
            } else {
                $key = $rootKey ?  $rootKey. '.' . $node->getKey() : $node->getKey();
                $result[] = [
                    $key => $node->getChildren()
                ];
            }
        } else {
            throw new \InvalidArgumentException('the param must be instance of Node or NodeCollection');
        }

        return $result;
    }
}
