<?php


class ProductController extends ProductControllerCore
{
    /**
     * Assign template vars related to category.
     */
    protected function assignCategory()
    {
        $this->success[] = 'hello world';
        parent::assignCategory();
    }
}
