<?php


/**
 * Path to override in your module must be the same as it is in prestashop controllers or classes
 * you can't override controllers based on symfony.
 * Class AdminOrdersController
 */
class AdminOrdersController extends AdminOrdersControllerCore
{
    public function __construct()
    {
        parent::__construct();
        $this->fields_list['total_shipping'] = array(
                'title' => $this->trans('Total Shipping', array(), 'Admin.Global'),
        );
    }
}
