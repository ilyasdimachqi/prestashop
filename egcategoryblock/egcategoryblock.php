<?php
/*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}
include_once(dirname(__FILE__) . '/classes/EgCategoryBlockClass.php');

class EgCategoryBlock extends Module
{
    public function __construct()
    {
        $this->name = 'egcategoryblock';
        $this->tab = 'front_office_features';
        $this->author = 'Egio Digital';
        $this->version = '1.0.0';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->domain = 'Modules.Egcategoryblock.Egcategoryblock';
        $this->displayName = $this->l('Eg Category Block');
        $this->description = $this->l('Adds a category block to the home page.');
        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', [], $this->domain);
        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
    }

    public function install()
    {
        include(dirname(__FILE__) . '/sql/install.php');

        if (!parent::install()
            || !$this->registerHook('header')
            || !$this->registerHook('displayHome')
        ) {
            return false;
        }
        EgCategoryBlockClass::createTabs();
        return true;
    }

    public function uninstall()
    {
        include(dirname(__FILE__) . '/sql/uninstall.php');

        if (!parent::uninstall()) {
            EgCategoryBlockClass::removeTabs('AdminEgCategoryGeneral');
            EgCategoryBlockClass::removeTabs('AdminEgCategoryBlock');
            EgCategoryBlockClass::removeTabs('AdminEgCategoryBlockConf');
            return false;
        }

        return true;
    }

    public function hookHeader()
    {
        $this->context->controller->addCSS($this->_path . 'views/css/front.css');
        $this->context->controller->addJS($this->_path . 'views/js/front.js');

    }

    public function hookDisplayHome($params)
    {
        $maxDisplay = Configuration::get('EG_CATEGORIES_MAX_DISPLAY');
        $categoriesList = EgCategoryBlockClass::getCategories($maxDisplay);

        foreach ($categoriesList as &$category) {
            $category['image'] = _PS_BASE_URL_ . __PS_BASE_URI__ .
                'modules/' . $this->name . '/views/img/' . $category['image'];
        }

        $this->context->smarty->assign(array(
            'categoriesList' => $categoriesList,
            'base_url' => _PS_BASE_URL_,
            'id_lang' => (int)Context::getContext()->language->id
        ));

        return $this->display(__FILE__, 'views/templates/hook/category_listing.tpl');
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    public function getContent()
    {
        if (Tools::isSubmit('submitModule')) {
            Configuration::updateValue(
                'EG_CATEGORIES_MAX_DISPLAY',
                Tools::getValue('EG_CATEGORIES_MAX_DISPLAY')
            );
            $this->_clearCache('category_listing.tpl');
        }

        return $this->renderForm();
    }

    public function renderForm()
    {
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->submit_action = 'submitModule';
        $helper->toolbar_scroll = true;
        $helper->fields_value['EG_CATEGORIES_MAX_DISPLAY'] = Configuration::get('EG_CATEGORIES_MAX_DISPLAY');
        $form = $this->getConfigForm();

        return $helper->generateForm([$form]);
    }

    public function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->trans('Module Configuration'),
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->trans('Max CATEGORIES to Display'),
                        'name' => 'EG_CATEGORIES_MAX_DISPLAY',
                        'class' => 'fixed-width-xs',
                        'desc' => $this->trans('Enter the maximum number of CATEGORIES to display.'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->trans('Save'),
                ),
            ),
        );
    }
}

