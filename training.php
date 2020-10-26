<?php

use Invertus\Training\SearchProvider\TrainingSearchProvider as SearchProvider;

class training extends Module
{
    private const HOOK_TYPE_WEIGHT = 'weight';

    public function __construct()
    {
        $this->name = 'training';
        $this->version = '1.0.0';
        $this->author = 'Invertus';

        parent::__construct();

        $this->displayName = $this->l('Training');
        $this->description = $this->l('Training description');
        $this->ps_versions_compliancy = array('min' => '1.7.7.0', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        if (!$this->registerHook('displayProductAdditionalInfo')) {
            return false;
        }

        if (!$this->registerHook('displayProductPriceBlock')) {
            return false;
        }

        if (!$this->registerHook('displayProductPriceBlock')) {
            return false;
        }

        if (!$this->registerHook('productSearchProvider')) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookProductSearchProvider($params)
    {
        /** @var \PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery $query */
        $query = $params['query'];
        if ($query->getIdCategory()) {
            return new SearchProvider();
        }
    }

    public function hookDisplayProductAdditionalInfo()
    {
       return 'hello world';
    }

    public function hookDisplayProductPriceBlock($params)
    {
        if ($params['type'] === self::HOOK_TYPE_WEIGHT) {
            return 'hello price';
        }
    }
}
