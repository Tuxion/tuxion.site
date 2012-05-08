<?php namespace components\cms\models; if(!defined('TX')) die('No direct access.');

class MenuItemInfo extends \dependencies\BaseModel
{
  
  protected static
    
    $table_name = 'menu_item_info',
    
    $relations = array(
      'MenuItems' => array('item_id' => 'MenuItems.id'),
      'Languages' => array('lang_id' => 'Languages.id')
    );

}