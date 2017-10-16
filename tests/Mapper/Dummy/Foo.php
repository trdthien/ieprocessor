<?php

namespace Shopmacher\IEProcessor\Test\Mapper\Dummy;

/**
 * Class Foo
 * @package Shopmacher\IEProcessor\Test
 */
class Foo
{
    /**
     * @var string $id
     */
    private $id;

    /**
     * @var Bar $bar
     */
    private $bar;

    /**
     * @var Bar[] $bars
     */
    private $bars;

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
     * @return mixed
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @param Bar $bar
     */
    public function setBar(Bar $bar)
    {
        $this->bar = $bar;
    }

    /**
     * @param array $bars
     */
    public function setBars($bars = [])
    {
        $this->bars = $bars;
    }

    /**
     * @return Bar[]
     */
    public function getBars()
    {
        return $this->bars;
    }
}
