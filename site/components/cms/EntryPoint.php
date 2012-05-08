<?php namespace components\cms; if(!defined('TX')) die('No direct access.');

class EntryPoint extends \dependencies\BaseEntryPoint
{

  public function entrance()
  {

    if(tx('Config')->system()->check('backend'))
    {

      return $this->template('cms_backend', 'cms_backend', array(
        'plugins' =>  array(
                        load_plugin('jquery'),
                        load_plugin('jquery_ui'),
                        load_plugin('jquery_selectmenu'),
                        load_plugin('nestedsortable'),
                        load_plugin('jquery_message'),
                        load_plugin('ckfinder'),
                        load_plugin('ckeditor'),
                        load_plugin('jquery_tmpl'),
                        load_plugin('jsFramework'),
                        load_plugin('idtabs')
                      ),
        'scripts' => array(
                        '<script type="text/javascrip" src="'.URL_COMPONENTS.'/cms/includes/backend.js"></script>'
        )
      ),
      array(
        'content' => $this->view('app')
      ));


    }

    else
    {

      $that = $this;

      tx('Validating get variables', function(){

        //validate page id
        tx('Data')->get->pid->not('set', function(){
          throw new \exception\User('Missing the page ID.');
        })->validate('Page ID', array('number'=>'integer', 'gt'=>0));

        //check if page id is present in database
        $page = tx('Sql')->execute_single('SELECT * FROM #__page_pages WHERE id = '.tx('Data')->get->pid)->is('empty', function(){
          throw new \exception\EmptyResult('The page ID does not refer to an existing page.');
        });

        //Check user permissions.
        switch($page->access_level->get('int'))
        {
          case 1:
            tx('Account')->page_authorisation(1);
            break;
          case 2:
            tx('Account')->page_authorisation(1);
            break;
          case 3:
            tx('Account')->page_authorisation(2);
            break;
        }
        
        //validate module id
        tx('Data')->get->mid->is('set', function($mid){
          $mid->validate('Module ID', array('number'=>'integer', 'gt'=>0));
        });

      })

      ->failure(function(){

        //first see if we can go back to where we came from
        $prev = tx('Url')->previous(false, false);
        if($prev !== false && !$prev->compare(tx('Url')->url)){
          tx('Url')->redirect(url($prev, true));
          return;
        }

        tx('Config')->user('homepage')->is('set', function($homepage){

          $redirect = url($homepage);

          $redirect->data->pid->is('set')->and_is(function($pid){
            return tx('Sql')->execute_single('SELECT * FROM #__page_pages WHERE id = '.$pid)->is_set();
          })
          ->success(function()use($redirect){tx('Url')->redirect($redirect);})
          ->failure(function(){tx('Url')->redirect('/admin/');});

        });

      })

      ->success(function()use($that, &$output){

        //load a layout-part
        if(tx('Data')->get->part->is_set()){
          $output = $that->section('page_part');
        }

        //or are we going to load an entire page?
        elseif(tx('Data')->get->pid->is_set()){

          $pi = $that->helper('get_page_info', tx('Data')->get->pid);

          $output = $that->template($pi->template, $pi->theme, array(
            'plugins' =>  array(
                            load_plugin('jquery'),
                            load_plugin('jquery_ui'),
                            load_plugin('nestedsortable'),
                            load_plugin('jsFramework'),
                            load_plugin('jquery_message')
                          )
          ),
          array(
            'admin_toolbar' => $that->section('admin_toolbar'),
            'content' => $that->view('page')
          ));

        }

        else{
          throw new \exception\Unexpected('Failed to detect what to load. :(');
        }

      });

      return $output;

    }

  }

}
