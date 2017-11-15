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
            'client_id' => '0Ru470w3DafSAsCXKeeXyws_',
            'client_secret' => 'V3OV46rk5vSiw2qXlZ6JBYnM-SiY7nF5',
            'project' => 'koffer24-data'
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

        $reader = new CsvDataIo(';');
        $reader->setForwardMap(Yaml::parse(file_get_contents(__DIR__ . '/forward-map.yml')));
        $nodes  = $reader->read(__DIR__.'/integrationnew-products-2.csv');

        foreach ($nodes->toList() as $node) {
            $node->addChildren(
                Node::of('productType')
                        ->addChildren(
                            Node::ofKeyAndValue('id', '66083275-712c-4ea4-a0cb-30859223d5da')
                        )
                        ->addChildren(
                            Node::ofKeyAndValue('typeId', 'product-type')
                        )
            );
        }

        $writer = new CommerceToolsProductIo($this->client);
        $fh = fopen(__DIR__ . '/import-log.txt', 'w');
        $writer->setLogger(new SimpleLogger($fh));
        $writer->write($nodes);
    }
}
