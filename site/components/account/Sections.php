<?php namespace components\account; if(!defined('TX')) die('No direct access.');

class Sections extends \dependencies\BaseViews
{
  
  protected function edit_user()
  {
    
    return $this
      ->table('Accounts')
      ->join('UserInfo', $ui)
      ->select("$ui.username", 'username')
      ->select("$ui.name", 'name')
      ->select("$ui.preposition", 'preposition')
      ->select("$ui.family_name", 'family_name')
      ->pk(tx('Data')->get->user_id)
      ->execute_single();
    
  }
  
  protected function user_list()
  {

    return $this
      ->table('Accounts')
      ->join('UserInfo', $ui)
      ->select("$ui.username", 'username')
      ->select("$ui.status", 'status')
      ->where(tx('Sql')->conditions()
        ->add('1', array("(`$ui.status` & 1)", '1'))
        ->add('2', array("(`$ui.status` & 4)", '4'))
        ->combine('3', array('1', '2'), 'OR')
        ->utilize('3')
      )
      ->order('level', 'DESC')
      ->execute();
    
  }
  
  protected function compose_mail()
  {
    
    return array(
      'default_user_email' => $this
        ->table('Accounts')
        ->pk(tx('Data')->get->user_id)
        ->execute()
        ->map(function($node){ return $node->id->get('int'); }),
      'users' => $this
        ->table('Accounts')
        ->join('UserInfo', $ui)
        ->select("$ui.username", 'username')
        ->select("$ui.status", 'status')
        ->execute()
    );
    
  }

  protected function login_form()
  {
    return array();
  }

  protected function profile()
  {
    return tx('Data')->session->user;
  }

}
