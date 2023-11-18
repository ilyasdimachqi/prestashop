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

class EgAlertStockAlertStockModuleFrontController extends ModuleFrontController
{

    public $ssl = true;

    public function initContent()
    {
        parent::initContent();

        // Récupérer l'ID du produit depuis la requête
        $productId = (int)Tools::getValue('product_id');

        // Logique pour afficher le popup et gérer les notifications
        $this->context->smarty->assign('productId', $productId);

        // Charger les scripts et styles nécessaires
        $this->context->controller->addJS($this->module->getPathUri() . 'views/js/egalertstockpopup.js');
        $this->context->controller->addCSS($this->module->getPathUri() . 'views/css/egalertstockpopup.css');

        // Afficher le template
        $this->setTemplate('module:egalertstock/views/templates/front/popup.tpl');
    }
}
