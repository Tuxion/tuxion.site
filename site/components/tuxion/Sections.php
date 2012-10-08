<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.');

class Sections extends \dependencies\BaseViews
{

  /* ---------- Frontend ---------- */
  
  protected function sidebar($data)
  {
    
    return array();
    
  }
  
  protected function footer_menu($data)
  {
    
    return array();
    
  }
  
  /**
   * Backend
   */

  protected function item_list($options)
  {

    return array(
      'items' => $this->table('Items')->order('dt_created', 'DESC')->execute()
    );

  }

  protected function edit_item($options)
  {
  
    return array(
      'item' => $this->table('Items')->pk(tx('Data')->get->item_id)->execute_single(),
      'users' => tx('Component')->helpers('tuxion')->get_users(),
      'categories' => tx('Component')->helpers('tuxion')->get_categories()
    );
      

  }

}

