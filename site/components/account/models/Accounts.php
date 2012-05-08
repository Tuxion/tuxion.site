<?php namespace components\account\models; if(!defined('TX')) die('No direct access.');

class Accounts extends \dependencies\BaseModel
{
  
  protected static
    
    $table_name = 'cms_users',
    
    $relations = array(
      'UserInfo' => array('id' => 'UserInfo.user_id')
    );
  
  public function get_is_administrator()
  {
    
    return $this->level->get('int') == 2;
    
  }
  
  public function get_user_info()
  {
  
    return $this->table('UserInfo')->where('user_id', $this->id)->execute_single();
    
  }
  
}
