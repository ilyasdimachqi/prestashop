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
include_once(dirname(__FILE__) . '/classes/EgAlertStockClass.php');

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class EgAlertStock extends Module
{

    protected $domain;
    private $templateFile;
    public function __construct()
    {
        $this->name = 'egalertstock';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Egio Digital';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Eg Alert Stock');
        $this->description = $this->l('Custom module for stock alerts.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
        $this->domain = 'Modules.EgAlertStock.EgAlertStock';
        $this->templateFile = 'module:egalertstock/views/templates/hook/button.tpl';
    }

    public function install()
    {
        include(dirname(__FILE__) . '/sql/install.php');

        if (!parent::install()) {
            $this->registerHook('actionCustomerAccountAdd') &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('displayProductAdditionalInfo') &&
            $this->registerHook('displayMonMessage');
            return false;
        }
        $this->createTabs();

        return true;
    }

    public function uninstall()
    {
        include(dirname(__FILE__) . '/sql/uninstall.php');

        if (!parent::uninstall()) {
            $this->removeTabs('AdminEgAlertStockGeneral');
            $this->removeTabs('AdminEgAlertStock');

            return false;
        }

        return true;
    }

    public function createTabs()
    {
        $idParent = (int)Tab::getIdFromClassName('AdminEgDigital');
        if (empty($idParent)) {
            $parent_tab = new Tab();
            $parent_tab->class_name = 'AdminEgDigital';
            $parent_tab->module = $this->name;
            $parent_tab->icon = 'library_books';
            foreach (Language::getLanguages(true) as $lang) {
                $parent_tab->name[$lang['id_lang']] = $this->trans(
                    'Modules EGIO',
                    [],
                    'Modules.EgAlertStock'
                );
            }
            $parent_tab->id_parent = 0;
            $parent_tab->add();
        }

        $tab = new Tab();
        $tab->class_name = 'AdminEgAlertStockGeneral';
        $tab->module = $this->name;
        $tab->icon = 'library_books';
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans('Alert Stock management', array(), $this->domain);
        }
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminEgDigital');
        $tab->add();

        $tab = new Tab();
        $tab->class_name = 'AdminEgAlertStock';
        $tab->module = $this->name;
        $tab->icon = 'library_books';
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans(
                'Alert Stock management',
                [],
                'Modules.EgAlertStock'
            );
        }
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminEgAlertStockGeneral');
        $tab->add();


        return true;
    }
    public function removeTabs($class_name)
    {
        if ($tab_id = (int)Tab::getIdFromClassName($class_name)) {
            $tab = new Tab($tab_id);
            $tab->delete();
        }
        return true;
    }

    public function hookActionCustomerAccountAdd($params)
    {
        // newCustomer
        $customer = $params['newCustomer'];
        $customer->lastname = 'TestMonModule';
        $customer->save();
    }

    public function hookDisplayProductAdditionalInfo($products)
    {
        return $this->display(__FILE__, 'views/templates/admin/monaffichage.tpl');
    }

    public function hookDisplayMonMessage()
    {
        return $this->display(__FILE__, 'views/templates/admin/monmessage.tpl');
    }

}
