<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.');

class Sections extends \dependencies\BaseViews
{

  /**
   * Backend
   */

  protected function item_list($options)
  {

    return array(
      'items' => $this->table('Items')->execute()
    );

  }

  protected function edit_item($options)
  {
  
    return array(
      'item' => $this->table('Items')->pk(tx('Data')->get->item_id)->execute_single()
    );
      

  }

}

