<?php
/**
 * EmailLog Prestashop Module
 * Module declaration
 *
 */

class EmailLog extends ObjectModel
{

    public $id_order;
    public $customer_email;
    public $date_send;
    public $message;
  
    
    public static $definition = [
        'table' => 'emails_log',
        'primary' => 'id_order',
        'fields' => array(
            // Champs Standards
            'id_order' =>array('type' => self::TYPE_STRING, 'size' => 255),
            'customer_email' =>array('type' => self::TYPE_STRING, 'size' => 255),
            'date_send' =>array('type' => self::TYPE_DATE, 'size' => 255 ),
            'message' => array('type' => self::TYPE_HTML , 'size' => 255),

           
        ),
    ];


    
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
    
    
}
