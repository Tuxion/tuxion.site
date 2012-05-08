<?php namespace components\cms\models; if(!defined('TX')) die('No direct access.');

class ComponentViews extends \dependencies\BaseModel
{
  
  protected static
  
    $table_name = 'cms_component_views',
  
    $relations = array(
      'Components' => array('com_id' => 'Components.id'),
      'ComponentViewInfo' => array('id' => 'ComponentViewInfo.com_view_id')
    );
    
    
}