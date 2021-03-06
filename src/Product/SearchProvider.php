<?php

namespace Invertus\Training\Product;

use PrestaShop\PrestaShop\Core\Product\Search\Facet;
use PrestaShop\PrestaShop\Core\Product\Search\FacetCollection;
use PrestaShop\PrestaShop\Core\Product\Search\Filter;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchProviderInterface;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchResult;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;
use Product;

class SearchProvider implements ProductSearchProviderInterface
{

    private $module;

    public function __construct($module)
    {
        $this->module = $module;
    }

    /**
     * @param \PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext $context
     * @param \PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery $query
     *
     * @return \PrestaShop\PrestaShop\Core\Product\Search\ProductSearchResult
     */
    public function runQuery(ProductSearchContext $context, ProductSearchQuery $query)
    {
        $result = new ProductSearchResult();
        $sortOrder = $query->getSortOrder();

        /**
         * need to transform order by in order for it to be usable in Product::getProducts()
         */
        $orderByLegacy = $sortOrder->toLegacyOrderBy();

        /**
         * default orderBy is position which Product table does not have so there will sql be an error if we keep order by as position
         */
        if ($orderByLegacy === 'position') {
            $orderByLegacy = 'id_product';
        }

        /**
         * Getting base list of products, those can be gained using any other way.
         */
        $products = Product::getProducts(
            $context->getIdLang(),
            0,
            0,
            $orderByLegacy,
            $sortOrder->toLegacyOrderWay()
        );

        $encodedFilters = $query->getEncodedFacets();


        /**|
         * Where encoded facets are being used to affect your product list.
         */
        foreach ($products as $key => $product) {
            $productPrice = $product['price'] + ($product['price'] / 100 * $product['rate']);

            if ($encodedFilters === 'price-$-18-23') {
                if ($productPrice < 18 || $productPrice > 23) {
                    unset($products[$key]);
                }
            }

            if ($encodedFilters === 'price-$-10-18') {
                if ($productPrice < 10 || $productPrice > 18) {
                    unset($products[$key]);
                }
            }

            if ($encodedFilters == 'price-$-10-18/price-$-18-23') {
                if ($productPrice < 10 || $productPrice > 23) {
                    unset($products[$key]);
                }
            }
        }


        $result->setTotalProductsCount(count($products));
        $result->setAvailableSortOrders($this->getAvailableSortOrders());
        $result->setProducts($products);


        $facets = new FacetCollection();
        $facets->addFacet($this->getFacet($query->getEncodedFacets()));
        $result->setFacetCollection($facets);

        return $result;
    }


    /**
     * Definition of sort orders
     */
    private function getAvailableSortOrders()
    {
        return [
            (new SortOrder('product', 'position', 'asc'))->setLabel(
                $this->module->getTranslator()->trans('Relevance', array(), 'Modules.Facetedsearch.Shop')
            ),
            (new SortOrder('product', 'id_product', 'asc'))->setLabel(
                $this->module->getTranslator()->trans('Product id from lowset to biggest', array(), 'Shop.Theme.Catalog')
            ),
            (new SortOrder('product', 'id_product', 'desc'))->setLabel(
                $this->module->getTranslator()->trans('Product Id from biggest to lowest', array(), 'Shop.Theme.Catalog')
            ),
            (new SortOrder('product', 'price', 'asc'))->setLabel(
                $this->module->getTranslator()->trans('Price, low to high', array(), 'Shop.Theme.Catalog')
            ),
            (new SortOrder('product', 'price', 'desc'))->setLabel(
                $this->module->getTranslator()->trans('Price, high to low', array(), 'Shop.Theme.Catalog')
            ),
        ];
    }


    /**
     * Facets are used to filter trough products.
     * Each facet has filters
     * Example of facet: Color
     * Example of filter: Red
     */
    private function getFacet($encodedFilters)
    {
        $facet = new Facet();
        $facet->setLabel('Price');
        $facet->setDisplayed(true);
        $facet->setWidgetType('checkbox');
        $facet->setMultipleSelectionAllowed(true);

        $filter = new Filter();
        $filter->setLabel('$10 - $18');
        $filter->setType('price');
        $filter->setDisplayed(true);
        $filter->setMagnitude(4);
        $filter->setProperty('symbol', '$');
        $filter->setValue(['from' => '14', 'to' => '18']);
        $filter->setNextEncodedFacets('price-$-10-18');

        /**
         * We need to check that filter is active so that it's visible to user.
         */
        if ($encodedFilters == 'price-$-10-18' || $encodedFilters == 'price-$-10-18/price-$-18-23') {
            $filter->setActive(true);
        }

        /**
         * we need to check nextEncodedFacets so later PrestaShop knows both filters were selected
         */
        if ($encodedFilters == 'price-$-18-23') {
            $filter->setNextEncodedFacets('price-$-10-18/price-$-18-23');
        }


        $facet->addFilter($filter);
        $filter = new Filter();
        $filter->setLabel('$18 - $23');
        $filter->setType('price');
        $filter->setDisplayed(true);
        $filter->setMagnitude(4);
        $filter->setProperty('symbol', '$');
        $filter->setValue(['from' => '18', 'to' => '23']);
        $filter->setNextEncodedFacets('price-$-18-23');
        if ($encodedFilters == 'price-$-18-23' || $encodedFilters == 'price-$-10-18/price-$-18-23') {
            $filter->setActive(true);
        }
        if ($encodedFilters == 'price-$-10-18') {
            $filter->setNextEncodedFacets('price-$-10-18/price-$-18-23');
        }

        $facet->addFilter($filter);

        return $facet;
    }
}
