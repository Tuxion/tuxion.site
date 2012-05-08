<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.');

class EntryPoint extends \dependencies\BaseEntryPoint
{
  
  public function entrance()
  {
    
    //Show view.
    return $this->template('tuxion', array('plugins' => array(
      load_plugin('jquery'),
      load_plugin('jquery_ui'),
      load_plugin('jsFramework'),
      load_plugin('html5shiv'),
      load_plugin('jquery_appear'),
      load_plugin('jquery_smoothDivScroll')
    )),
    array(
      'content' => $this->view('app')
    ));
   
  }

}
