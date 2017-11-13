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
        if (!$node instanceof Node) {
            $this->children = $node;
            return $this;
        }

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
