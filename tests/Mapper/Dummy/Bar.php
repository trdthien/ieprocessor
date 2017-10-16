<?php

namespace Shopmacher\IEProcessor\Test\Mapper\Dummy;

/**
 * Class Bar
 * @package Shopmacher\IEProcessor\Test
 */
class Bar
{
    /**
     * @var string $id
     */
    private $id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}
