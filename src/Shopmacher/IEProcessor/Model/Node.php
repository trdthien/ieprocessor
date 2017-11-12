<?php

namespace Shopmacher\IEProcessor\Model;

/**
 * Class Node
 * @package Shopmacher\IEProcessor\Model
 */
class Node implements TransformArrayAbleInterface
{
    /**
     * @var mixed
     */
    private $id;

    /**
     * @var mixed
     */
    private $key;

    /**
     * @var string
     */
    private $type;

    /**
     * @var mixed
     */
    private $children;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Node constructor.
     * @param $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @param $node
     * @return $this
     */
    public function addChildren($node)
    {
        if (!$this->children instanceof NodeCollection) {
            $this->children = new NodeCollection();
        }

        $this->children->add($node);

        return $this;
    }

    /**
     * @return NodeCollection|mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        if ($this->children instanceof TransformArrayAbleInterface) {
            return [
                $this->key => $this->children->toArray()
            ];
        }

        return [$this->children => $this->children];
    }

    /**
     * @param array $data
     * @param array $mapping
     * @return Node
     */
    public static function fromArray($data = [], $mapping = [])
    {
        $node = new self('root');
        foreach ($mapping as $key => $childrenMap) {
            $node->key = $key;
            if (is_array($childrenMap)) {
                foreach ($childrenMap as $cKey => $map) {
                    if ($cKey === 'id') {
                        $node->id = $data[$map];
                        continue;
                    }
                    if (is_string($map) && preg_match('/^not_null\(%(.*)%\)$/', $map)) {
                        $vKey = preg_replace('/^not_null\(%(.*)%\)$/', '$1', $map);
                        if (empty($data[$vKey])) {
                            return;
                        }

                    }
                    $cNode = Node::fromArray($data, [$cKey => $map]);
                    if ($cNode) {
                        $node->addChildren($cNode);
                    }
                }
            } else {
                if (preg_match('/^bool\(.*\)$/', $childrenMap)) {
                    $vKey = preg_replace('/^bool\(%(.*)%\)$/', '$1', $childrenMap);
                    $node->children = boolval($data[$vKey]);
                } elseif (preg_match('/^number\(.*\)$/', $childrenMap)) {
                    $vKey = preg_replace('/^number\(%(.*)%\)$/', '$1', $childrenMap);
                    $node->children = (int) $data[$vKey];
                } elseif (preg_match('/^not_null\(.*\)$/', $childrenMap)) {
                    $vKey = preg_replace('/^not_null\(%(.*)%\)$/', '$1', $childrenMap);
                    $node->children = $data[$vKey];
                } elseif (preg_match('/%.*%/', $childrenMap)) {
                    $vKey = preg_replace('/^%(.*)%$/', '$1', $childrenMap);
                    $node->children = $data[$vKey];
                } else {
                    $node->children = $childrenMap;
                }
            }
        }

        return $node;
    }

    /**
     * @param Node $node
     * @return bool
     */
    public function isSame(Node $node)
    {
        return $this->id === $node->id && $this->key === $node->key;
    }

    /**
     * @param $key
     * @return static
     */
    public static function of($key)
    {
        return new static($key);
    }

    /**
     * @param $key
     * @param $id
     * @return self
     */
    public static function ofKeyAndId($key, $id)
    {
        return (new static($key))->setId($id);
    }

    /**
     * @param $key
     * @param $value
     * @return static
     */
    public static function ofKeyAndValue($key, $value)
    {
        $node = (new static($key));
        $node->children = $value;
        return $node;
    }
}
