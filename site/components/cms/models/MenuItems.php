<?php namespace components\cms\models; if(!defined('TX')) die('No direct access.');

class MenuItems extends \dependencies\BaseModel
{
  
  protected static
  
    $table_name = 'menu_items',
  
    $relations = array(
      'MenuItemInfo' => array('id' => 'MenuItemInfo.item_id'),
      'Menus' => array('menu_id' => 'MenuInfo.id'),
      'Pages' => array('page_id' => 'Pages.id')
    ),
    
    $hierarchy = array(
      'left' => 'lft',
      'right' => 'rgt'
    ),
    
    $secondary_keys = array(
      'menu_id'
    );

}