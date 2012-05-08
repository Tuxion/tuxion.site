<?php namespace components\tuxion\models; if(!defined('TX')) die('No direct access.');

class Categories extends \dependencies\BaseModel
{

  protected static

    $table_name = 'tuxion_item_categories',

    $relations = array(
      'Items'=>array('id' => 'Items.category_id')
    );

  public function get_stuff()
  {

    return true;

  }

}
