<?php namespace components\cms\models; if(!defined('TX')) die('No direct access.');

class Pages extends \dependencies\BaseModel
{
  
  protected static
  
    $table_name = 'page_pages',
  
    $relations = array(
      'Components' => array('view_id' => 'ComponentViews.id'),
      'ComponentViews' => array('view_id' => 'ComponentViews.id'),
      'ComponentViewInfo' => array('view_id' => 'ComponentViews.id'),
      'Themes' => array('theme_id' => 'Themes.id'),
      'Templates' => array('template_id' => 'Templates.id'),
      'Layouts' => array('layout_id' => 'Layouts.id'),
      'LayoutInfo' => array('layout_id' => 'LayoutInfo.layout_id'),
      'OptionSets' => array('optset_id' => 'OptionSets.id'),
      'MenuItems' => array('id' => 'MenuItems.page_id'),
      'Modules' => array('id' => 'ModulePageLink.page_id')
    );
  
  protected function get_menu_items()
  {
  
    return $this->table('MenuItems')
      ->where('page_id', $this->id)
      ->join('MenuItemInfo', $mii)
    ->workwith($mii)
      ->select("title", 'title')
      ->where('language_id', LANGUAGE)
    ->execute();
  
  }
  
}