<?php namespace components\tuxion\models; if(!defined('TX')) die('No direct access.');

class Items extends \dependencies\BaseModel
{

  protected static

    $table_name = 'tuxion_items',

    $relations = array(
      'Categories'=>array('category_id' => 'Categories.id'),
      'Accounts' => array('user_id' => 'Accounts.id')
    );

  public function get_user(){
    return tx('Sql')->table('Accounts')->pk($this->user_id)->execute_single();
  }

}
