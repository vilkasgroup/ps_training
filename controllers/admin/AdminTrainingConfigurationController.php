<?php

use Invertus\Training\Config\Config;

class AdminTrainingConfigurationController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;

        parent::__construct();
    }

    public function init()
    {
        parent::init();
        $this->initOptions();
    }

    private function initOptions()
    {
        $groups = Group::getGroups($this->context->language->id);
        $groupsForCheckbox = [];

        foreach ($groups as $group) {
            $groupsForCheckbox[$group['id_group']] = $group['name'];
        }

        $configurationProvider = $this->module->getSymfonyContainer()->get('training.provider.configuration_options_provider');

        $this->context->smarty->assign('training_selected_groups', json_decode(Configuration::get(Config::GROUPS)));
        $this->fields_options = [
            'training' => [
                'title' => $this->module->l('Configuration'),
                'fields' => [
                    Config::ARTICLES_PER_PAGE => [
                        'title' => $this->module->l('Articles per page'),
                        'desc' => $this->module->l('How many articles per page'),
                        'hint' => $this->module->l('enter number'),
                        'cast' => 'intval',
                        'type' => 'select',
                        'list' => $configurationProvider->getArticlesPerPageOptions(),
                        'identifier' => 'id',
                    ],
                    Config::GROUPS => [
                        'title' => $this->module->l('Groups'),
                        'desc' => $this->module->l('Groups that can see your articles'),
                        'cast' => 'intval',
                        'type' => 'custom_checkbox',
                        'choices' => $groupsForCheckbox,
                    ]
                ],
                'submit' => ['title' => $this->module->l('Save')]
            ]
        ];
    }

    public function beforeUpdateOptions()
    {
        $articlesPerPage = Tools::getValue(Config::ARTICLES_PER_PAGE);

        if ((int) $articlesPerPage != $articlesPerPage) {
            $this->errors[] = $this->module->l('Training articles per page must be an number');
        }

        parent::beforeUpdateOptions();
    }

    public function processUpdateOptions()
    {
        parent::processUpdateOptions();
        $groups = Group::getGroups($this->context->language->id);
        $selectedGroups = [];
        foreach ($groups as $group) {
            if (Tools::getValue(Config::GROUPS . $group['id_group'])) {
                $selectedGroups[] = $group['id_group'];
            }
        }

        Configuration::updateValue(Config::GROUPS, json_encode($selectedGroups));
    }
}
