<?php namespace core; if(!defined('TX')) die('No direct access.');

class Account
{

  public
    $user;

  public function __construct()
  {
  }

  public function init()
  {

    //append user object for easy access
    $this->user =& tx('Data')->session->user;

    //progress user activity
    tx('Data')->server->REQUEST_TIME->copyto($this->user->activity);

    //validate login
    if($this->user->check('login'))
    {

      tx('Sql')->execute_scalar("
        SELECT id FROM #__cms_users
        WHERE 1
          AND id = '{$this->user->id}'
          AND email = '{$this->user->email}'
          AND level = '{$this->user->level}'
          AND session = '".tx('Session')->id."'
          AND ipa = '".tx('Data')->server->REMOTE_ADDR."'
      ")

      ->is('empty', function(){
        tx('Account')->logout();
      });

    }

    else{
      $this->logout();
    }

  }

  public function login($email, $pass)
  {

    $errors = array();

    $email = data_of($email);
    $pass = data_of($pass);

    $user = tx('Sql')->execute_single("SELECT * FROM #__cms_users WHERE email = '$email'")->is('empty', function(){
      throw new \exception\EmptyResult('Email address not found.');
    });

    if(md5($pass) !== $user->password->get()){
      throw new \exception\Validation('Invalid password.');
    }

    tx('Session')->regenerate();

    $sid = tx('Session')->id;
    $ipa = tx('Data')->server->REMOTE_ADDR->get();
    $dtl = date("Y-m-d H:i:s");

    tx('Sql')->execute_non_query("UPDATE #__cms_users SET session = '$sid', ipa = '$ipa', dt_last_login = '$dtl' WHERE id = {$user->id}");

    $this->user->id = $user->id;
    $this->user->email = $user->email;
    $this->user->level = $user->level;
    $this->user->login = true;

  }

  public function logout()
  {
    $this->user->un_set('id', 'email', 'activity');
    $this->user->login = false;
    $this->user->level = 0;

    tx('Sql')->execute_non_query("UPDATE #__cms_users SET session = NULL, ipa = NULL WHERE (session != NULL OR ipa != NULL)");

    tx('Session')->regenerate();

  }

  public function register($email, $pass, $level=1)
  {

    $email = data_of($email);
    $pass = data_of($pass);
    $level = data_of($level);

    if(preg_match('/^[a-f0-9]{32}$/', $pass) == 0){
      $pass = md5($pass);
    }

    tx('Session')->regenerate();
    $sid = tx('Session')->id;
    $ipa = tx('Data')->server->REMOTE_ADDR->get();

    tx('Sql')->execute_non_query("INSERT INTO #__cms_users VALUES (NULL, '$email', '$pass', '$level', '$sid', '$ipa', NULL)");
    return true;

  }

  public function check_level($level, $exact=false)
  {
    
    return ($exact===true ? $this->user->level->get('int') == $level : $this->user->level->get('int') >= $level);
    
  }
  
  //>>TODO this looks CMS component specific. Remove from core?
  public function page_authorisation($level, $exact=false)
  {

    if($this->check_level($level, $exact)){
      return;
    }

    if(tx('Config')->user('login_page')->is_set()){
      $redirect = url(URL_BASE.'?'.tx('Config')->user('login_page'), true);
    }

    else{
      $redirect = url(URL_BASE.'?'.tx('Config')->user('homepage'), true);
    }

    if($redirect->compare(tx('Url')->url)){
      throw new \exception\User('The login page requires you to be logged in. Please contact the system administrator.');
    }

    tx('Url')->redirect($redirect);

  }

}
