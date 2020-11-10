<?php

class AdminTrainingArticlesController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;

        $this->table = 'training_article';
        $this->className = 'TrainingArticle';
        $this->lang = true;
        $this->list_no_link = true;

        $this->addRowAction('edit');
        $this->addRowAction('delete');

        parent::__construct();
    }

    public function init()
    {
        parent::init();
        $this->initList();
        $this->initForm();

    }

    private function initForm()
    {
        $this->fields_form = [
            'tinymce' => true,
            'legend' => [
                'title' => $this->module->l('Article'),
                'icon' => 'icon-info-sign'
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->module->l('Name'),
                    'name' => 'name',
                    'lang' => true,
                    'required' => true,
                    'col' => '4',
                    'desc' => 'Name of your article'
                ],
                [
                    'type' => 'textarea',
                    'tinymce' => true,
                    'label' => $this->module->l('Description'),
                    'name' => 'description',
                    'lang' => true,
                    'col' => '4',
                ],
                [
                    'type' => 'text',
                    'label' => $this->module->l('Type'),
                    'name' => 'type',
                ],
                [
                    'type' => 'switch',
                    'label' => $this->module->l('Active'),
                    'name' => 'active',
                    'is_bool' => true,
                    'values' => [
                        ['value' => 1, 'id' => 'active_on'],
                        ['value' => 0, 'id' => 'active_off']
                    ]
                ],
                [
                    'type' => 'select',
                    'label' => $this->module->l('Product'),
                    'name' => 'id_product',
                    'options' => [
                        'query' => Product::getProducts(
                            $this->context->language->id,
                            0,
                            99,
                            'id_product',
                            'ASC'
                        ),
                        'name' => 'name',
                        'id' => 'id_product'
                    ]
                ],
            ],
            'submit' => [
                'title' => $this->module->l('Save'),
            ]
        ];
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        $this->addJS($this->module->getPathUri() . 'views/js/admin/articles.js');
        Media::addJsDef([
            'trainingArticlesController' => $this->context->link->getAdminLink($this->controller_name)
        ]);
    }

    private function initList(): void
    {
        $this->_select .= ' pl.name as product_name';
        $this->_join .= ' LEFT JOIN ' . _DB_PREFIX_ . 'product_lang `pl` ON pl.`id_product` = a.`id_product` AND pl.`id_lang` = ' . (int) $this->context->language->id;

        $this->fields_list = [
             'id_training_article' => [
                 'title' => $this->module->l('Id'),
                 'width' => 30,
            ],
            'name' => [
                'title' => $this->module->l('Name'),
                'width' => 100,
                'filter_key' => 'b.name'
            ],
            'type' => [
                'title' => $this->module->l('Type'),
                'width' => 100,
            ],
            'product_name' => [
                'title' => $this->module->l('Product'),
                'width' => 100,
                'filter_key' => 'pl!name'
            ],
            'active' => [
                'title' => $this->module->l('Active'),
                'type' => 'bool'
            ],
            'description' => [
                'title' => $this->module->l('Show Description'),
                'width' => 100,
                'orderby' => false,
                'search' => false,
                'callback' => 'getDescription'
            ],
        ];
    }

    public function getDescription($description, $params)
    {
        $twig = $this->module->getSymfonyContainer()->get('twig');

        return $twig->render(
            '@Modules/' . $this->module->name . '/views/templates/admin/description.html.twig',
            [
                'id_training_article' => $params['id_training_article']
            ]
        );
    }

    public function ajaxProcessGetDescription()
    {
        $idTrainingArticle = Tools::getValue('id_training_article');
        $article = new TrainingArticle($idTrainingArticle, $this->context->language->id);
        echo $article->description;
    }
}
