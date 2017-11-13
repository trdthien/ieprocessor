<?php

namespace Shopmacher\IEProcessor\Test\CommerceTools;

use Commercetools\Core\Client;
use Commercetools\Core\Config;
use Commercetools\Core\Model\Common\Context;
use PHPUnit\Framework\TestCase;
use Shopmacher\IEProcessor\CommerceTools\CommerceToolsProductIo;
use Shopmacher\IEProcessor\CommerceTools\CommerceToolsVariantDataIo;
use Shopmacher\IEProcessor\Csv\CsvDataIo;
use Shopmacher\IEProcessor\Csv\Helper\ConvertBoolean;
use Shopmacher\IEProcessor\Csv\Helper\ConvertNumber;
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

    public function testExportCsv()
    {
        $reader = new CommerceToolsVariantDataIo($this->client);
        $nodes = $reader->read();

        $writer = new CsvDataIo(';');
        $writer->setReverseMap(Yaml::parse(file_get_contents(__DIR__ . '/reserver-map.yml')));
        $writer->write($nodes, 'test.csv');
    }

    public function testImportCsv()
    {
        StackConverters::register(new ConvertBoolean());
        StackConverters::register(new ConvertNumber());
        StackConverters::register(new NotNullConverter());

        $reader = new CsvDataIo(';', 100);
        $reader->setForwardMap(Yaml::parse(file_get_contents(__DIR__ . '/forward-map.yml.yml')));
        $nodes  = $reader->read(__DIR__.'/integrationnew-products-2.csv');

        foreach ($nodes->toList() as $node) {
            /** @var Node $node */
            $p = $node->toArray();
            $p = reset($p);

            $node->addChildren(
                Node::of('slug')->addChildren(
                    Node::ofKeyAndValue('en', $p['variants'][0]['attributes'][3]['value']['en'])
                )->addChildren(
                    Node::ofKeyAndValue('de', $p['variants'][0]['attributes'][3]['value']['de'])
                )
            );
            $node->addChildren(
                Node::of('productType')
                        ->addChildren(
                            Node::ofKeyAndValue('id', 'c5da1cef-ed32-4b7c-918c-90c468603c83')
                        )
                        ->addChildren(
                            Node::ofKeyAndValue('typeId', 'product-type')
                        )
            );
        }

        $writer = new CommerceToolsProductIo($this->client);
        $writer->write($nodes);
    }
}

function clean($str)
{
    $str = preg_replace('/\s+/', '-', $str);
    // Removes special chars.
    return strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', $str));
}
