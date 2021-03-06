<?php

namespace Invertus\Training\Price;

use training;

class GetProductPrice
{
    /**
     * @var training
     */
    private $module;

    public function __construct($module)
    {
        $this->module = $module;
    }

    /*
     * This is a nonsensical class used to demonstrate how services.yml works.
     */
    public function getPrice($id)
    {
        if ($id === 2) {
            return $this->module->l('Message');
        }

        return 10;
    }
}
