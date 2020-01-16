<?php

use Invertus\Training\TrainingProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

class training extends Module
{
    const CONTROLLER_CONFIG = 'AdminTrainingConfiguration';

    const CONTROLLER_PARENT = 'AdminTrainingParent';


    public function __construct()
    {
        $this->name = 'training';
        $this->tab = 'analytics_stats';
        $this->version = '1.0.0';
        $this->author = 'Invertus';

        parent::__construct();

        $this->displayName = $this->l('Training');
        $this->description = $this->l('Training module');
        $this->ps_versions_compliancy = array('min' => '1.7.2.0', 'max' => _PS_VERSION_);
    }

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

        if (!$this->createTables()) {
            return false;
        }

        return true;
    }
//
//    public function uninstall()
//    {
//        $sql = DB::getInstance()->execute('DROP TABLE '._DB_PREFIX_. 'training_article');
//        parent::uninstall();
//    }


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

    public function hookActionFrontControllerSetMedia()
    {
        $this->context->controller->registerJavascript('training-custom-event-catcher', 'modules/training/views/js/eventCatcher.js');
    }


    public function hookDisplayProductAdditionalInfo($params)
    {

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


//        $article = new TrainingArticle(2);
//
//        $article->delete();
//
//        $article->name[Language::getIdByIso('FI')] = 'Name in finish';
//
//        $article->description[Language::getIdByIso('FI')] = 'Description in finish';
//        $article->save();
        $this->context->smarty->assign(
            [
                'id_product' => $params['product']->getId(),
                'link_to_front_controller' => $this->context->link->getModuleLink($this->name, 'customPage')
            ]
        );
        return $this->fetch($this->getTemplatePath('productAdditionalInfoHook.tpl'));
    }

    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink(self::CONTROLLER_CONFIG));
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
                'class_name' => self::CONTROLLER_CONFIG,
            ]
        ];
    }
}
