<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.');

class EntryPoint extends \dependencies\BaseEntryPoint
{
  
  public function entrance()
  {
    
    if(tx('Config')->system()->check('backend'))
    {
      
      //Display a login page?
      if(!tx('Account')->user->check('login'))
      {
        
        return $this->template('tx_login', 'tx_login', array(), array(
          'content' => tx('Component')->sections('account')->get_html('login_form')
        ));
        
      }
      
      //Show view.
      return $this->template('tuxion', 'tuxion_backend', array('plugins' => array(
        load_plugin('jquery'),
        load_plugin('jsFramework')
      )),
      array(
        'content' => $this->view('items')
      ));
      
    }
    else
    {
      
      //Show view.
      return $this->template('tuxion', array('plugins' => array(
        load_plugin('jquery'),
        load_plugin('jquery_scrollTo'),
        load_plugin('jquery_viewport'),
        load_plugin('jquery_postpone'),
        load_plugin('underscore'),
        load_plugin('jsFramework')
      )),
      array(
        'sidebar' => $this->section('sidebar'),
        'content' => $this->view('app'),
        'footer' => $this->section('footer_menu')
      ));
      
    }
    
  }
  
}
