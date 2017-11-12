<?php

namespace Shopmacher\IEProcessor\Csv;

use Shopmacher\IEProcessor\Model\Node;
use Shopmacher\IEProcessor\Model\NodeCollection;
use Shopmacher\IEProcessor\Model\NodeToFlatArrayBuilder;
use Shopmacher\IEProcessor\NodeIoInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class CsvDataIo
 * @package Shopmacher\IEProcessor
 */
class CsvDataIo implements NodeIoInterface
{
    private $parser;
    private $forwardMap;
    private $reverseMap;

    /**
     * CsvDataIo constructor.
     * @param string $delimiter
     */
    public function __construct($delimiter = ',')
    {
        $this->parser = new \parseCSV();
        $this->parser->limit = 300;
        $this->parser->delimiter = $delimiter;
    }

    /**
     * @return mixed
     */
    public function getForwardMap()
    {
        return $this->forwardMap;
    }

    /**
     * @param mixed $forwardMap
     */
    public function setForwardMap($forwardMap)
    {
        $this->forwardMap = $forwardMap;
    }

    /**
     * @return mixed
     */
    public function getReverseMap()
    {
        return $this->reverseMap;
    }

    /**
     * @param mixed $reverseMap
     */
    public function setReverseMap($reverseMap)
    {
        $this->reverseMap = $reverseMap;
    }

    /**
     * @param mixed|null $source
     */
    public function read($source = null)
    {
        if (!$source) {
            throw new \InvalidArgumentException('source missing.');
        }

        $this->parser->parse($source);
        $nodes = new NodeCollection();

        foreach ($this->parser->data as $row) {
            $nodes->add(Node::fromArray(
                $row,
                $this->forwardMap
            ));
        }

        return $nodes;
    }

    /**
     * @param NodeCollection $nodes
     * @param null $file
     * @throws \Exception
     * @return mixed
     */
    public function write(NodeCollection $nodes, $file = null)
    {
        if (!$file) {
            throw new \Exception('please provide the name of file');
        }

        $rows = [];

        foreach ($nodes->toList() as $node) {
            $rows[] = NodeToFlatArrayBuilder::build($node)[0];
        }

        $this->parser->save($file, $rows);
    }
}
