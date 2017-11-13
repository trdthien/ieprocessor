<?php

namespace Shopmacher\IEProcessor\CommerceTools;

use Commercetools\Core\Client;
use Commercetools\Core\Request\Products\ProductProjectionQueryRequest;
use Shopmacher\IEProcessor\NodeIoInterface;
use Shopmacher\IEProcessor\Model\ArrayToNodeBuilder;
use Shopmacher\IEProcessor\Model\Node;
use Shopmacher\IEProcessor\Model\NodeCollection;

/**
 * Class CommerceToolsVariantDataIo
 * @package Shopmacher\IEProcessor\CommerceTools
 */
class CommerceToolsVariantDataIo implements NodeIoInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * CommerceToolsVariantDataIo constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $source
     * @return NodeCollection
     * @throws \Exception
     */
    public function read($source = null)
    {
        $request = ProductProjectionQueryRequest::of()->staged(true)->limit(500);

        $response = $request->executeWithClient($this->client);

        if ($response->isError()) {
            throw new \Exception($response->getErrors()->current()->getMessage());
        }

        $products = $request->mapFromResponse($response);

        $nodes = new NodeCollection();

        foreach ($products as $product) {
            $variantCollection = $product->getVariants();
            $variantCollection->add($product->getMasterVariant());
            $productArr = $product->toArray();
            foreach ($variantCollection as $ctVariant) {
                $node = Node::ofKeyAndId('variant', $productArr['id'] . '_' . $ctVariant->getId());
                $node->addChildren(
                    ArrayToNodeBuilder::build(
                        $productArr['name'],
                        'product.name'
                    )
                );
                $node->addChildren(
                    ArrayToNodeBuilder::build(
                        $productArr['slug'],
                        'product.slug'
                    )
                );
                $node->addChildren(
                    ArrayToNodeBuilder::build(
                        $productArr['slug'],
                        'product.slug'
                    )
                );
                $children = ArrayToNodeBuilder::build(
                    $ctVariant->toArray()
                );
                foreach ($children->toList() as $child) {
                    $node->addChildren($child);
                }
                $nodes->add($node);
            }
        }

        return $nodes;
    }

    /**
     * @inheritdoc
     */
    public function write(NodeCollection $nodes, $target = null)
    {
    }
}
