<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.');

class Views extends \dependencies\BaseViews
{

  protected function app($options)
  {
    return array(
      'items' => tx('Component')->helpers('tuxion')->get_items(),
      'categories' => tx('Component')->helpers('tuxion')->get_categories(),
      'admin_toolbar' => (tx('Component')->available('cms') ? tx('Component')->sections('cms')->get_html('admin_toolbar') : false)
    );
    
  }

  protected function items($options)
  {

    return array(
      'item_list' => $this->section('item_list'),
      'edit_item' => $this->section('edit_item'),
      'admin_toolbar' => (tx('Component')->available('cms') ? tx('Component')->sections('cms')->get_html('admin_toolbar') : false)
    );

  }
  
  protected function email_template($options){
    return $options;
  }

}