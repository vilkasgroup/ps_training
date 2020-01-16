<?php


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
