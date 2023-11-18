<?php
/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

class EgAlertStockAlertModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    public function init()
    {
        return parent::init();
    }

    public function initContent()
    {
        parent::initContent();
    }

    public function displayAjaxGetModal()
    {
        $this->context->smarty->assign(
            array(
                'idProduct' => Tools::getValue('id_product'),
                'idUser' => Tools::getValue('id_user'),
                'emailAlert' => Tools::getValue('email_user'),

            )
        );

        $response['modal'] = $this->module->display($this->module->name, 'views/templates/front/modal.tpl');
        die(json_encode($response));
    }

    public function displayAjaxSubmitAlert()
    {
        $response = [];
        $data = [];
        if (Tools::getValue('data')) {
            parse_str(Tools::getValue('data'), $data);
            if ($data) {
                if (!EgAlertStockClass::getExistingAlert($data['id_product'], $data['customerEmail'])) {
                    $alert = new EgAlertStockClass();
                    $alert->id_product = (int)$data['id_product'];
                    $alert->customer_id = (int)$data['id_user'];
                    $alert->email = $data['customerEmail'];
                    $alert->save();
                    $response['status'] = 1;
                    $response['id_product'] = $data['id_product'];
                    $response['message'] = $this->module->l('Your request has been submitted successfully.');
                } else {
                    $response['status'] = 0;
                    $response['message'] = $this->module->l('Alert already exists for this product and email.');
                }
            }
        }

        die(json_encode($response));
    }

}
