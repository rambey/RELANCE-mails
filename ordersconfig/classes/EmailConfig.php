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
    public $datesend;
    public $authorizedstatus;
    public $unauthorizedstatus;
    
   

    public static $definition = [
        'table' => 'email_config',
        'primary' => 'id_config',
        'multilang' => true,
        'fields' => array(
            // Champs Standards
            'datesend' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'authorizedstatus' =>array('type' => self::TYPE_STRING, 'size' => 255),
            'unauthorizedstatus' =>array('type' => self::TYPE_STRING, 'size' => 255),
            //Champs langue
            'subject' => array('type' => self::TYPE_STRING , 'lang' => true ,'validate' => 'isName' , 'size' => 255 , 'required' => true),
            'message' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml' , 'required' => true),
      
        ),
    ];
}
