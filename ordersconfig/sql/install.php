<?php
/**
* 2007-2019 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
$sql = array();


$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'email_config` (
    `id_config` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `authorizedstatus` varchar(255) DEFAULT NULL,
                `unauthorizedstatus` varchar(255) DEFAULT NULL,
                `relance` varchar(255) DEFAULT NULL,
                `template` varchar(255) NOT NULL,
                PRIMARY KEY (`id_config`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';



$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'email_config_lang`(
             `id_config` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `id_lang` int(11) NOT NULL,
              `subject` varchar(255) NOT NULL,
              `message` text NOT NULL,
              PRIMARY KEY (`id_config`,`id_lang`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'mail_variables` (
             `id_tag` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `tag` varchar(255) NOT NULL,
              `column` varchar(255) NOT NULL,
              `relatedtable` varchar(255) NOT NULL,
              `condition` varchar(255) NOT NULL,
              `message` text  NOT NULL,
              PRIMARY KEY (`id_tag`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'emails_log` (
    `id_order` int(11) unsigned NOT NULL AUTO_INCREMENT,
     `customer_email` varchar(255) NOT NULL,
     `date_send` DATETIME NOT NULL,
     `message` text NOT NULL,
     PRIMARY KEY (`id_order`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';


$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'emails_log` (
    `id_order` int(11) unsigned NOT NULL AUTO_INCREMENT,
     `customer_email` varchar(255) NOT NULL,
     `date_send` DATETIME NOT NULL,
     `message` text NOT NULL,
     PRIMARY KEY (`id_order`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

 $sql[] = 'INSERT INTO  `'. _DB_PREFIX_ .'mail_variables` (`tag`, `column`, `relatedtable`, `condition`, `message`) VALUES
            ("{firstname}", "firstname", "customer", "", "du client"),
            ("{lastname}", "lastname", "customer","", "du client"),
            ("{id_order}", "id_order", "orders", "", "de la commande"),
            ("{ref_order}", "reference", "orders", "", "de la commande"),
            ("{date_livraison}", "delivery_date", "orders", "", "de la commande"),
            ("{shop_logo}", "PS_LOGO", "Configuration", "", "du shop"),
            ("{shop_name}", "PS_SHOP_NAME ", "Configuration", "", "du shop")
            ';
foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
