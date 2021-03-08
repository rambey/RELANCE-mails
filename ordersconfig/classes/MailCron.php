<?php
/**
 * MailCron Prestashop Module
 * Module declaration
 *
 */
require_once _PS_MODULE_DIR_ . '/ordersconfig/classes/MailVariables.php';
require_once _PS_MODULE_DIR_ . '/ordersconfig/classes/EmailConfig.php';
class MailCron extends ObjectModel
{

    private static function authorizedStatus(){
        $sql = '
        SELECT `authorizedstatus`
        FROM `'._DB_PREFIX_.'email_config` 
        ' ;
        $row = Db::getInstance()->getRow($sql);
        return  $row['authorizedstatus'];
       
    }

    private static function unauthorizedStatus(){
        $sql = '
        SELECT `unauthorizedstatus`
        FROM `'._DB_PREFIX_.'email_config` 
        ' ;
        $row = Db::getInstance()->getRow($sql);
        return  $row['unauthorizedstatus'];
       
    }
 

   public static function sendMail()
   {
       $context = Context::getContext();
       $orders_details = Order::getOrdersWithInformations();

        $authorized_status = self::authorizedStatus();
        $unauthorizedStatus = self::unauthorizedStatus();
        
       foreach ($orders_details as $key =>$order_detail) {

           $customer_email =  $order_detail['email'] ;
           $id_shop = (int) $order_detail['id_shop'];
           $id_lang = (int) $order_detail['id_lang'];
           $context->shop->id = $id_shop;
           $context->language->id = $id_lang;
           $date_add = $order_detail["date_add"];

           //fetch message content to template 
           $emailconfig = new EmailConfig((int)1) ;
           $template_vars = array(
            '{message_content}' => MailVariables::setTemplate($emailconfig->message),
           );
        die('changed');
           $iso = Language::getIsoById($id_lang);
         
           if($order_detail["id_order"] == "2479"){

                if (file_exists(_PS_MODULE_DIR_.'ordersconfig/mails/'.$iso.'/order_tracking.txt') &&
                file_exists(_PS_MODULE_DIR_.'ordersconfig/mails/'.$iso.'/order_tracking.html')) {
                try {
                    Mail::Send(
                        $id_lang,
                        'order_tracking',
                        Mail::l('Tracking your order', $id_lang),
                        $template_vars,
                        (string) $customer_email,
                        null,
                        (string) Configuration::get('PS_SHOP_EMAIL', null, null, $id_shop),
                        (string) Configuration::get('PS_SHOP_NAME', null, null, $id_shop),
                        null,
                        null,
                        _PS_MODULE_DIR_.'ordersconfig/mails/',
                        false,
                        $id_shop
                    );
                    return true ;
                } catch (Exception $e) {
                        $e->getMessage();
                    return false;

                }
            }
           }
           
          
       }
   }

   
}
