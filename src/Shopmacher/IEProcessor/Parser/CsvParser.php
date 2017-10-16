<?php

namespace Shopmacher\IEProcessor\Parser;

use Shopmacher\IEProcessor\Mapper\MapperInterface;

/**
 * Class CsvParser
 * @package Shopmacher\Base\IEProcessor\Parser
 */
class CsvParser implements ParserInterface
{
    /**
     * @var MapperInterface[]
     */
    private $mappers;

    /**
     * CsvParser constructor.
     * @param array $mappers
     */
    public function __construct(array $mappers = [])
    {
        $this->mappers = $mappers;
    }

    /**
     * @inheritdoc
     */
    public function parse($file)
    {
        $parser = new \parseCSV();
        $parser->parse($file);

        $collection = $parser->data;

        foreach ($this->mappers as $mapper) {
            $collection = $mapper->mapping($collection);
        }

        return $collection;
    }

    /**
     * @inheritdoc
     */
    public function write($collection, $target)
    {
        $parser = new \parseCSV();

        foreach ($this->mappers as $mapper) {
            $collection = $mapper->mapping($collection);
        }

        $parser->output($target, $collection);
    }
}
