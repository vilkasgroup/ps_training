<?php

/**
 * In order for controlelr to work tab must be registered
 * Also Naming Should be Admin{ControllerName}Controller
 * It must extend ModuleAdminController
 * Class AdminTrainingArticleController
 */
class AdminTrainingArticleController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        /**
         * Connecting Controller with object and database
         */
        $this->table = 'training_article';
        $this->className = 'TrainingArticle';
        $this->lang = true;

        /**
         * Adding row actions without which we cant edit or delete
         * Custom row actions can be added
         */
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->_defaultOrderBy = 'id_training_article';
        parent::__construct();
    }

    public function init()
    {
        parent::init();
        $this->initList();
        $this->initForm();
    }

    /*
     * More information how form is being displayed can be found in PS/admin/themes/default/template/helpers/list/list-content.tpl
     */
    private function initList()
    {
        /**
         * Those are needed if you want to add more data to select then table of object of this controller
         */
        $this->_select = ' pl.`name` as product_name, pl.`name` as product_button_name';
        $this->_join = 'LEFT JOIN `'._DB_PREFIX_.'product_lang` `pl` ON pl.`id_product` = a.`id_training_article` AND pl.`id_lang` = ' . (int)$this->context->language->id;
        $this->fields_list =  array(
            'id_training_article' => array(
                'title' => $this->module->l('Id'),
                'width' => 100,
                'class' => 'my-custom-class'
            ),
            'name' => array(
                'title' => $this->module->l('Name'),
                'width' => 80,
            ),
            'description' => array(
                'title' => $this->module->l('Description'),
                'width' => 80,
                'filter_key' => 'b!description'
            ),
            'type' => array(
                'title' => $this->module->l('Type'),
                'width' => 80,
            ),
            'product_name' => array(
                'title' => $this->module->l('Product Name'),
                'width' => 80,
                'filter_key' => 'pl!name'
            ),
            'product_button_name' => array(
                'title' => $this->module->l('Product Name'),
                'width' => 80,
                'callback' => 'getProductName',
            ),
        );

    }

    /**
     * Callback function which can return whatever you want instead to list.
     * Useful to adding custom things to list
     */
    public function getProductName($productName, $tr)
    {
        $this->context->smarty->assign(array(
            'productName' => $productName,
        ));

        return $this->context->smarty->fetch('module:training/views/templates/admin/productName.tpl');
    }

    /**
     * More information how form is being displayed can be found in PS/admin/themes/default/template/helpers/form/form.tpl
     */
    private function initForm()
    {
        /**
         * this way you can show certain values to customers as selected
         */
        $this->fields_value['type_1'] = true;
        $this->fields_value['type_3'] = true;

        $this->fields_form =  array(
            'legend' => array(
                'title' => $this->module->l('Article'),
                'icon' => 'icon-info-sign',
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->module->l('Name'),
                    'name' => 'name',
                    'lang' => true,
                    'required' => true,
                    'col' => '4',
                    'hint' => $this->trans('Your internal name for this attribute.', array(), 'Admin.Catalog.Help') . '&nbsp;' . $this->trans('Invalid characters:', array(), 'Admin.Notifications.Info') . ' <>;=#{}',
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->module->l('Description'),
                    'name' => 'description',
                    'lang' => true,
                    'required' => true,
                    'col' => '4',
                    'hint' => $this->trans('Your internal name for this attribute.', array(), 'Admin.Catalog.Help') . '&nbsp;' . $this->trans('Invalid characters:', array(), 'Admin.Notifications.Info') . ' <>;=#{}',
                ),
                array(
                    'type' => 'checkbox',
                    'label' => $this->module->l('Type'),
                    'name' => 'type',
                    'values' => array(
                        'query' => Group::getGroups($this->context->language->id),
                        'id' => 'id_group',
                        'name' => 'name',
                    ),
                    'col' => '4',
                    'hint' => $this->trans('Your internal name for this attribute.', array(), 'Admin.Catalog.Help') . '&nbsp;' . $this->trans('Invalid characters:', array(), 'Admin.Notifications.Info') . ' <>;=#{}',
                ),
            ),
            'submit' => array(
                'title' => $this->trans('Save', array(), 'Admin.Actions'),
            )
        );
    }
//
    /**
     * In processSave you can execute your own saving operations. You could use other functions
     * like postProcess but processSave is great because that's when PrestaShop does the saving
     */
//    public function processSave()
//    {
//       // Db::getInstance()->delete('article_group', 'id_article = ' . (int)$this->id_object);
//
//
//        parent::processSave();
//
//    }

}
