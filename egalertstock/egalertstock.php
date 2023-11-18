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

class EgAlertStock extends Module
{
    protected $domain;
    private $oldQuantities = [] ;
    public function __construct()
    {
        $this->name = 'egalertstock';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Egio Digital';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->domain = 'Modules.Egalertstock.Egalertstock';
        $this->displayName = $this->trans('Eg Alert Stock', [], $this->domain);
        $this->description = $this->trans('Custom module for stock alerts.', [], $this->domain);
        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', [], $this->domain);
        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
        $this->loadOldQuantities();
    }

    public function install()
    {
        include(dirname(__FILE__) . '/sql/install.php');

        if (parent::install()
            && $this->registerHook('displayHeader')
            && $this->registerHook('displayProductAdditionalInfo')
            && $this->registerHook('actionProductUpdate')
            && $this->createTabs()
        ) {
            return true;
        }
        return false;
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

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    public function hookHeader()
    {
        Media::addJsDef(
            [
                'eg_alert_link' => $this->context->link->getModuleLink($this->name, 'alert')
            ]
        );
        $this->context->controller->addCSS($this->_path . 'views/css/front.css');
        $this->context->controller->addJS($this->_path . 'views/js/front.js');
    }

    public function hookDisplayProductAdditionalInfo($params)
    {
        $productId = (int)$params['product']['id_product'];
        $user = $this->context->customer;
        $userId = $user->isLogged() ? $user->id : 0;
        $emailAlert = $user->isLogged() ? $user->email : '';
        $stock = StockAvailable::getQuantityAvailableByProduct($productId);

        if ($stock <= 0) {

            $smartyData = [
                'eg_alert_link' => $this->context->link->getModuleLink($this->name, 'alert'),
                'idProduct' => $productId,
                'emailAlert' => $emailAlert,
                'idUser' => $userId,
            ];
            $this->context->smarty->assign($smartyData);
            return $this->display(__FILE__, 'views/templates/hook/button.tpl');
        }

        return '';
    }

    public function hookActionProductUpdate($params)
    {
        $productId = (int)$params['id_product'];
        $oldQuantity = isset($this->oldQuantities[$productId]) ? $this->oldQuantities[$productId] : 0;
        $newQuantity = StockAvailable::getQuantityAvailableByProduct($productId);

        if ($oldQuantity === 0 && $newQuantity > 0) {
            $subscribers = EgAlertStockClass::getSubscribers($productId);

            foreach ($subscribers as $subscriber) {
                if (!EgAlertStockClass::isNotificationSent($productId, $subscriber['email'])) {
                    $this->sendStockNotificationEmail($subscriber['email'], $productId);
                    EgAlertStockClass::updateNotificationSentStatus($productId, $subscriber['email']);
                }
            }
        }
        $this->oldQuantities[$productId] = $newQuantity;
    }

    private function loadOldQuantities()
    {
        $productIds = Db::getInstance()->executeS('SELECT id_product FROM ' . _DB_PREFIX_ . 'product');

        foreach ($productIds as $productId) {
            $productId = (int)$productId['id_product'];
            $this->oldQuantities[$productId] =
                StockAvailable::getQuantityAvailableByProduct($productId, 0);
        }
    }



    protected function sendStockNotificationEmail($userEmail, $productId)
    {
        $product = new Product($productId);

        $subject = 'Product Back in Stock Notification';
        $message = 'The product '.$product->name[1].' is back in stock.';

        mail($userEmail, $subject, $message);

    }



    public function createTabs()
    {
        $idParent = (int) Tab::getIdFromClassName('AdminEgDigital');
        if (empty($idParent)) {
            $parent_tab = new Tab();
            $parent_tab->class_name = 'AdminEgDigital';
            $parent_tab->module = $this->name;
            $parent_tab->icon = 'library_books';
            foreach (Language::getLanguages(true) as $lang) {
                $parent_tab->name[$lang['id_lang']] = $this->trans(
                    'Modules EGIO',
                    [],
                    $this->domain
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
            $tab->name[$lang['id_lang']] = $this->trans('Alert Stock management', [], $this->domain);
        }
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminEgDigital');
        $tab->add();

        $tab = new Tab();
        $tab->class_name = 'AdminEgAlertStock';
        $tab->module = $this->name;
        $tab->icon = 'library_books';
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans(
                'Alert Stock management',
                [],
                $this->domain
            );
        }
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminEgAlertStockGeneral');
        $tab->add();

        return true;
    }

    public function removeTabs($class_name)
    {
        if ($tab_id = (int) Tab::getIdFromClassName($class_name)) {
            $tab = new Tab($tab_id);
            $tab->delete();
        }
        return true;
    }
}
