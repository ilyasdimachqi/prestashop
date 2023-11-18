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
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
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

class EgAlertStockClass extends ObjectModel
{
    public $id_alert;
    public $id_product;
    public $email;
    public $customer_id;
    public $notification_sent;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'egalertstock',
        'primary' => 'id_alert',
        'fields' => array(
            'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'email' => array('type' => self::TYPE_STRING, 'validate' => 'isEmail', 'required' => true),
            'customer_id' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),

        ),
    );

    public static function getExistingAlert($productId, $email)
    {
        $sql = 'SELECT id_alert FROM ' . _DB_PREFIX_ . 'egalertstock WHERE id_product = '
            . (int)$productId . ' AND email = \'' . pSQL($email) . '\'';
        $result = Db::getInstance()->getValue($sql);

        return $result;
    }

    public static function getSubscribers($productId)
    {
        $sql = 'SELECT email FROM ' . _DB_PREFIX_ . 'egalertstock WHERE id_product = ' . (int)$productId;
        return Db::getInstance()->executeS($sql);
    }

    public static function updateNotificationSentStatus($productId, $customerEmail)
    {
        $sql = 'UPDATE ' . _DB_PREFIX_ . 'egalertstock
                SET notification_sent = 1
                WHERE id_product = ' . (int)$productId . '
                AND email = \'' . pSQL($customerEmail) . '\'';

        return Db::getInstance()->execute($sql);
    }

    public static function isNotificationSent($productId, $email)
    {
        $sql = 'SELECT notification_sent
                FROM ' . _DB_PREFIX_ . 'egalertstock
                WHERE id_product = ' . (int)$productId . '
                AND email = \'' . pSQL($email) . '\'';

        $result = Db::getInstance()->getValue($sql);

        // Si la valeur est 1, cela signifie que la notification a déjà été envoyée
        return $result === '1';
    }


}
