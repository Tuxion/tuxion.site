<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.');

class Sections extends \dependencies\BaseViews
{

  /* ---------- Frontend ---------- */
  
  protected function sidebar($data)
  {
    
    if( tx('Data')->get->view->not('empty') && (tx('Data')->get->view == 'light' || tx('Data')->get->view == 'dark') ){
      $view = tx('Data')->get->view->get();
    }elseif( date("H") < 19 && date("H") > 7 ){
      $view = 'light';
    }else{
      $view = 'dark';
    }

    return array(
      'view' => $view
    );
    
  }
  
  protected function contact_info(){
  }
  
  protected function footer_menu($data)
  {
    
    return array();
    
  }
  
  /**
   * Backend
   */

  protected function item_list($options)
  {

    return array(
      'items' => $this->table('Items')->order('dt_created', 'DESC')->execute()
    );

  }

  protected function edit_item($options)
  {
  
    return array(
      'item' => $this->table('Items')->pk(tx('Data')->get->item_id)->execute_single(),
      'users' => tx('Component')->helpers('tuxion')->get_users(),
      'categories' => tx('Component')->helpers('tuxion')->get_categories(),
      'image_uploader' => tx('Component')->modules('media')->get_html('image_uploader', array(
        'insert_html' => array(
          'header' => '',
          'drop' => 'Sleep gerust een afbeelding naar dit vlak.',
          'upload' => 'Uploaden',
          'browse' => 'Bladeren'
        ),
        'auto_upload' => true,
        'callbacks' => array(
          'ServerFileIdReport' => 'plupload_image_file_id'
        )))
    );
      

  }

}

