<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.');

class Views extends \dependencies\BaseViews
{

  protected function app($options)
  {
    
    $options = Data(array());
    
    return array();
    
  }

  protected function items($options)
  {

    return array(
      'item_list' => $this->section('item_list'),
      'edit_item' => $this->section('edit_item')
    );

  }

}