<?php


namespace Invertus\Training\SearchProvider;


use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchProviderInterface;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchResult;

class WishListSearchProvider implements ProductSearchProviderInterface
{
    private $cookies;

    public function __construct($cookies)
    {
        $this->cookies = $cookies;
    }

    public function runQuery(ProductSearchContext $context, ProductSearchQuery $query)
    {
        $searchResult = new ProductSearchResult();

        $products = $this->cookies->products;
        if ($products) {
            $decodedProductIds = json_decode($products);
        }

        $returnProducts = [];
        foreach ($decodedProductIds as $decodedProductId) {
            $returnProducts[] = [
                'id_product' => $decodedProductId
            ];
        }

        $searchResult->setTotalProductsCount(count($returnProducts));
        $searchResult->setProducts($returnProducts);
        return $searchResult;
    }
}
