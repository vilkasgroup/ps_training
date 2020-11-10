<?php

use Invertus\Training\API\Cat\Client;
use Invertus\Training\Config\Config;
use Invertus\Training\SearchProvider\TrainingSearchProvider as SearchProvider;
use PrestaShop\PrestaShop\Adapter\Presenter\Product\ProductLazyArray;
use PrestaShop\PrestaShop\Adapter\SymfonyContainer;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class training extends Module implements WidgetInterface
{
    private const HOOK_TYPE_WEIGHT = 'weight';

    const CONTROLLER_PARENT = 'AdminTrainingParent';
    const CONTROLLER_CONFIGURATION = 'AdminTrainingConfiguration';
    const CONTROLLER_ARTICLES = 'AdminTrainingArticles';

    public function __construct()
    {
        $this->name = 'training';
        $this->version = '1.0.2';
        $this->author = 'Invertus';

        parent::__construct();

        $this->displayName = $this->l('Training');
        $this->description = $this->l('Training description');
        $this->ps_versions_compliancy = array('min' => '1.7.7.0', 'max' => _PS_VERSION_);
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    public function getContent()
    {
        $route = $this->getSymfonyContainer()->get('router')->generate('training_admin_article');
        Tools::redirectAdmin($route);
        //Tools::redirectAdmin($this->context->link->getAdminLink(self::CONTROLLER_CONFIGURATION));
    }

    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        if (!$this->registerHooks()) {
            return false;
        }

        if (!$this->createTables()) {
            return false;
        }

        return true;
    }

    private function createTables()
    {
        $return =  Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS '._DB_PREFIX_. 'training_article' . '(
            `id_training_article` INTEGER(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `type` varchar(100) DEFAULT NULL,
            `active` TINYINT(1) DEFAULT 0,
            `id_product` INTEGER(10) DEFAULT NULL
                ) ENGINE='. _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8');

        $return &= Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS '._DB_PREFIX_. 'training_article_lang' . '(
            `id_training_article` INTEGER(10) UNSIGNED,
            `id_lang` INTEGER(10) UNSIGNED ,
            `name` varchar(128),
            `description` varchar(128),
            PRIMARY KEY (id_training_article, id_lang)
                ) ENGINE='. _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8');

        return $return;
    }

    private function registerHooks()
    {
        $hooks = [
            'displayProductAdditionalInfo',
            'displayProductPriceBlock',
            'productSearchProvider',
            'actionFrontControllerSetMedia',
            'additionalCustomerFormFields',
            'validateCustomerFormFields',
            'displayAdminOrderMain',
            'displayAdminOrderLeft',
            'actionCategoryGridQueryBuilderModifier',
            'actionCategoryGridDataModifier',
            'actionCategoryGridDefinitionModifier',
            'actionPerformancePageDebugModeForm',
            'actionPerformancePageDebugModeSave',
            'actionCustomerFormBuilderModifier',
            'actionAfterCreateCustomerFormHandler',
            'actionAfterUpdateCustomerFormHandler'
        ];

        foreach ($hooks as $hook) {
            if (!$this->registerHook($hook)) {
                return false;
            }
        }
        return true;
    }

    public function hookActionCustomerFormBuilderModifier($params)
    {
        $formBuilder = $params['form_builder'];
        $formBuilder->add(
            'vip',
            SwitchType::class,
            [
                'label' => $this->trans('VIP', [], 'Modules.Training.Hook'),
            ]
        );
    }

    public function hookActionAfterCreateCustomerFormHandler($params)
    {
        $idCustomer = $params;
        $vip = $params['form_data']['vip'];
        Db::getInstance()->update('training_customer', [
            'id_customer' => $idCustomer,
            'vip' => $vip
        ]);
    }
    public function hookActionAfterUpdateCustomerFormHandler($params)
    {
        $idCustomer = $params;
        $vip = $params['form_data']['vip'];
        Db::getInstance()->update('training_customer', [
            'id_customer' => $idCustomer,
            'vip' => $vip
        ]);
    }

    public function hookActionPerformancePageDebugModeForm($params)
    {
        $formBuilder = $params['form_builder'];
        $formBuilder->add(
            'text',
            TextType::class,
            [
                'data' => Configuration::get(Config::CUSTOM_DEBUG_MODE),
                'label' => $this->trans('Text', [], 'Modules.Training.Hook'),
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\Length(
                        [
                            'min' => 5
                        ]
                    )
                ]
            ]
        );
        $formBuilder->add(
            'custom_debug_mode',
            SwitchType::class,
            [
                'data' => Configuration::get(Config::CUSTOM_DEBUG_MODE),
                'label' => $this->trans('Custom Debug Mode', [], 'Modules.Training.Hook')
            ]
        );
    }

    public function hookActionPerformancePageDebugModeSave($params)
    {
        $data = $params['form_data']['custom_debug_mode'];
        Configuration::updateValue(Config::CUSTOM_DEBUG_MODE, (int) $data);
    }

    public function hookActionCategoryGridQueryBuilderModifier($params)
    {
        /**
         * @var $searchQueryBuilder Doctrine\DBAL\Query\QueryBuilder
         */
        $searchQueryBuilder = $params['search_query_builder'];
        $searchQueryBuilder->addSelect('ta.type');
        $searchQueryBuilder->leftJoin(
            'c',
            _DB_PREFIX_ . 'training_article',
            'ta',
            'c.id_category = ta.id_training_article'
        );
    }

    public function hookActionCategoryGridDefinitionModifier($params)
    {
        /** @var \PrestaShop\PrestaShop\Core\Grid\Definition\GridDefinition $definition */
        $definition = $params['definition'];
        $definition->getColumns()->addAfter('position', (new DataColumn('Type'))
            ->setName($this->l('Type'))
            ->setOptions([
                'field' => 'type',
            ])
        );
    }

    //public function hookActionCategoryGridDataModifier($params)
    //{
    //    dump($params);
    //}

    public function hookAdditionalCustomerFormFields()
    {
        $formField = (new FormField())
            ->setName('repeat_password')
            ->setType('password')
            ->setLabel($this->l('Repeat password'))
            ->setRequired(true);

        return array($formField);
    }

    public function hookDisplayAdminOrderLeft()
    {
        //$query = new DbQuery();
        //$query->select('');
        //$query->from('training_article');
        //$query->where('');
        //$query->build();
        //DB::getInstance()->update('training_article', [
        //            ''
        //        ],
        //    'id_training_article = 1'
        //);

        /** Comparing versions in PS */
        //version_compare(_PS_VERSION_, $this->ps_versions_compliancy['min'], '<')
        $twig = $this->getSymfonyContainer()->get('twig');
        return $twig->render(
            '@Modules/' . $this->name . '/views/templates/admin/displayAdminOrderLeft.html.twig'
        );
    }

    public function hookDisplayAdminOrderMain()
    {
        $twig = $this->getSymfonyContainer()->get('twig');
        $client = $this->getSymfonyContainer()->get(Client::class);
        dump($client->getCatFacts());
        return $twig->render(
            '@Modules/' . $this->name . '/views/templates/admin/displayAdminOrderLeft.html.twig',
            [
                'articlesController' => self::CONTROLLER_ARTICLES,
                'var' => 'My variable'
            ]
        );
    }

    public function hookValidateCustomerFormFields($params)
    {
        /** @var FormField $repeatPasswordField */
        $repeatPasswordField = $params['fields'][0];
        $repeatedPassword = $repeatPasswordField->getValue();
        $password = Tools::getValue('password');
        if ($password !== $repeatedPassword) {
            $repeatPasswordField->addError('Passwords are not the same');
        }
        return [$repeatPasswordField];
    }

    public function hookActionFrontControllerSetMedia()
    {
        if ($this->context->controller instanceof ProductControllerCore) {
            Media::addJsDef([
                'trainingAjaxController' => $this->context->link->getModuleLink($this->name, 'ajax')
            ]);
            $this->context->controller->registerJavascript('training-wishlist-js', 'modules/training/views/js/wishlist.js');
        }
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookProductSearchProvider($params)
    {
        /** @var \PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery $query */
        $query = $params['query'];
        if ($query->getIdCategory()) {
            return new SearchProvider();
        }
    }

    public function getSymfonyContainer()
    {
        return SymfonyContainer::getInstance();
    }

    public function mySmartyFunction($params)
    {
        return 'hello ' . $params['var'];
    }

    public function renderWidget($hookName, array $configuration)
    {
        if ($hookName == 'displayProductPriceBlock') {
            return false;
        }

        //$trainingArticle = new TrainingArticle(2);
        //
        //$trainingArticle->name[Language::getIdByIso('LT')] = 'name in lithuanian';AdminParentModulesSf
        //$trainingArticle->save();

        //$languages = Language::getLanguages();
        //foreach ($languages as $language) {
        //    $trainingArticle->name[$language['id_lang']] = 'name';
        //}
        //
        //$trainingArticle->description = 'Description';
        //$trainingArticle->type = 'type';
        //$trainingArticle->validateFieldsLang();
        //$trainingArticle->save();

        /** Redirect with notifications */
        //$this->context->controller->success[] = 'success';
        //$this->context->controller->redirectWithNotifications($this->context->link->getCategoryLink(1));
        smartyRegisterFunction($this->context->smarty, 'function', 'training_my_function', ['training', 'mySmartyFunction']);
        $this->context->smarty->assign($this->getWidgetVariables($hookName, $configuration));

        return $this->fetch($this->getTemplatePath('displayAdditionalInfo.tpl'));
    }



    public function getWidgetVariables($hookName, array $configuration)
    {
        /** @var ProductLazyArray $product */
        $product = $configuration['product'];
        return [
            'myVariable' => 'Value',
            'myArray' => Group::getGroups($this->context->language->id),
            'linkToWishList' => $this->context->link->getModuleLink($this->name, 'wishList'),
            'idProduct' => $product->getId()
        ];
    }

    public function getTabs()
    {
        return [
            [
                'name' => 'Training',
                'ParentClassName' => 'AdminParentModulesSf',
                'class_name' => self::CONTROLLER_PARENT,
                'visible' => false,
            ],
            [
                'name' => 'Configuration',
                'ParentClassName' => self::CONTROLLER_PARENT,
                'class_name' => self::CONTROLLER_CONFIGURATION
            ],
            [
                'name' => 'Articles',
                'ParentClassName' => self::CONTROLLER_PARENT,
                'class_name' => self::CONTROLLER_ARTICLES
            ]
        ];
    }


}
