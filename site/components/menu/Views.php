<?php namespace components\menu; if(!defined('TX')) die('No direct access.');

class Views extends \dependencies\BaseViews
{

  protected function menus($return=null)
  {    
    return array(
      'menu_item_list' => $this->section('menu_item_list'),
      'menu_item_edit' => $this->section('menu_item_edit')
    );    
  }
  
}