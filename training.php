<?php

use Invertus\Training\SearchProvider\TrainingSearchProvider as SearchProvider;
use PrestaShop\PrestaShop\Adapter\Presenter\Product\ProductLazyArray;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class training extends Module implements WidgetInterface
{
    private const HOOK_TYPE_WEIGHT = 'weight';

    public function __construct()
    {
        $this->name = 'training';
        $this->version = '1.0.0';
        $this->author = 'Invertus';

        parent::__construct();

        $this->displayName = $this->l('Training');
        $this->description = $this->l('Training description');
        $this->ps_versions_compliancy = array('min' => '1.7.7.0', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        if (!$this->registerHooks()) {
            return false;
        }

        return true;
    }

    private function registerHooks()
    {
        $hooks = [
            'displayProductAdditionalInfo',
            'displayProductPriceBlock',
            'productSearchProvider',
            'actionFrontControllerSetMedia',
            'additionalCustomerFormFields',
            'validateCustomerFormFields'
        ];

        foreach ($hooks as $hook) {
            if (!$this->registerHook($hook)) {
                return false;
            }
        }
        return true;
    }

    public function hookAdditionalCustomerFormFields()
    {
        $formField = (new FormField())
            ->setName('repeat_password')
            ->setType('password')
            ->setLabel($this->l('Repeat password'))
            ->setRequired(true);

        return array($formField);
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

    public function mySmartyFunction($params)
    {
        return 'hello ' . $params['var'];
    }

    public function renderWidget($hookName, array $configuration)
    {
        if ($hookName == 'displayProductPriceBlock') {
            return false;
        }

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
}
