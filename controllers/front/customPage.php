<?php


use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use \PrestaShop\PrestaShop\Adapter\Presenter\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

class TrainingCustomPageModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        $this->context->smarty->assign([
            'products' => $this->getProducts()
        ]);

        $this->setTemplate('module:' . $this->module->name . '/views/templates/front/customPage.tpl');
    }

    public function setMedia()
    {
        parent::setMedia();

        Media::addJsDef([
            'trainingCustomPageUrl' => $this->context->link->getModuleLink($this->module->name, 'customPage')
        ]);

        $this->registerStylesheet('training-custom-css', 'modules/training/views/css/customPage.css');
        $this->registerJavascript('training-custom-js', 'modules/training/views/js/customPage.js');
    }

    public function postProcess()
    {
        if ($this->isTokenValid() && $this->ajax && Tools::getValue('action') === 'getCurrentDay') {
            $this->ajaxRender('I dont know either');
        }
        parent::postProcess();
    }

    protected function getProducts()
    {
        $searchProvider = $this->getContainer()->get('invertus.training.product.search_provider');

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

        $assembler = new ProductAssembler($this->context);

        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();

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
