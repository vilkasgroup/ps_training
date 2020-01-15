<?php

class training extends Module
{
    public function __construct()
    {
        $this->name = 'training';
        $this->tab = 'analytics_stats';
        $this->version = '1.0.0';
        $this->author = 'Invertus';

        parent::__construct();

        $this->displayName = $this->l('Traininhg');
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

        return true;
    }

    public function hookDisplayProductAdditionalInfo($params)
    {
        $repository = new \Invertus\Training\Repository\CurrencyExchangeRepository();
        $changer = new \Invertus\Training\Currency\CurrencyExchangeChanger($repository);
        $getProductPrice = $this->context->controller->getContainer()->get('training.get_price');
        echo $getProductPrice->getPrice($params['product']->getId());
    }
}
