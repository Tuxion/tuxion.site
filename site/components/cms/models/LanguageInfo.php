<?php namespace components\cms\models; if(!defined('TX')) die('No direct access.');

class LanguageInfo extends \dependencies\BaseModel
{
  
  protected static
  
    $table_name = 'language_info',
  
    $relations = array(
      'Languages' => array('language_id' => 'Languages.id')
    );
    
    
}