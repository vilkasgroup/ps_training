<?php

use PrestaShop\PrestaShop\Adapter\SymfonyContainer;

class training extends Module
{
    const CONTROLLER_CONFIG = 'AdminTrainingConfiguration';
    const CONTROLLER_ARTICLE = 'AdminTrainingArticle';


    const CONTROLLER_PARENT = 'AdminTrainingParent';


    public function __construct()
    {
        $this->name = 'training';
        $this->tab = 'analytics_stats';
        $this->version = '1.0.1';
        $this->author = 'Invertus';

        parent::__construct();

        $this->displayName = $this->l('Training');
        $this->description = $this->l('Training module');
        $this->ps_versions_compliancy = array('min' => '1.7.2.0', 'max' => _PS_VERSION_);
    }

    /**
     * Should always return true or false
     * Should probably move to another service for cleanness
     * You can't services from your services.yml here because module is not installed yet.
     */
    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        if (!$this->registerHook('displayProductAdditionalInfo')) {
            return false;
        }
        if (!$this->registerHook('productSearchProvider')) {
            return false;
        }
        if (!$this->registerHook('actionFrontControllerSetMedia')) {
            return false;
        }
        if (!$this->registerHook('displayAdminOrder')) {
            return false;
        }

        if (!$this->createTables()) {
            return false;
        }

        return true;
    }

    /**
     * Symfony container used in new functions in PrestaShop
     * Works only in backoffice
     * Can be used to retrieve new PrestaShop services.
     */
    public function getContainer()
    {
        return SymfonyContainer::getInstance();
    }
    public function hookDisplayAdminOrder($params)
    {
        /**
         * Using twig service to display twig template
         */
        $twig = $this->getContainer()->get('twig');
        return $twig->render(
            '@Modules/training/views/templates/admin/adminOrder.html.twig',
            [
                'id_order' => (string) $params['id_order']
            ]
        );
//        $twig = $this->context->controller->get('twig');
//        dump($twig);
    }
//
//    public function uninstall()
//    {
//        $sql = DB::getInstance()->execute('DROP TABLE '._DB_PREFIX_. 'training_article');
//        parent::uninstall();
//    }


    /**
     * Function to create tables using Db::getInstance()->execute which executes any sql code.
     * Make sure to delete table on uninstall when you create them
     * @return bool
     */
    public function createTables()
    {
        $return =  Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS '._DB_PREFIX_. 'training_article' . '(
            `id_training_article` INTEGER(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `type` varchar(100) DEFAULT NULL
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

    /**
     * Hook is required to tell prestashop that it should use diffrent search  provided then the default
     * If you don't want to replace search provider from prestashop return false
     */
    public function hookProductSearchProvider($params)
    {
        /** @var \PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery $query */
        $query = $params['query'];

        if ($query->getIdCategory()) {
            $searchProvider = $this->context->controller->getContainer()->get('invertus.training.product.search_provider');

            return $searchProvider;
        } else {
            return null;
        }
    }

    /**
     * Hook is required for setting of javascript or css in prestashop
     * Use hookActionAdminControllerSetMedia for admin
     * For admin it would be $this->context->controller->addJS() instead.
     * If you want to only have js/css in specific controller you can always do something like this
     * if ($this->context->controller instanceof AdminOrderController) {
     * and then set js/css
     */
    public function hookActionFrontControllerSetMedia()
    {
        $this->context->controller->registerJavascript('training-custom-event-catcher', 'modules/training/views/js/eventCatcher.js');
    }


    public function hookDisplayProductAdditionalInfo($params)
    {

        /**
         * some examples of what can be done with Db::getInstance or DbQuery
         */
      //  Configuration::updateValue('TRAINING_ARTICLES_PER_PAGE', 5);
//        $query = new DbQuery();
//        $db = Db::getInstance();
//        $query->select('type');
//        $query->from('training_article');
//        $query->where('id_training_article = 2');
//        dump($db->getValue($query));
//        $db->delete('training_article', 'id_training_article = 2 ');
//        $db->update('training_article', [
//            'type' => 'super_type'
//        ], 'id_training_article = 2 ');


        /**
         * Examples how object model can be used
         */
//        $article = new TrainingArticle(2);
//
//        $article->delete();
//
//        $article->name[Language::getIdByIso('FI')] = 'Name in finish';
//
//        $article->description[Language::getIdByIso('FI')] = 'Description in finish';
//        $article->save();

        /**
         * Assiging variables to smarty so they can be used in template
         */
        $this->context->smarty->assign(
            [
                'id_product' => $params['product']->getId(),
                'link_to_front_controller' => $this->context->link->getModuleLink($this->name, 'customPage')
            ]
        );

        /**
         * you can set errors to your controlelr and they will be displayed automatically, if you want to see them in next page
         * you can use function redirectWithNotifications to that page
         */
//        $this->context->controller->errors[] = 'Some error';
//        $this->context->controller->success[] = 'Some error';
//        $this->context->controller->warnings[] = 'Some error';
//
//
//        $this->context->controller->redirectWithNotifications($this->context->link->getModuleLink('training', 'customPage'));

        /**
         * how to get template.
         * $this->getTemplatePath will only work if template is in views/templates/hook
         */
        return $this->fetch($this->getTemplatePath('productAdditionalInfoHook.tpl'));
    }

    /**
     * getContent returns content which displayed once you click configure on module
     * Configure module won't be visible if you don't have this function
     * Can return HTML (or template) here or you can redirect somewhere else
     */
    public function getContent()
    {
        Tools::redirectAdmin($this->getContainer()->get('router')->generate('training_admin_article'));

        Tools::redirectAdmin($this->context->link->getAdminLink(self::CONTROLLER_CONFIG));
    }

    /**
     * Function used to register tabs in prestashop. ParentClassName is the parent tab. Could be any Prestashop or your own tab
     */
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
                'class_name' => self::CONTROLLER_CONFIG,
            ],
            [
                'name' => 'Article',
                'ParentClassName' => self::CONTROLLER_PARENT,
                'class_name' => self::CONTROLLER_ARTICLE,
            ]
        ];
    }


    /**
     * This is used by carrier modules in order to crete carriers that are tied to your module
     * Module class needs to extend CarrierModule instead of Module
     */

    /** This is logic to create carriers in PrestasShop in way that they use this modules functions */
    public function createCarriers()
    {
        $carrier = new Carrier();
        $carrier->active = true;
        $carrier->name = 'Training Carrier';
        $delay = [];
        foreach (Language::getLanguages() as $language) {
            $delay[$language['id_lang']] = 'Translated name';
        }
        $carrier->delay = $delay;
        $carrier->is_module = 1;
        $carrier->need_range = 1;
        $carrier->shipping_external = 1;
        $carrier->external_module_name = $this->name;


        $groupsForCarrier = [];
        foreach (Group::getGroups($this->context->language->id) as $group) {
            $groupsForCarrier[] = $group['id_group'];
        }
        $carrier->save();

        /**
         * Should be set after carrier is saved because this func tion required carrier id
         * This sets groups for carrier so it can be viewed by all customer groups in PrestaShop adjust if nessecery
         */
        $carrier->setGroups($groupsForCarrier);

        /**
         * adds base range for carrier could be RangeWeight or RangePrice
         * PrestaShop needs at least one range for carrier to work, more about ranges you can find out in carrier shipping locations and costs tab
         */
        $weightRange = new RangeWeight();
        $weightRange->id_carrier = $carrier->id;
        $weightRange->delimiter1 = 0;
        $weightRange->delimiter2 = 999999999;
        $weightRange->add();

        /**
         * adds carrier to all of the existing zones
         */
        foreach(Zone::getZones() as $zone) {
            $carrier->addZone($zone['id_zone']);
        }

        return true;
    }

    /**
     * returns shipping costs no matter what is defined in carrier settings, return false if you don't want customer to see carrier
     * @param $params
     * @param $shipping_cost
     * @return int
     */
    public function getOrderShippingCost($params, $shipping_cost)
    {
        return 10;
    }

    public function getOrderShippingCostExternal($params)
    {
        return 10;
    }
}
