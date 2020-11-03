<?php

declare(strict_types=1);

namespace Invertus\Training\SearchProvider;

use PrestaShop\Decimal\DecimalNumber;
use PrestaShop\Decimal\Number;
use PrestaShop\PrestaShop\Core\Product\Search\Facet;
use PrestaShop\PrestaShop\Core\Product\Search\FacetCollection;
use PrestaShop\PrestaShop\Core\Product\Search\Filter;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchProviderInterface;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchResult;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;
use Product;

class TrainingSearchProvider implements ProductSearchProviderInterface
{
    private const FACET_5_25 = 'price-$-5-25';
    private const FACET_25_75 = 'price-$-25-75';
    private const FACET_5_75 = 'price-$-5-75';

    public function runQuery(ProductSearchContext $context, ProductSearchQuery $query)
    {
        $searchResult = new ProductSearchResult();

        $totalProducts = Product::getProducts(
            $context->getIdLang(),
            0,
            0,
            'id_product',
            'DESC'
        );
        $start = 0;
        $end = $query->getResultsPerPage();
        if ($query->getPage() > 1) {
            $start = $query->getResultsPerPage() * ($query->getPage()-1);
            $end = $query->getResultsPerPage() * ($query->getPage());
        }

        $orderBy = $query->getSortOrder()->toLegacyOrderBy();
        if ($orderBy === 'position') {
            $orderBy = 'id_product';
        }

        $products = Product::getProducts(
            $context->getIdLang(),
            $start,
            $end,
            $orderBy,
            $query->getSortOrder()->toLegacyOrderWay()
        );

        $encodedFacets = $query->getEncodedFacets();

        foreach ($products as $key => $product) {
            $price = new DecimalNumber((string) $product['price']);
            $rate = new DecimalNumber((string) $product['rate']);
            $taxPrice = $price->dividedBy(new DecimalNumber('100'));
            $taxPrice = $taxPrice->times($rate);
            $price = $price->plus($taxPrice);
            $productPrice = (float)(string) $price;

            if ($encodedFacets === self::FACET_5_25) {
                if ($productPrice < 5 || $productPrice > 25) {
                    unset($products[$key]);
                }
            }

            if ($encodedFacets === self::FACET_25_75) {
                if ($productPrice < 25 || $productPrice > 75) {
                    unset($products[$key]);
                }
            }

            if ($encodedFacets === self::FACET_5_75) {
                if ($productPrice < 5 || $productPrice > 75) {
                    unset($products[$key]);
                }
            }
        }

        foreach ($totalProducts as $key => $product) {
            $productPrice = $product['price'] + ($product['price'] / 100 * $product['rate']);

            if ($encodedFacets === self::FACET_5_25) {
                if ($productPrice < 5 || $productPrice > 25) {
                    unset($totalProducts[$key]);
                }
            }

            if ($encodedFacets === self::FACET_25_75) {
                if ($productPrice < 25 || $productPrice > 75) {
                    unset($totalProducts[$key]);
                }
            }

            if ($encodedFacets === self::FACET_5_75) {
                if ($productPrice < 5 || $productPrice > 75) {
                    unset($totalProducts[$key]);
                }
            }
        }

        $searchResult->setTotalProductsCount(count($totalProducts));
        $searchResult->setProducts($products);
        $searchResult->setAvailableSortOrders($this->getSortOrders());
        $searchResult->setFacetCollection($this->getFacets($encodedFacets));
        return $searchResult;
    }

    private function getSortOrders(): array
    {
        return [
            (new SortOrder('product', 'id_product', 'asc'))->setLabel('Product ASC'),
            (new SortOrder('product', 'id_product', 'desc'))->setLabel('Product DESC'),
            (new SortOrder('product', 'price', 'asc'))->setLabel('Price ASC'),
            (new SortOrder('product', 'price', 'desc'))->setLabel('Price DESC'),
        ];
    }

    private function getFacets($encodedFacets): FacetCollection
    {
        $facetsCollection = new FacetCollection();
        $facet = new Facet();
        $facet->setLabel('Price');
        $facet->setDisplayed(true);
        $facet->setWidgetType('checkbox');
        $facet->setMultipleSelectionAllowed(true);

        $filter = new Filter();
        $filter->setLabel('$5 - $25');
        $filter->setType('price');
        $filter->setDisplayed(true);
        $filter->setMagnitude(4);
        $filter->setProperty('symbol', '$');
        $filter->setValue(['from' => '5', 'to' => '25']);
        $filter->setNextEncodedFacets(self::FACET_5_25);
        if ($encodedFacets == self::FACET_5_25) {
            $filter->setActive(true);
            $filter->setNextEncodedFacets('');
        }
        if ($encodedFacets == self::FACET_25_75) {
            $filter->setNextEncodedFacets(self::FACET_5_75);
        }
        if ($encodedFacets == self::FACET_5_75) {
            $filter->setActive(true);
            $filter->setNextEncodedFacets(self::FACET_25_75);
        }

        $facet->addFilter($filter);

        $filter = new Filter();
        $filter->setLabel('$25 - $75');
        $filter->setType('price');
        $filter->setDisplayed(true);
        $filter->setMagnitude(4);
        $filter->setProperty('symbol', '$');
        $filter->setValue(['from' => '25', 'to' => '75']);
        $filter->setNextEncodedFacets(self::FACET_25_75);
        if ($encodedFacets == self::FACET_25_75) {
            $filter->setActive(true);
            $filter->setNextEncodedFacets('');
        }
        if ($encodedFacets == self::FACET_5_25) {
            $filter->setNextEncodedFacets(self::FACET_5_75);
        }
        if ($encodedFacets == self::FACET_5_75) {
            $filter->setActive(true);
            $filter->setNextEncodedFacets(self::FACET_5_25);
        }
        $facet->addFilter($filter);

        $facetsCollection->addFacet($facet);

        return $facetsCollection;
    }
}
