<?php namespace components\cms\models; if(!defined('TX')) die('No direct access.');

class Options extends \dependencies\BaseModel
{
  
  protected static
  
    $table_name = 'options',
  
    $relations = array(
      'OptionSets' => array('id' => 'OptionLink.option_id')
    );
    
    
}