<?php

class AdminTrainingConfigurationController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;

        parent::__construct();
        $this->override_folder = 'template_overrides/';
        $this->tpl_folder = 'template_overrides/';

    }

    public function init()
    {
        parent::init();
        $this->initOptions();
    }

    public function initOptions()
    {
        // dump(Group::getGroups($this->context->language->id));
        $groups = Configuration::get('TRAINING_ARTICLE_GROUPS');
        $this->context->smarty->assign('select_fields_values',
            $groups
        );
        $this->fields_options = array(
            'training' => array(
                'title' => $this->module->l('Configuration'),
                'fields' => array(
                    'TRAINING_ARTICLES_PER_PAGE' => array(
                        'title' => $this->module->l('Articles per page'),
                        'desc' => $this->module->l('How many artilces per page'),
                        'hint' => $this->module->l('enter number'),
                        'cast' => 'intval',
                        'type' => 'custom_select',
                        'choices' => [
                            '1' => 'One',
                            '2' => 'Two',
                            '5' => 'Five'

                        ]
                    ),
                    'TRAINING_ARTICLE_GROUPS' => array(
                        'title' => $this->module->l('Articles per page'),
                        'desc' => $this->module->l('How many artilces per page'),
                        'hint' => $this->module->l('enter number'),
                        'cast' => 'intval',
                        'type' => 'text'
                    ),
                    'TRAINING_DISPLAY_ARTICLES' => array(
                        'title' => $this->module->l('Display articles'),
                        'cast' => 'intval',
                        'type' => 'bool',
                    ),
                    'TRAINING_DISPLAY_RIGHT_COLUMN' => array(
                        'title' => $this->module->l('Display right column'),
                        'cast' => 'intval',
                        'type' => 'bool',
                    ),
                    'TRAINING_ARTICLES_GROUPS' => array(
                        'title' => $this->module->l('Display articles'),
                        'cast' => 'intval',
                        'type' => 'select',
                        'list' => Group::getGroups($this->context->language->id),
                        'identifier' => 'id_group'

                    ),
                ),
                'submit' => array('title' => $this->trans('Save', array(), 'Admin.Actions')),
            ),
        );

        $this->fields_value['TRAINING_ARTICLES_PER_PAGE_2'] = [1, 2 ,5];
    }

//    public function postProcess()
//    {
//        $groups = Tools::getVAlue('SOME NAME FROM GET');
//        Configuration::updateValue('TRAINING_ARTICLE_GROUPS', json_encode($groups));
//        return parent::postProcess(); // TODO: Change the autogenerated stub
//    }
}
