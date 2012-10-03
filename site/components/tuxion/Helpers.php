<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.');

class Helpers extends \dependencies\BaseComponent
{
  
  public function get_categories($category_id = null)
  {
  
    return $this
      ->table('Categories')
      ->order('title')
      ->execute();
    
  }
  
}
