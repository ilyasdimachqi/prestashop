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

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eg_category` (
    `id_eg_category` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `id_category` int(10) unsigned NOT NULL,
    `image` varchar(255) NOT NULL,
    `position` int(10) unsigned NOT NULL DEFAULT 0,
    `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
    PRIMARY KEY (`id_eg_category`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eg_category_lang` (
    `id_eg_category` int(10) unsigned NOT NULL,
    `id_lang` int(10) unsigned NOT NULL,
    `id_shop` int(10) unsigned NOT NULL DEFAULT 1,
    `title` varchar(255) NOT NULL,
    `subtitle` varchar(255) NOT NULL,
    `url` varchar(255) NOT NULL,
    PRIMARY KEY (`id_eg_category`, `id_shop`, `id_lang`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eg_category_shop` (
    `id_eg_category` int(10) unsigned NOT NULL,
    `id_shop` int(10) unsigned NOT NULL ,
    PRIMARY KEY (`id_eg_category`, `id_shop`),
    KEY `id_shop` (`id_shop`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;';



foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
