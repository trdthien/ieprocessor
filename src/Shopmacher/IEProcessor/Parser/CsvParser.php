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
     * @var string
     */
    private $delimiter;

    /**
     * CsvParser constructor.
     * @param string $delimiter
     * @param array $mappers
     */
    public function __construct($delimiter = ',', array $mappers = [])
    {
        $this->delimiter = $delimiter;
        $this->mappers = $mappers;
    }

    /**
     * @inheritdoc
     */
    public function parse($file)
    {
        $parser = new \parseCSV();
        $parser->delimiter = $this->delimiter;
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
