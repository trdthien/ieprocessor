<?php

namespace Shopmacher\IEProcessor\Csv;

use Shopmacher\IEProcessor\Csv\Helper\StackConverters;
use Shopmacher\IEProcessor\Model\Node;

/**
 * Class FlatArrayToNodeBuilder
 * @package Shopmacher\IEProcessor\Csv
 */
class FlatArrayToNodeBuilder
{
    /**
     * @param array $raw
     * @param array $mapping
     * @return null|Node
     */
    public static function build($raw = [], $mapping = [])
    {
        $node = new Node('root');
        $stackConverter = StackConverters::instance();

        foreach ($mapping as $key => $childrenMap) {
            $node->setKey($key);
            if (is_array($childrenMap)) {
                foreach ($childrenMap as $cKey => $map) {
                    if (is_string($map)) {
                        $value = $stackConverter::execute($map, $raw);
                        if (is_null($value)) {
                            return null;
                        }
                        if ($cKey === Node::IDENTIFIER) {
                            $node->setNId($value);
                            continue;
                        }
                        if ($cKey === Node::KEY_NODE) {
                            $node->setKey($value);
                            continue;
                        }
                    }
                    $cNode = FlatArrayToNodeBuilder::build($raw, [$cKey => $map]);
                    if ($cNode) {
                        $node->addChildren($cNode);
                    }
                }
            } else {
                // is the ending node
                $node->addChildren($stackConverter::execute($childrenMap, $raw));
            }
        }

        return $node;
    }
}
