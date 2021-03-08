<?php

require_once _PS_MODULE_DIR_ . '/ordersconfig/classes/objectModel/EmailConfig.php';
require_once _PS_MODULE_DIR_ . '/ordersconfig/classes/controller/EmailContentController.php';
class EmailController
{
    /**
    * Main mail export function
    */
    public function exportMail()
    {
        $result = true;
        $templates = $this->getTemplates();
        
        foreach ($templates as $template) {

            $orders = $this->getordersByTemplate($template);
        
            foreach ($orders as $order) {
                /**
                 * @var $mail_info = array('subject' => '', 'content' => '', 'email' => '', ...)
                 */
                $mail_info = $this->getEmailContent($template, $order);
                if($this->sendEmail($mail_info)){
                     $this->saveLog($mail_info);
                }
            }
        }
        return $result;
    }

   /**
      * get all mail templates to be processed
    */
    private function getTemplates()
    {
        return EmailConfig::fetchAll(Context::getContext()->language->id);
    }

   /**
    * Get all the orders  associated with the current template
    */
    private function getOrdersByTemplate($template)
    {
        $status = EmailConfig::authorizedStatus();
        $email_config = EmailConfig::fetchAll(Context::getContext()->language->id);
        $current = new DateTime();
        $hoursToSubtract = (int)$email_config[0]['relance'];
        $current->sub(new DateInterval("PT{$hoursToSubtract}H"));
        $next = clone($current);
        $current->add(new DateInterval("PT1H"));
        $date_from = $next->format('Y-m-d H:i:s') ;  
        $date_to = $current->format('Y-m-d H:i:s');
        //var_dump($date_from);die;
        $orders = EmailConfig::getOrders($status,$date_from,$date_to);
        return $orders;
    }

    /**
    *insert into log
    */
    public static function saveLog($mail_info){

        $id_order  = $mail_info["order_id"] ;
        $customer_email = $mail_info["email_customer"];
        $date = new DateTime() ;
        $date_send = $date->format('Y-m-d H:i:s');
        $message =  Tools::safeOutput($mail_info["template_vars"]["{message_content}"]) ;
       
        $sql = 'INSERT INTO ' . _DB_PREFIX_ . 'emails_log (`id_order`, `customer_email`, `date_send`, `message`)
        VALUES(' . (int) $id_order. ', "' . $customer_email . '", "' . $date_send. '", "'.strip_tags(html_entity_decode($message)).'")' ;
        $res = Db::getInstance()->execute
        ($sql);
         return $res;
    }

    /**
    * Send the email with all the information
    */
    
    private function sendEmail($mail_info)
    {        
               $id_lang = (int)Context::getContext()->language->id;
               $iso = Language::getIsoById($id_lang);
                if (file_exists(_PS_MODULE_DIR_.'ordersconfig/mails/'.$iso.'/'.$mail_info["template_name"].'.txt') &&
                    file_exists(_PS_MODULE_DIR_.'ordersconfig/mails/'.$iso.'/'.$mail_info["template_name"].'.html')) {
                    
                        Mail::Send(
                            $mail_info["id_language"],
                            $mail_info["template_name"],
                            $mail_info["mail_subject"],
                            $mail_info["template_vars"],
                            (string) $mail_info["email_customer"],
                            null,
                            (string) $mail_info["ps_shop_email"],
                            (string) Configuration::get('PS_SHOP_NAME', null, null, $mail_info["id_shop"]),
                            null,
                            null,
                            _PS_MODULE_DIR_.'ordersconfig/mails/',
                            false,
                            $mail_info["id_shop"]
                        );
                        return true ;
                    
                }
    }


    /**
     * Build all variable to send mail to customer
     */
    private function getEmailContent($template, $order)
    {
        $email_content = new EmailContentController($template, $order);
        $content = $email_content->buildContent($template,$order);
        $id_lang = Context::getContext()->language->id ;
        $id_shop = (int) Context::getContext()->shop->id;
        return array(
            'id_language' => $id_lang ,
            'template_name' => $templatename = EmailConfig::getTemplateName($template['id_config']) ,
            'mail_subject' => $template["subject"],
            'id_shop' =>$id_shop ,
            'id_lang' => $id_lang ,
            'template_vars' => array(
                '{message_content}' =>  $content["message"],
            ),
            'email_customer' => EmailConfig::getEmailById($order["id_customer"]),
            'ps_shop_email' => (string)Configuration::get('PS_SHOP_EMAIL', null, null, $id_shop), 
            'shop_name' =>  (string) Configuration::get('PS_SHOP_NAME', null, null, $id_shop),
            'mails_dir' => _PS_MODULE_DIR_.'ordersconfig/mails/',
            'order_id' => $order["id_order"],
            
        );
    }

}