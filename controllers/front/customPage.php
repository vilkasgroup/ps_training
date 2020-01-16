<?php


use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use \PrestaShop\PrestaShop\Adapter\Presenter\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;

class TrainingCustomPageModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        $this->context->smarty->assign([
            'products' => $this->getProducts()
        ]);

        /**
         * You need setTemplate for front controller to not crash
         * Unless controller is never meant to be opened directly(For example used for ajax only)
         */
        $this->setTemplate('module:' . $this->module->name . '/views/templates/front/customPage.tpl');
    }

    public function setMedia()
    {
        parent::setMedia();

        /**
         * Adding variable to javascript
         */
        Media::addJsDef([
            'trainingCustomPageUrl' => $this->context->link->getModuleLink($this->module->name, 'customPage')
        ]);

        $this->registerStylesheet('training-custom-css', 'modules/training/views/css/customPage.css');
        $this->registerJavascript('training-custom-js', 'modules/training/views/js/customPage.js');
    }

    public function postProcess()
    {
        /**
         * $this->ajax makes sure its ajax
         * isTokenValid checks for tokeen
         */
        if ($this->isTokenValid() && $this->ajax && Tools::getValue('action') === 'getCurrentDay') {
            $this->ajaxRender('I dont know either');
        }
        parent::postProcess();
    }


    /**
     * Used to get Products using the ProductSearchProvider
     * This list is required to properly display product miniatures of prestashop which require a lot of stuff like
     * images, links, colors etc.
     * So this function usees productSearchProvided to get product list and then add all required info to that lsit
     */
    protected function getProducts()
    {
        /**
         * Search provider being taken trough container. This is src/Product/SearchProvider class
         */
        $searchProvider = $this->getContainer()->get('invertus.training.product.search_provider');

        $context = new ProductSearchContext($this->context);

        $query = new ProductSearchQuery();

        $query
            ->setResultsPerPage(20)
            ->setPage(1)
        ;

        /**
         * We get product list using context and query
         * Context is simply required for context of page like language
         * Query tells what sort of list we went to provider. Query might contain idCategory,
         * idManufacturer and so but it only matters if searchProvider takes them into account
         */
        $result = $searchProvider->runQuery(
            $context,
            $query
        );

        /**
         * Assemble is required to assemble product data and add missing properties
         */
        $assembler = new ProductAssembler($this->context);

        /**
         * Presentation settings hold settings that might affect how to dispaly products
         * Example: if Catalog mode is on you shouldn't display add to cart.
         */
        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();

        /**
         * Present is responsible for presenting additional data like images links etc,
         */
        $presenter = new ProductListingPresenter(
            new ImageRetriever(
                $this->context->link
            ),
            $this->context->link,
            new PriceFormatter(),
            new ProductColorsRetriever(),
            $this->context->getTranslator()
        );

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
