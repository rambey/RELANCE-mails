<?php
/**
 * EmailConfig Prestashop Module
 * Module declaration
 *
 */

class EmailConfig extends ObjectModel
{

    public $id;
    public $subject;
    public $message;
    public $authorizedstatus;
    public $unauthorizedstatus;
    public $relance;
    public $template;
    
   

    public static $definition = [
        'table' => 'email_config',
        'primary' => 'id_config',
        'multilang' => true,
        'fields' => array(
            // Champs Standards
            'authorizedstatus' =>array('type' => self::TYPE_STRING, 'size' => 255),
            'unauthorizedstatus' =>array('type' => self::TYPE_STRING, 'size' => 255),
            'relance' =>array('type' => self::TYPE_STRING, 'size' => 255 , 'required' => true),
            'template' => array('type' => self::TYPE_STRING , 'size' => 255 , 'required' => true),

            //Champs langue
            'subject' => array('type' => self::TYPE_STRING , 'lang' => true ,'validate' => 'isName' , 'size' => 255 , 'required' => true),
            'message' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml' , 'required' => true),
        ),
    ];

    public static function authorizedStatus(){
        $sql = '
        SELECT `authorizedstatus`
        FROM `'._DB_PREFIX_.'email_config` 
        ' ;
        $row = Db::getInstance()->getRow($sql);
        return  $row['authorizedstatus'];

    }

    public static function unauthorizedStatus(){
        $sql = '
        SELECT `unauthorizedstatus`
        FROM `'._DB_PREFIX_.'email_config` 
        ' ;
        $row = Db::getInstance()->getRow($sql);
        return  $row['unauthorizedstatus'];

    }
    /**
     * return all email templates
     * @return void
     */
    public static function fetchAll($id_lang){
        $sql = '
        SELECT *
        FROM `'._DB_PREFIX_.'email_config`  em
        LEFT JOIN ' . _DB_PREFIX_ . 'email_config_lang  em_lg  ON em.id_config = em_lg.id_config
        WHERE em_lg.id_lang ='.$id_lang 
         ;
        $row = Db::getInstance()->executeS($sql);
        return  $row ; 
    }
    
    /**
     * return all order relate to a tamplate
     *
     * @return void
     */
    public static function getOrders($status, $date_from, $date_to){
        $sql = '
        SELECT *  FROM '._DB_PREFIX_.'orders 
        WHERE current_state  IN('.$status.') 
        AND `date_add` BETWEEN "'.$date_from.'" AND "'.$date_to.'"';
  

       return  Db::getInstance()->executeS($sql);
    }
    /**
     * get email by  order customer id
     * @param [type] $order_customer_id
     * @return void
     */
    public static function getEmailById($order_customer_id){
        $sql = '
        SELECT `email`
        FROM `'._DB_PREFIX_.'customer` 
        WHERE id_customer ='.$order_customer_id ;
        $row = Db::getInstance()->getRow($sql);
        return  $row['email'];
    }

    /**
     * get Log of sent mails
     * @param [type] $id_order
     * @return void
    */
    public static function getLog($id_order){
        $sql = '
        SELECT *
        FROM `'._DB_PREFIX_.'emails_log` 
        WHERE id_order ='.$id_order ;
        $row = Db::getInstance()->getRow($sql);
        
        return  $row;
    }
    
     /**
     * get Log of sent mails
     * @return void
     */
    public static function getLogs(){
        $sql = '
        SELECT *
        FROM `'._DB_PREFIX_.'emails_log`' ;
        $row = Db::getInstance()->getRow($sql);
        
        return  $row;
    }
    
    /**
     * get heures de reance
     * @param [type] $id_config
     * @return void
     */
    public static function getRelanceHour($id_config){
        $sql = '
        SELECT `relance`
        FROM `'._DB_PREFIX_.'email_config` 
        WHERE id_config ='.$id_config ;
        $row = Db::getInstance()->getRow($sql);
        return  $row['relance'];
    }
    
    /**
     * get template Name to be passed to Mail::send
     * @param [type] $id_config
     * @return void
     */
    public static function getTemplateName($id_config){
        $sql = '
        SELECT `template`
        FROM `'._DB_PREFIX_.'email_config` 
        WHERE id_config ='.$id_config ;
        $row = Db::getInstance()->getRow($sql);
        return  $row['template'];
    }
  
}
