<?php
/**
 * MailVariables Prestashop Module
 * Module declaration
 *
 */

class MailVariables extends ObjectModel
{

    public $id_tag;
    public $tag;
    /**
     * $column
     * @var [type]
     * associated column
     */
    public $column;
    public $relatedtable;
    public $condition;
    public $message;
    

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'mail_variables',
        'primary' => 'id_tag',
        'fields' => array(
            'id_tag' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'tag' => array('type' => self::TYPE_STRING, 'required' => true),
            'column' => array('type' => self::TYPE_STRING, 'required' => true),
            'relatedtable' => array('type' => self::TYPE_STRING, 'required' => true),
            'condition' => array('type' => self::TYPE_STRING, 'required' => true),
            'message' => array('type' => self::TYPE_STRING),
        ),
    );
   

    public static function getTags(){
        $sql = '
        SELECT *
        FROM `'._DB_PREFIX_.'mail_variables` 
        ' ;
        $row = Db::getInstance()->executeS($sql);
        return  $row ; 
    }

    public static function setTemplate($content){

            $order_details = Db::getInstance()->executeS('
                SELECT *  FROM '._DB_PREFIX_.'orders
                ');
                $id_order = $order_details['id_order'] ;
       

        $order_tags = self::getTagsByTable('orders');
        foreach($order_tags as $tag){
            $column = $tag['column'];
            replaceTags($tag['tag'] , $order_details.'['.$column.']' , $content);
        }

        if (is_null($id_order)) {
            $customer = array(
                'firstname' => 'Evolutive',
                'lastname' => 'Group',
                'id_lang' => $id_lang,
                'gender_name' => 'M.'
            );
        } else {
            $customer = Db::getInstance()->executeS('
                    SELECT c.firstname, c.lastname, c.id_lang, gl.name as gender_name
                    FROM `' . _DB_PREFIX_ . 'orders` o
                    LEFT JOIN ' . _DB_PREFIX_ . 'customer c ON o.id_customer = c.id_customer
                    LEFT JOIN ' . _DB_PREFIX_ . 'gender_lang gl ON c.id_gender = gl.id_gender
                    WHERE o.id_order = ' . (int)$id_order . ' AND c.id_lang = ' . (int)$id_lang);
        }
        $customer_tags = self::getTagsByTable('customer');
        foreach($customer_tags as $tag){
            $column = $tag['column'];
            // exemple $customer['firstname'] ,  $customer['lastname'] 
            replaceTags($tag['tag'] , $customer.'['.$column.']' , $content);
        }
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

    /**
     * Remplacer un tag par la colonne asscociée
     * @param [type] $tag
     * @param [type] $column
     * @param [type] $content
     * @return void
     */
    private function replaceTags($tag , $column , $content){
        return $content = str_replace($tag, $column, $content);
    }
}
