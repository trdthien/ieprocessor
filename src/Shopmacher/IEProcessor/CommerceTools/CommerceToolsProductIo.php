<?php

namespace Shopmacher\IEProcessor\CommerceTools;

use Commercetools\Core\Client;
use Commercetools\Core\Model\Category\Category;
use Commercetools\Core\Model\Category\CategoryReference;
use Commercetools\Core\Model\Category\CategoryReferenceCollection;
use Commercetools\Core\Model\Common\AttributeCollection;
use Commercetools\Core\Model\Common\LocalizedString;
use Commercetools\Core\Model\Common\PriceDraftCollection;
use Commercetools\Core\Model\Product\ProductDraft;
use Commercetools\Core\Model\Product\ProductVariantDraft;
use Commercetools\Core\Model\Product\ProductVariantDraftCollection;
use Commercetools\Core\Model\ProductType\ProductTypeReference;
use Commercetools\Core\Model\TaxCategory\TaxCategoryReference;
use Commercetools\Core\Request\Categories\CategoryQueryRequest;
use Commercetools\Core\Request\Products\ProductCreateRequest;
use Commercetools\Core\Request\Products\ProductProjectionQueryRequest;
use Psr\Log\LogLevel;
use Shopmacher\IEProcessor\Model\TraitLogger;
use Shopmacher\IEProcessor\NodeIoInterface;
use Shopmacher\IEProcessor\Model\ArrayToNodeBuilder;
use Shopmacher\IEProcessor\Model\Node;
use Shopmacher\IEProcessor\Model\NodeCollection;

/**
 * Class CommerceToolsProductIo
 * @package Shopmacher\IEProcessor\CommerceTools
 */
class CommerceToolsProductIo implements NodeIoInterface
{
    /**
     * @var Client
     */
    private $client;

    use TraitLogger;

    /**
     * CommerceToolsProductIo constructor.
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
        $request = ProductProjectionQueryRequest::of();

        $response = $request->executeWithClient($this->client);

        if ($response->isError()) {
            throw new \Exception($response->getErrors()->current()->getMessage());
        }

        $products = $request->mapFromResponse($response);

        $nodes = new NodeCollection();
        foreach ($products as $product) {
            $node = Node::ofKeyAndId('product', $product->getId());
            $children = ArrayToNodeBuilder::build(
                $product->toArray()
            );
            foreach ($children->toList() as $child) {
                $node->addChildren($child);
            }
            $nodes->add($node);
        }

        return $nodes;
    }

    /**
     * @inheritdoc
     */
    public function write(NodeCollection $nodes, $target = null)
    {
        foreach ($nodes->toList() as $node) {
            $nameNode = $node->getChildren()->findNode(Node::of('name'));
            $slugNode = $node->getChildren()->findNode(Node::of('slug'));
            $variantNodes = $node->getChildren()->findNode(Node::of('variants'));
            $categoryNodes = $node->getChildren()->findNode(Node::of('categories'));
            $productTypeNode = $node->getChildren()->findNode(Node::of('productType'));
            $taxCategoryNode = $node->getChildren()->findNode(Node::of('taxCategory'));

            $productDraft = ProductDraft::of();
            $productDraft->setSlug(LocalizedString::fromArray($slugNode->getChildren()->toArray()));
            $productDraft->setName(LocalizedString::fromArray($nameNode->getChildren()->toArray()));
            $productDraft->setProductType(ProductTypeReference::fromArray($productTypeNode->getChildren()->toArray()));

            if ($taxCategoryNode) {
                $productDraft->setTaxCategory(
                    TaxCategoryReference::fromArray(
                        $taxCategoryNode->getChildren()->toArray()
                    )
                );
            }

            $variants = $variantNodes->getChildren()->toArray();
            $masterVariant = array_pop($variants);

            $masterVariantDraft = ProductVariantDraft::of()
                ->setSku($masterVariant['sku'])
                ->setAttributes(
                    AttributeCollection::fromArray($masterVariant['attributes'])
                )->setPrices(
                    PriceDraftCollection::fromArray($masterVariant['prices'])
                );

            $productDraft->setMasterVariant(
                $masterVariantDraft
            );

            if (count($variants)) {
                $variantCollection = ProductVariantDraftCollection::of();
                foreach ($variants as $variant) {
                    $variantCollection->add(
                        ProductVariantDraft::of()
                            ->setSku($variant['sku'])
                            ->setAttributes(
                                AttributeCollection::fromArray($variant['attributes'])
                            )->setPrices(
                                PriceDraftCollection::fromArray($variant['prices'])
                            )
                    );
                }
                $productDraft->setVariants($variantCollection);
            }

            $categoryReferences = CategoryReferenceCollection::of();

            foreach ($categoryNodes->getChildren()->toList() as $categoryNode) {
                $category = $categoryNode->getChildren()->toArray();
                if ($category['externalId']) {
                    $query = sprintf('externalId="%s"', $category['externalId']);
                } elseif ($category['key']) {
                    $query = sprintf('externalId="%s"', $category['key']);
                } else {
                    $query = sprintf('externalId="%s"', $category['id']);
                }

                $request = CategoryQueryRequest::of();
                $request->where($query)->limit(1);
                $response = $request->executeWithClient($this->client);

                $result = $request->mapFromResponse($response);
                $categoryNode = $result->current();

                if ($categoryNode && $categoryNode instanceof Category) {
                    $categoryReference = CategoryReference::ofId($categoryNode->getId());
                    $categoryReferences->add($categoryReference);
                }
            }

            $productDraft->setCategories($categoryReferences);

            $response = null;
            $request = ProductCreateRequest::ofDraft($productDraft);
            $response = $request->executeWithClient($this->client);



            if ($response->isError()) {
                $this->getLogger()->log(
                    LogLevel::ERROR,
                    sprintf(
                        "Message: %s",
                        $response->getErrors()->current()->getMessage()
                    )
                );
            }
        }
    }
}
