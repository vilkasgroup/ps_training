<?php

namespace Invertus\Training\Form\ChoiceProvider;

use PrestaShop\PrestaShop\Core\Form\FormChoiceProviderInterface;
use Product;

class ProductChoiceProvider implements FormChoiceProviderInterface
{
    private $contextLangId;

    public function __construct($contextLangId)
    {
        $this->contextLangId = $contextLangId;
    }

    public function getChoices()
    {
        $products = Product::getProducts($this->contextLangId, 0, 1000, 'id_product', 'DESC');
        $returnProducts = [];
        foreach ($products as $product) {
            $returnProducts[$product['id_product'] . '-' . $product['name']] = $product['id_product'];
        }
        return $returnProducts;
    }
}
