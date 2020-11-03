<?php


use Invertus\Training\SearchProvider\TrainingSearchProvider;
use Invertus\Training\SearchProvider\WishListSearchProvider;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;

class TrainingWishListModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
        $this->context->smarty->assign('products', $this->getProducts());
        $this->setTemplate('module:' . $this->module->name . '/views/templates/front/wishList.tpl');
    }

    public function setMedia()
    {
        parent::setMedia();

        $this->registerStylesheet('training-wishlist-css', 'modules/training/views/css/wishlist.css');
        $this->registerJavascript('training-wishlist-js', 'modules/training/views/js/wishlist.js');
    }

    public function getProducts()
    {
        $searchProvider = new WishListSearchProvider($this->context->cookie);
        $context = new ProductSearchContext($this->context);
        $query = new ProductSearchQuery();

        $query
            ->setResultsPerPage(20)
            ->setPage(1)
        ;

        $result = $searchProvider->runQuery(
            $context,
            $query
        );

        /**  Responsible for retrieving all the related data to product */
        $assembler = new ProductAssembler($this->context);

        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = $presenterFactory->getPresenter();

        $products_for_template = [];

        foreach ($result->getProducts() as $rawProduct) {
            $products_for_template[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );
        }

        return $products_for_template;
    }
}
