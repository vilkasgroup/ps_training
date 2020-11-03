<?php


use Invertus\Training\SearchProvider\WishListSearchProvider;
use PHPUnit\Framework\TestCase;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchResult;

class WishListProviderTest extends TestCase
{
    public function test_provider()
    {
        $cookies = new Cookie('products');
        $cookies->products = json_encode(['1', '2']);
        $wishListProvider = new WishListSearchProvider($cookies);
        $context = new ProductSearchContext();
        $query = new ProductSearchQuery();
        $result = $wishListProvider->runQuery($context, $query);
        $expectedResult = new ProductSearchResult();
        $expectedResult->setProducts([
           ['id_product' => 1], ['id_product' => 2],
        ]);
        $expectedResult->setTotalProductsCount(2);
        $this->assertEquals($result->getProducts(), $expectedResult->getProducts());
        $this->assertEquals($result->getTotalProductsCount(), $expectedResult->getTotalProductsCount());

    }
}
