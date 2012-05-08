<?php namespace components\menu; if(!defined('TX')) die('No direct access.');

class Sections extends \dependencies\BaseViews
{

  protected function menu_item_list($options)
  {

    return tx('Sql')
      ->table('cms', 'MenuItems')
        ->sk(tx('Data')->filter('cms')->menu_id->is_set() ? tx('Data')->filter('cms')->menu_id : '1')
        ->add_absolute_depth('depth')
        ->join('MenuItemInfo', $mii)->inner()
      ->workwith($mii)
        ->select('title', 'title')
        ->where('language_id', LANGUAGE)
      ->execute();
  }

  protected function menu_item_edit($options)
  {

    return array(
      'item' => $this->table('MenuItems')->join('MenuItemInfo', $ii)->left()->select("$ii.title", 'title')->select("$ii.description", 'description')->pk(tx('Data')->get->item_id)->execute_single()
    );

  }
  
  /* =Menu items
  -------------------------------------------------------------- */

  protected function json_update_menu_items()
  {
    
    $return = tx('Updating menu items', function(){
    
      return tx('Data')->post->menu_items->each(function($item){
        
        tx('Sql')->model('menu', 'MenuItems')->set($item->having(array(
          'id' => 'item_id',
          'lft' => 'left',
          'rgt' => 'right'
        )))
        
        ->save();
        
      });
    
    });
    
    return Data(array(
      
      'return' => $return->return_value,
      
      'success' => $return->success(),
      
      'message' => $return->get_user_message()
      
    ))->as_json();
    
  }
  
}
