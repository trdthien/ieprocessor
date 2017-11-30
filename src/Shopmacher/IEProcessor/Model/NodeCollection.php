<?php

namespace Shopmacher\IEProcessor\Model;

/**
 * Class NodeCollection
 * @package Shopmacher\IEProcessor\Model
 */
class NodeCollection implements TransformArrayAbleInterface
{
    /**
     * @var array
     */
    private $indexes;

    /**
     * @var Node[]|string
     */
    private $nodes;

    /**
     * @return array
     */
    public function toArray()
    {
        $nodes = [];
        foreach ($this->nodes as $node) {
            if ($node instanceof Node) {
                if ($node->getChildren() instanceof NodeCollection) {
                    $children = $node->getChildren()->toArray();
                } else {
                    $children = $node->getChildren();
                }
                if (isset($nodes[$node->getKey()])) {
                    $nodes[] = $children;
                } else {
                    $nodes[$node->getKey()] = $children;
                }
            } else {
                $nodes[] = $node;
            }
        }

        return $nodes;
    }

    /**
     * @return Node[]|string
     */
    public function toList()
    {
        return $this->nodes;
    }

    /**
     * @param Node $n
     * @return $this
     */
    public function add(Node $n)
    {
        if (!$node = $this->findNode($n)) {
            $this->pushNode($n);
            return $this;
        }

        if ($node->getChildren() instanceof NodeCollection && $n->getChildren() instanceof NodeCollection) {
            foreach ($n->getChildren()->toList() as $cNode) {
                $node->addChildren($cNode);
            }
            return $this;
        } else {
            // TODO: replace or merge?
            return $this;
        }
    }

    /**
     * @param Node $node
     * @return null|Node
     */
    public function findNode(Node $node)
    {
        $nodeIndex = NodeIdentifier::create($node->getKey(), $node->getNId());

        if (isset($this->indexes[$nodeIndex])) {
            $index = $this->indexes[$nodeIndex];
            return $this->nodes[$index];
        }

        return null;
    }

    /**
     * @param Node $node
     * @return $this
     */
    private function pushNode(Node $node)
    {
        if (!empty($this->nodes) && is_int($node->getKey())
            && reset($this->nodes)->getKey() === $node->getKey()) {
            $node->setKey(count($this->nodes));
        }
        $this->nodes[] = $node;
        $index = NodeIdentifier::create($node->getKey(), $node->getNId());
        $this->indexes[$index] = count($this->nodes) - 1;

        return $this;
    }
}
