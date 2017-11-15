<?php

namespace Shopmacher\IEProcessor\CommerceTools;

use Commercetools\Core\Client;
use Commercetools\Core\Model\Category\CategoryReference;
use Commercetools\Core\Model\Category\CategoryReferenceCollection;
use Commercetools\Core\Model\Common\AttributeCollection;
use Commercetools\Core\Model\Common\LocalizedString;
use Commercetools\Core\Model\Product\ProductDraft;
use Commercetools\Core\Model\Product\ProductVariantDraft;
use Commercetools\Core\Model\Product\ProductVariantDraftCollection;
use Commercetools\Core\Model\ProductType\ProductTypeReference;
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
            $response = null;
            $variants = $node->getChildren()->findNode(Node::of('variants'));
            $categories = $node->getChildren()->findNode(Node::of('categories'));
            $name = $node->getChildren()->findNode(Node::of('name'));
            $slug = $node->getChildren()->findNode(Node::of('slug'));
            $type = $node->getChildren()->findNode(Node::of('productType'));
            $productDraft = ProductDraft::of();
            $productDraft->setSlug(LocalizedString::fromArray($slug->getChildren()->toArray()));
            $productDraft->setName(LocalizedString::fromArray($name->getChildren()->toArray()));
            $productDraft->setProductType(ProductTypeReference::fromArray($type->getChildren()->toArray()));

            $variantsArray = $variants->getChildren()->toArray();
            $masterVariantArray = $variantsArray[0];
            array_shift($variantsArray);

            $masterVariantDraft = ProductVariantDraft::of()
                ->setSku($masterVariantArray['sku'])
                ->setAttributes(
                    AttributeCollection::fromArray($masterVariantArray['attributes'])
                );

            $productDraft->setMasterVariant($masterVariantDraft);

            if (count($variantsArray)) {
                $variantCollection = ProductVariantDraftCollection::of();
                foreach ($variantsArray as $vKey => $variant) {
                    $variantCollection->add(
                        ProductVariantDraft::of()
                            ->setSku($variant['sku'])
                            ->setAttributes(
                                AttributeCollection::fromArray($variant['attributes'])
                            )
                    );

                    $productDraft->setVariants($variantCollection);
                }
            }

            $categoryReferences = CategoryReferenceCollection::of();
            $categoriesArray = $categories->getChildren()->toArray();

            foreach ($categories->getChildren()->toList() as $category) {
                $categoryArray = $category->getChildren()->toArray();
                if ($categoryArray['externalId']) {
                    $query = sprintf('externalId="%s"', $categoryArray['externalId']);
                } elseif ($categoryArray['key']) {
                    $query = sprintf('externalId="%s"', $categoryArray['key']);
                } else {
                    $query = sprintf('externalId="%s"', $categoryArray['id']);
                }

                $request = CategoryQueryRequest::of();
                $request->where($query)->limit(1);
                $response = $request->executeWithClient($this->client);
                $result = $request->mapFromResponse($response);
                $category = $result->current();

                $categoryReference = CategoryReference::ofId($category->getId());
                $categoryReferences->add($categoryReference);
            }

            $productDraft->setCategories($categoryReferences);

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
