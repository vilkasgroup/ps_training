<?php

use Invertus\Training\TrainingProductSearchProvider;

class training extends Module
{
    public function __construct()
    {
        $this->name = 'training';
        $this->tab = 'analytics_stats';
        $this->version = '1.0.0';
        $this->author = 'Invertus';

        parent::__construct();

        $this->displayName = $this->l('Training');
        $this->description = $this->l('Training module');
        $this->ps_versions_compliancy = array('min' => '1.7.2.0', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        if (!$this->registerHook('displayProductAdditionalInfo')) {
            return false;
        }
        if (!$this->registerHook('productSearchProvider')) {
            return false;
        }

        return true;
    }

    public function hookProductSearchProvider($params)
    {
        /** @var \PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery $query */
        $query = $params['query'];

        if ($query->getIdCategory()) {
            $searchProvider = $this->context->controller->getContainer()->get('invertus.training.product.search_provider');

            return $searchProvider;
        } else {
            return null;
        }
    }

    public function hookDisplayProductAdditionalInfo($params): void
    {
        $getProductPrice = $this->context->controller->getContainer()->get('training.get_price');
        echo $getProductPrice->getPrice($params['product']->getId());
    }
}
