<?php

namespace Shopmacher\IEProcessor\Csv;

use Shopmacher\IEProcessor\Model\NodeCollection;
use Shopmacher\IEProcessor\Model\NodeToFlatArrayBuilder;
use Shopmacher\IEProcessor\NodeIoInterface;

/**
 * Class CsvDataIo
 * @package Shopmacher\IEProcessor
 */
class CsvDataIo implements NodeIoInterface
{
    /**
     * @var \parseCSV
     */
    private $parser;
    /**
     * @var array
     */
    private $forwardMap;
    /**
     * @var array
     */
    private $reverseMap;

    /**
     * CsvDataIo constructor.
     * @param string $delimiter
     * @param int $limit
     */
    public function __construct($delimiter = ',', $limit = -1)
    {
        $this->parser = new \parseCSV();
        $this->parser->delimiter = $delimiter;
        if ($limit > 0) {
            $this->parser->limit = $limit;
        }
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
     * @param null $source
     * @return NodeCollection
     */
    public function read($source = null)
    {
        if (!$source) {
            throw new \InvalidArgumentException('source missing.');
        }

        $this->parser->parse($source);
        $nodes = new NodeCollection();

        foreach ($this->parser->data as $row) {
            $nodes->add(FlatArrayToNodeBuilder::build(
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

        $records = [];

        foreach ($nodes->toList() as $row => $node) {
            $data = NodeToFlatArrayBuilder::build($node)[0];
            if ($this->reverseMap) {
                foreach ($this->reverseMap as $key => $mapValue) {
                    $records[$row+1][] = isset($data[$key]) ? $data[$key] : null;
                }
            }
        }

        array_unshift($records, array_values($this->reverseMap));

        $this->parser->save($file, array_values($records));
    }
}
