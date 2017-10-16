<?php

namespace Shopmacher\IEProcessor;

use Shopmacher\IEProcessor\Parser\ParserInterface;
use Shopmacher\IEProcessor\Repository\RepositoryInterface;

/**
 * Class Exporter
 * @package Shopmacher\IEProcessor
 */
class Exporter implements ExporterInterface
{
    /**
     * @var ParserInterface
     */
    private $parser;
    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * Importer constructor.
     * @param ParserInterface $parser
     * @param RepositoryInterface $repository
     */
    public function __construct(ParserInterface $parser, RepositoryInterface $repository)
    {
        $this->parser = $parser;
        $this->repository = $repository;
    }

    /**
     * @inheritdoc
     */
    public function export($target = null)
    {
        $collection = $this->repository->load();
        $this->parser->write($collection, $target);
    }
}
