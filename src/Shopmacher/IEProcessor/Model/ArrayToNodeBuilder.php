<?php

namespace Shopmacher\IEProcessor\Model;

/**
 * Class ArrayToNodeBuilder
 * @package Shopmacher\IEProcessor\Model
 */
class ArrayToNodeBuilder
{
    public static function build($data, $key = null)
    {
        if (!is_array($data)) {
            return Node::of(isset($key) ? $key : $data);
        }

        $nodes = new NodeCollection();

        if (isset($key)) {
            $node = Node::of($key);
        }

        foreach ($data as $cKey => $nodeData) {
            if (!is_array($nodeData)) {
                $childNode = Node::ofKeyAndValue($cKey, $nodeData);
            } else {
                $childNode = ArrayToNodeBuilder::build($nodeData, $cKey);
            }

            if (!$childNode) {
                continue;
            }

            if (isset($node)) {
                if ($cKey == 'id') {
                    $node->setId($nodeData);
                }
                $node->addChildren($childNode);
            } else {
                $nodes->add($childNode);
            }
        }

        return isset($node) ? $node : $nodes;
    }
}
