<?php
/**
 * ordersconfig Prestashop Module
 * Module declaration
 */

require_once _PS_MODULE_DIR_ . '/ordersconfig/classes/objectModel/EmailConfig.php';
require_once _PS_MODULE_DIR_ . '/ordersconfig/classes/objectModel/MailVariables.php';
class AdminEmailConfigController extends ModuleAdminController
{

    /**
      * Instantiation of the class
      * Definition of basic mandatory parameters
      */
    public function __construct()
    {
        $this->bootstrap = true; 
        $this->table = EmailConfig::$definition['table']; 
        $this->identifier = EmailConfig::$definition['primary']; 
        $this->className = EmailConfig::class; 
        $this->lang = true; 

        
        parent::__construct();

        
        $this->fields_list = [
            'id_config' => [ 
                'title' => $this->module->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ],
            'subject' => [
                'title' => $this->module->l('subject'),
                'align' => 'left',
            ],
            'authorizedstatus' => [
                'title' => $this->module->l('authorized status'),
                'align' => 'left',
            ],
            'template' => [
                'title' => $this->module->l('template email'),
                'align' => 'left',
            ],
            'relance' => [
                'title' => $this->module->l('reminder(H)'),
                'align' => 'center',
            ],
            
        ];

        
        $this->addRowAction('edit');
        $this->addRowAction('delete');
    }

    public function renderForm()
    {
        $templates_options = array(
            array(
                'id_option' => 'SUIVI_COMMANDE',
                'name' => $this->l('suivi_commande')
            ),
            array(
                'id_option' => 'PROGRESSION_COMMANDE',
                'name' => $this->l('progression_commande')
            ),
        );
        $this->fields_form = [
            'legend' => [
                'title' => $this->module->l('Edit Sample'),
                'icon' => 'icon-cog'
            ],
            [
              'type' => 'hidden',
                'name' => 'id',
            ],
            'input' => [
                [
                    'type' => 'hidden', 
                    'name' => 'id', 
                ],
                [
                    'type' => 'text', 
                    'label' => $this->module->l('subject'), 
                    'name' => 'subject', 
                    'required' => true,
                    'lang' => true,
                ],
				 [
                    'type' => 'textarea',
                    'label' => $this->module->l('message'),
                    'name' => 'message',
                    'lang' => true,
		    'required' => true,
                    'autoload_rte' => true, 
                    'desc' => 'You can personalize the content of your emails using the tags in the next section.',
                ],
               
                [
                    'type' => 'select',
                    'label' => $this->trans('authorized order states'),
                    'hint' => $this->trans('Select the staus for which emails will be sent'),
                    'name' => 'authorizedstatus[]',
                    'class' => 'chosen',
                    'multiple' => true,
                    'required' => true, 
                    'options' => [
                        'query' => $this->getOrderStatus(),
                        'id' => 'id_order_state',
                        'name' => 'name',
                    ]  
                ],

                [
                    'type' => 'select',
                    'label' => $this->trans('unauthorized order states'),
                    'hint' => $this->trans('Select the staus for which emails will not be sent'),
                    'name' => 'unauthorizedstatus[]',
                    'class' => 'chosen',
                    'multiple' => true,
                    'required' => true, 
                    'options' => [
                        'query' => $this->getOrderStatus(),
                        'id' => 'id_order_state',
                        'name' => 'name',
                    ]  
                ],
                [
                    'type' => 'text', 
                    'label' => $this->module->l('send frequency'), 
                    'name' => 'relance', 
                    'required' => true,
                   
                    'hint' => $this->trans('Indicate the number of hours for which the email will be sent.'),
                    'col' => 1
                    
                ],
               [
                    'type' => 'select',
                    'required' => true,
                    'label' => $this->l('Template'),
                    'name' => 'template',
                    'desc' => $this->l('select the email template'),
                    'options' => [
                        'query' => $templates_options,
                        'id' => 'id_option',
                        'name' => 'name'
                    ],
                   
                    ],
            ],
           
            'submit' => [
                'title' => $this->l('Save'), 
            ]
        ];
     
        $this->context->smarty->assign(
            'query_tags', 
            MailVariables::getTags());   

        $content = $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'ordersconfig/views/templates/admin/configure.tpl');
        $list = parent::renderForm();
         return $list . $content;
    }

	private function getOrderStatus(){
		 return  OrderState::getOrderStates(Context::getContext()->language->id);
	}


 public function getFieldsValue($obj)
{
    foreach ($this->fields_form as $fieldset) {
        if (isset($fieldset['form']['input'])) {
            foreach ($fieldset['form']['input'] as $input) {
                if (!isset($this->fields_value[$input['name']])) {
                    if (isset($input['type']) && $input['type'] == 'shop') {
                       
                        if ($obj->id) {
                            $result = Shop::getShopById((int) $obj->id, $this->identifier, $this->table);
                            foreach ($result as $row) {
                                $this->fields_value['shop'][$row['id_' . $input['type']]][] = $row['id_shop'];
                            }
                        }
                    } elseif (isset($input['lang']) && $input['lang']) {
                      
                        foreach ($this->_languages as $language) {
                            $field_value = $this->getFieldValue($obj, $input['name'], $language['id_lang']);
                            if (empty($field_value)) {
                                if (isset($input['default_value']) && is_array($input['default_value']) && isset($input['default_value'][$language['id_lang']])) {
                                    $field_value = $input['default_value'][$language['id_lang']];
                                } elseif (isset($input['default_value'])) {
                                    $field_value = $input['default_value'];
                                }
                            }
                            $this->fields_value[$input['name']][$language['id_lang']] = $field_value;
                        }
                    }  else {
                        $field_value = $this->getFieldValue($obj, $input['name']);
                        if($input['name'] == 'authorizedstatus[]' || $input['name'] == 'unauthorizedstatus[]'){
                            $field_value = explode(",", $this->getFieldValue($obj , substr($input['name'],0,-2))) ;
                        }
                        if ($field_value === false && isset($input['default_value'])) {
                            $field_value = $input['default_value'];
                        }
                        $this->fields_value[$input['name']] = $field_value;
                    }
                }
            }
        }
    }
    return $this->fields_value;
}


    /**
     * Gestion de la toolbar
     */
    public function initPageHeaderToolbar()
    {

        //Bouton d'ajout
        $this->page_header_toolbar_btn['new'] = array(
            'href' => self::$currentIndex . '&add' . $this->table . '&token=' . $this->token,
            'desc' => $this->module->l('Add new Configuration'),
            'icon' => 'process-icon-new'
        );

        parent::initPageHeaderToolbar();
    }
	
    public function postProcess()
    {
	if (Tools::isSubmit('submitAddemail_config'))
	{
	    $_POST['authorizedstatus'] = implode(',', Tools::getValue('authorizedstatus'));
            $_POST['unauthorizedstatus'] = implode(',', Tools::getValue('unauthorizedstatus'));
	}
	parent::postProcess();
    }
}
