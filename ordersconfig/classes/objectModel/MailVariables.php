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


    /**
     * retouner tous les tags en se basant sur la table concerné 
     * $table_name equivaut champ relatedtable côte BD
     * @return void
     */
    public function getTagsByTable($table_name){
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
