<?php

class EmailContentController
{
    private $template;
    private $order;

    public function __construct($template, $order)
    {
        $this->template = $template;
        $this->order = $order;
    }

    public function buildContent($template ,$order)
    {
      
        $order_tags = self::getTagsByTable('orders');
        foreach($order_tags as $tag){
            $column = $tag['column'];
            if($tag['tag'] == '{date_livraison}'){
            $order[$column] = (Order::getdeliverytime((int)$order["id_order"]))? Order::getdeliverytime((int)$order["id_order"]):"N/A" ;            }
            $this->template["message"] = str_replace($tag['tag'] , $order[$column] , $this->template["message"]);  
        }
       
            $customer_details = Db::getInstance()->executeS('
                    SELECT c.firstname, c.lastname, c.id_lang, gl.name as gender_name
                    FROM `' . _DB_PREFIX_ . 'orders` o
                    LEFT JOIN ' . _DB_PREFIX_ . 'customer c ON o.id_customer = c.id_customer
                    LEFT JOIN ' . _DB_PREFIX_ . 'gender_lang gl ON c.id_gender = gl.id_gender
                    WHERE o.id_order = ' . (int)$order["id_order"]);
     

        $customer_tags = self::getTagsByTable('customer');
        foreach($customer_tags as $tag){
            $column = $tag['column'];
            // exemple $customer['firstname'] ,  $customer['lastname']
            $this->template = str_replace($tag['tag'] , $customer_details[0][$column], $this->template);
        }
        $shop_name  = '<a href="'.Context::getContext()->shop->getBaseUrl().'" style="text-decoration: underline; color: #25b9d7; font-size: 16px; font-weight: 600;">'.Configuration::get('PS_SHOP_NAME').'</a>';
        $this->template['message'] = str_replace('{shop_name}' , $shop_name, $this->template);
        $logo = Configuration::get('PS_LOGO_MAIL');
        if (!$logo || $logo == '' || !file_exists(_PS_IMG_DIR_.$logo)) {
            $logo = Configuration::get('PS_LOGO');
        }
        $logo = Context::getContext()->shop->getBaseUrl() . 'img/' . $logo;
        $this->template['message'] = str_replace('%7Bshop_logo%7D', $logo,  $this->template['message']);
        
        return $this->template['message'];
    }
    
     /**
     * retouner tous les tags en se basant sur la table concerné 
     * $table_name equivaut champ relatedtable côte BD
     * @return void
     */
    private function getTagsByTable($table_name){
        $sql = '
        SELECT *
        FROM `'._DB_PREFIX_.'mail_variables` 
        WHERE relatedtable = "'.$table_name.'"' ;
        $row = Db::getInstance()->executeS($sql);
        return  $row ; 
    }
}