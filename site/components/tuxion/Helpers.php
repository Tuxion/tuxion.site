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
  
  public function get_items($options = null)
  {
  
    $options = Data($options);

    $q = $this
      ->table('Items')
      ->order('dt_created', 'DESC')
      ->limit(20);

    $url_key =
      trim($options->url_key->get(), '/');

    if(!empty($url_key)){
      $q->where('url_key', "'".$url_key."'");
    }

    return $q->execute();

  }
  
}
