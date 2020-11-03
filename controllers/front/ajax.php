<?php


class TrainingAjaxModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        if (!Tools::getValue('ajax')) {
            return false;
        }

        if (!$this->isTokenValid()) {
            return false;
        }

        switch (Tools::getValue('action')) {
            case 'addToWishList':
                $this->addToWishList();
                continue;
        }


        parent::postProcess();
    }

    public function addToWishList()
    {
        $products = $this->context->cookie->products;
        if ($products) {
            $products = json_decode($products);
        }
        if (!is_array($products)) {
            $products = [];
        }

        $idProduct = Tools::getValue('id_product');
        $products[] = $idProduct;
        $this->context->cookie->products = json_encode($products);
        $this->ajaxRender('Product succesfully added');
    }
}
