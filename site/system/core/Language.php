<?php namespace core; if(!defined('TX')) die('No direct access.');

class Language
{
  
  //in the initiator, we set the language to the first language in the database, or one defined by session vars
  public function init()
  {
    
    $language = null;
    
    //easy access to language variables in session
    $lang = tx('Data')->session->tx->language;
    
    //if the session defines a language
    if($lang->is_set())
    {

      tx('Validating language.', function()use($lang){
        $lang->validate('Language', array('number'=>'integer'));
        tx('Sql')->execute_scalar('SELECT id FROM #__language_languages WHERE id = '.$lang);
      })
      
      ->failure(function($info)use($lang){
        $lang->un_set();
        tx('Session')->new_flash('error', $info->get_user_message());
      });
      
    }
    
    //if the language is not in the session
    if( ! $lang->is_set())
    {

      tx('Setting language from database.', function()use($lang){
        $lang->set(tx('Sql')->execute_scalar('SELECT id FROM #__language_languages ORDER BY id ASC LIMIT 1'));
      })
      
      ->failure(function($info)use($lang){
        $lang->un_set();
        throw new \exception\NotFound($info->get_user_message());
      });
      
    }
    
    if(tx('Config')->system()->check('backend')){
      define('LANGUAGE', 1);
    }else{
      define('LANGUAGE', $lang->get());
    }
    
    define('LANGUAGE_CODE', tx('Sql')->execute_scalar('SELECT code FROM #__language_languages WHERE id = '.$lang));
    
  }
  
}
