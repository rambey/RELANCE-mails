<?php
/**
 * ordersconfig Prestashop Module
 * Module declaration
 */

require_once _PS_MODULE_DIR_ . '/ordersconfig/classes/objectModel/EmailLog.php';

class AdminEmailLogController extends ModuleAdminController
{

    public function __construct()
    {
        $this->bootstrap = true; 
        $this->table = EmailLog::$definition['table']; 
        $this->identifier = EmailLog::$definition['primary']; 
        $this->className = EmailLog::class; 
        
        Context::getContext()->smarty->assign('cron_link', Context::getContext()->link->getModuleLink('ordersconfig', 'actions'));
        parent::__construct();

        
        $this->fields_list = [
            'id_order' => [ 
                'title' => $this->module->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'remove_onclick' => true
            ],
            'customer_email' => [
                'title' => $this->module->l('EMAIL'),
                'align' => 'left',
                'remove_onclick' => true
            ],
            'date_send' => [
                'title' => $this->module->l('DATE'),
                'align' => 'left',
                'remove_onclick' => true
            ],
            'message' => [
                'title' => $this->module->l('MESSAGE'),
                'align' => 'left',
                'remove_onclick' => true
            ],
            
        ];

        $this->addRowAction('delete');
    }

    public function renderList()
    {
        $content = $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'ordersconfig/views/templates/admin/cron.tpl');
        $list = parent::renderList();
        return $content .$list ;
    }
    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();
    }
    
    public function initToolbar() {
        parent::initToolbar();
        unset( $this->toolbar_btn['new'] );
    }	

}
