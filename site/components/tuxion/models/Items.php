<?php namespace components\tuxion\models; if(!defined('TX')) die('No direct access.');

class Items extends \dependencies\BaseModel
{

  protected static

    $table_name = 'tuxion_items',

    $relations = array(
      'Categories'=>array('category_id' => 'Categories.id')
    );

  public function get_stuff()
  {

    return true;

  }

}
