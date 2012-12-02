<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.');

class Sections extends \dependencies\BaseViews
{

  /* ---------- Frontend ---------- */
  
  protected function feed($data)
  {

    load_plugin('atom');

    //Create Atom object.
    $atom = new \plugins\Atom('Tuxion webdevelopment', 'http://www.tuxion.nl/', 'yesterday');

    //Define feed elements.
    $atom->feed(array(
      'author' => array(
        'name' => 'Tuxion',
        'email' => 'team@tuxion.nl',
        'uri' => 'http://www.tuxion.nl'
      ),
      'link'   => array(
        'rel'=>'self',
        'type'=>'application/atom+xml', 
        'href'=>'http://web.tuxion.nl/atom.xml'
      ) 
    ));

    //Loop items.
    tx('Component')
      ->helpers('tuxion')
      ->get_items()
      ->each(function($r)use(&$atom){

        //Define item elements.
        $atom->entry(
          ($r->title ? $r->title : 'Untitled'), 
          'http://web.tuxion.nl/'.$r->url_key,'/',
          $r->dt_created, 
          array(
            'link' => array(
              'rel'  => 'alternate',
              'href' => 'http://web.tuxion.nl/'.$r->url_key.'/'
            ),
            'content' => array(
              'type'  => 'html', 
              'value' => htmlspecialchars($r->text)
            ),
            'summary' => htmlspecialchars($r->description)
          )
        );

      });

    //Display feed.
    $atom->display();

  }
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

