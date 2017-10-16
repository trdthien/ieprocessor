<?php

namespace Shopmacher\IEProcessor;

use Shopmacher\IEProcessor\Parser\ParserInterface;
use Shopmacher\IEProcessor\Repository\RepositoryInterface;

/**
 * Class Importer
 * @package Shopmacher\IEProcessor
 */
class Importer implements ImporterInterface
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
     * @param $source
     * @return void
     */
    public function import($source)
    {
        $collection = $this->parser->parse($source);
        $this->repository->save($collection);
    }
}
