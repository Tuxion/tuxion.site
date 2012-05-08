<?php namespace components\cms\models; if(!defined('TX')) die('No direct access.');

class ComponentViewInfo extends \dependencies\BaseModel
{
  
  protected static
  
    $table_name = 'cms_component_view_info',
  
    $relations = array(
      'Languages' => array('lang_id' => 'Languages.id'),
      'ComponentViews' => array('com_view_id' => 'ComponentViews.id')
    );
    
    
}