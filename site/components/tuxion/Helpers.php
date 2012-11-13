<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.');

class Helpers extends \dependencies\BaseComponent
{
  
  public function get_users($user_id = null)
  {
  
    return $this
      ->table('Accounts')
      ->order('username')
      ->execute();
    
  }

  public function get_categories($category_id = null)
  {
  
    return $this
      ->table('Categories')
      ->order('title')
      ->execute();
    
  }
  
  public function get_items($item_id = null, $options = null)
  {
  
    return $this
      ->table('Items')
      ->order('dt_created', 'DESC')
      ->limit(20)
      ->execute();
    
  }
  
}
