<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.');

class EntryPoint extends \dependencies\BaseEntryPoint
{
  
  public function entrance()
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
      'content' => $this->view('app')
    ));
   
  }

}
