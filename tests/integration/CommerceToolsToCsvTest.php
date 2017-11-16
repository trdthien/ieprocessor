<?php

namespace Shopmacher\IEProcessor\Test\CommerceTools;

use Commercetools\Core\Client;
use Commercetools\Core\Config;
use Commercetools\Core\Helper\Subscriber\Log\SimpleLogger;
use Commercetools\Core\Model\Common\Context;
use PHPUnit\Framework\TestCase;
use Shopmacher\IEProcessor\CommerceTools\CommerceToolsProductIo;
use Shopmacher\IEProcessor\CommerceTools\CommerceToolsVariantDataIo;
use Shopmacher\IEProcessor\Csv\CsvDataIo;
use Shopmacher\IEProcessor\Csv\Helper\ConvertBoolean;
use Shopmacher\IEProcessor\Csv\Helper\ConvertName;
use Shopmacher\IEProcessor\Csv\Helper\ConvertNumber;
use Shopmacher\IEProcessor\Csv\Helper\ConvertRandom;
use Shopmacher\IEProcessor\Csv\Helper\ConvertSlug;
use Shopmacher\IEProcessor\Csv\Helper\NotNullConverter;
use Shopmacher\IEProcessor\Csv\Helper\StackConverters;
use Shopmacher\IEProcessor\Model\Node;
use Shopmacher\IEProcessor\Model\NodeToFlatArrayBuilder;
use Symfony\Component\Yaml\Yaml;

/**
 * Class CommerceToolsToCsvTest
 * @package Shopmacher\IEProcessor\Test\CommerceTools
 */
class CommerceToolsToCsvTest extends TestCase
{
    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        $config = [
            'client_id' => 'lUVaD8NWY7rYTnnHaZhGCNvG',
            'client_secret' => 'eYkowvcaOmHt_EyetrKU8IlIn6RF1uGY',
            'project' => 'koffer24-dev'
        ];

        $context = Context::of()->setLanguages(['en'])->setLocale('en_US')->setGraceful(true);
        $config = Config::fromArray($config)->setContext($context);
        $this->client = Client::ofConfig($config);
    }

//    public function testExportCsv()
//    {
//        $reader = new CommerceToolsVariantDataIo($this->client);
//        $nodes = $reader->read();
//
//        $writer = new CsvDataIo(';');
//        $writer->setReverseMap(Yaml::parse(file_get_contents(__DIR__ . '/reserver-map.yml')));
//        $writer->write($nodes, 'test.csv');
//    }

    public function testImportCsv()
    {
        StackConverters::register(new ConvertBoolean());
        StackConverters::register(new ConvertNumber());
        StackConverters::register(new NotNullConverter());
        StackConverters::register(new ConvertSlug());
        StackConverters::register(new ConvertName());
        StackConverters::register(new ConvertRandom());

        $reader = new CsvDataIo(';', 100);
        $reader->setForwardMap(Yaml::parse(file_get_contents(__DIR__ . '/forward-map-dev.yml')));
        $nodes  = $reader->read(__DIR__.'/integrationnew-products-2.csv');

        $writer = new CommerceToolsProductIo($this->client);
        $fh = fopen(__DIR__ . '/import-log-dev.txt', 'w');
        $writer->setLogger(new SimpleLogger($fh));
        $writer->write($nodes);
    }
}
