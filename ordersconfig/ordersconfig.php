<?php
/**
* 2007-2021 PrestaShop
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
*  @copyright 2007-2021 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_ . '/ordersconfig/classes/objectModel/EmailConfig.php';


class Ordersconfig extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'ordersconfig';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'evolutive group';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;
    
        parent::__construct();

        $this->displayName = $this->l('Orders Config');
        $this->description = $this->l('add Orders Config for sending mail');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
       
    }

    /**
     * install
     * @return boolean
     */
    public function install()
    {
        
        include(dirname(__FILE__) . '/sql/install.php');
        return parent::install() &&     
         $this->registerHook('backOfficeHeader') && 
         $this->registerHook('displayAdminOrderLeft');
    }

    /**
     * uninstall
     * @return boolean
     */
    public function uninstall()
    {
       include(dirname(__FILE__) . '/sql/uninstall.php');
        return parent::uninstall() &&  $this->_uninstallTab();
    }


    /**
     * _installTab
     * @return boolean
     */
    protected function _installTab()
    {
        $languages = Language::getLanguages();
        $parent_tab = new Tab();
        $parent_tab->class_name = 'EMAILCONFG';
        $parent_tab->id_parent = 0;
        $parent_tab->module = $this->name;
        foreach ($languages as $language) {
            $parent_tab->name[$language['id_lang']] = $this->l('EMAILS CONFIGURATION');
        }

        $parent_tab->add();

        $tab_array = array(

            array(
                'class_name' => 'AdminEmailConfig',
                'name' => $this->l('CONFIGUARATION'),
            ),
            array(
                'class_name' => 'AdminEmailLog',
                'name' => $this->l('HISTORIQUE'),
            ),
        );

        foreach ($tab_array as $tab) {
            $new_tab = new Tab();
            $new_tab->class_name = $tab['class_name'];
            $new_tab->id_parent = (int)$parent_tab->id;
            $new_tab->module = $this->name;
            foreach ($languages as $language) {
                $new_tab->name[$language['id_lang']] = $tab['name'];
            }

            $new_tab->add();
        }
    }

    /**
     * _uninstallTab
     * @return boolean
     */
    protected function _uninstallTab()
    {
        $id_tabs = array();
        $tab = Tab::getInstanceFromClassName('AdminEmailConfig');
        $id_tabs[] = $tab->id_parent;
        $id_tabs[] = Tab::getIdFromClassName('AdminEmailLog');

        foreach ($id_tabs as $id_tab) {
            $tab = new Tab($id_tab);
            $tab->delete();
        }
        return true;
        
     
    }
    
    public function hookBackOfficeHeader()
    {
        
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
       
    }
    public function hookDisplayAdminOrderLeft($param){
        
        $current_order = new Order ((int)$param['id_order']);
        $log = EmailConfig::getLog((int)$param['id_order']);
        
        $customer_details = $current_order->getCustomer();
        $this->context->smarty->assign(
            array(
                'customer_details'=> $customer_details ,
                'log' => $log
            ));   

        return  $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'ordersconfig/views/templates/admin/mail.tpl');
       
    }
    
}
