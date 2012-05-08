<?php namespace components\account; if(!defined('TX')) die('No direct access.');

class Helpers extends \dependencies\BaseComponent
{
  
  /**
   * Force a reset of the password of the user.
   *
   * @author Beanow
   * @param Integer/Array(Integer) $user_id The user id's to reset the password for.
   * @return \dependencies\UserFunction The user function in which the password is reset.
   */
  public function reset_password($user_id)
  {
    
    $that = $this;
    
    return tx('Resetting password', function()use($user_id, $that){
      
      tx('Logging')->log('Account', 'Reset password', 'Called with: '.Data($user_id)->dump());
      
      //Validate input.
      $user_id = Data($user_id)
        ->each(function($node){
          tx('Logging')->log('Account', 'Reset password', 'Validating for: '.$node);
          return Data($node)->validate('User ID', array('required', 'number', 'gt'=>0));
        });
      
      //Get the users.
      tx('Sql')
        ->table('account', 'Accounts')
        ->pk($user_id)
        ->execute()
        
        //Check users were found.
        ->is('empty', function(){
          tx('Logging')->log('Account', 'Reset password', 'No users found.');
          throw new \exception\User('No User found for this User ID.');
        })
        
        ->each(function($user){
          
          tx('Logging')->log('Account', 'Reset password', 'Init for user: '.$user->email->get('string'));
          
          //Create claim key.
          $claim_key = tx('Security')->random_string(10);
          
          //Set database values.
          $user
            ->password->set(null)->back()
            ->save()
              ->user_info
              ->claim_key->set($claim_key)->back()
              ->set_status('claimable')
              ->save();
          
          //Create various links.
          $links = Data(array(
            'for_link' => url('/', true)->output,
            'claim_link' => url('/?action=account/claim_account/get&id='.$user->id.'&claim_key='.$claim_key)->output,
            'unsubscribe_link' => url('/?action=account/unsubscribe/get&email='.urlencode($user->email->get('string')))->output
          ));
          
          //Send the invitation email.
          tx('Component')->helpers('mail')->send_fleeting_mail(array(
            'to' => array('name'=>$user->user_info->username->otherwise(''), 'email'=>$user->email),
            'subject' => __('Informatie over uw account op 7daysinmylife.com', 1),
            'html_message' => tx('Component')->views('account')->get_html('email_user_password_reset', $links->having('for_link', 'claim_link', 'unsubscribe_link'))
          ))
          
          ->failure(function($info){
            throw $info->exception;
          });
          
          tx('Logging')->log('Account', 'Reset password', 'Succeeded for user '.$user->email->get('string').'.');

        });
      
    });
    
  }
  
  /**
   * Invite a new user to create an account.
   *
   * @author Beanow
   * @param String $data->username The username to give the user. Note: when claiming the user can change this.
   * @param Email $data->email The email address to send an invite to.
   * @param Int $data->level The user level to set the user.
   * @param String $data->for_title What to invite the user for. Example: 'Project Xtra Coolness'.
   * @param String $data->for_link The link to what to invite the user for. Example: '?page_id=1337'.
   * @return \dependencies\UserFunction The user function in which the invitation is sent.
   */
  public function invite_user($data)
  {
    
    return tx('Sending invitation', function()use($data){
      
      //Validate input.
      $data = Data(data_of($data))->having('username', 'email', 'level', 'for_title', 'for_link')
        ->email->validate('Email', array('required', 'email'))->back()
        ->for_title->validate('For-title', array('required', 'string', 'no_html'))->back()
        ->for_link->validate('For-link', array('required', 'url'))->back();
      
      //Check if the user is already created.
      if(tx('Sql')->table('account', 'Accounts')->where('email', $data->email)->count()->get('int') > 0){
        return tx('Sql')->table('account', 'Accounts')->where('email', $data->email)->execute_single()->id;
        // throw new \exception\User('A user with this email address has already been created.');
      }
      
      //Create the user in the core tables.
      $user = tx('Sql')
        ->model('account', 'Accounts')
        ->email->set($data->email->get('string'))->back()
        ->password->set(null)->back()
        ->level->set($data->level->otherwise(1))->back()
        ->save();
      
      //Create claim key.
      $data->claim_key = tx('Security')->random_string(10);
      
      //Store additional info in the account tables.
      tx('Sql')
        ->model('account', 'UserInfo')
        ->user_id->set($user->id)->back()
        ->username->set($data->username)->back()
        ->claim_key->set($data->claim_key)->back()
        ->set_status('reclaim')
        ->save();
      
      //Create various links.
      $data->for_link = url($data->for_link->get('string'), true)->output;
      $data->claim_link = url('/?action=account/claim_account/get&id='.$user->id.'&claim_key='.$data->claim_key)->output;
      $data->unsubscribe_link = url('/?action=account/unsubscribe/get&email='.urlencode($user->email->get('string')))->output;
      
      //Send the invitation email.
      tx('Component')->helpers('mail')->send_fleeting_mail(array(
        'to' => array('name'=>$data->username->otherwise(''), 'email'=>$user->email),
        'subject' => __('Invitation for', 1).': '.$data->for_title,
        'html_message' => tx('Component')->views('account')->get_html('email_user_invited', $data->having('for_link', 'for_title', 'claim_link', 'unsubscribe_link'))
      ))
      
      ->failure(function($info){
        throw $info->exception;
      });
      
      return $user->id;
      
    });
    
  }
  
  /**
   * Create a new user.
   *
   * @author Beanow
   * @param Email $data->email The email address for the new user.
   * @param String $data->username The username for the new user.
   * @param String $data->password The password for the new user.
   * @param int $data->level The user level to set for the new user (1 or 2).
   * @return \components\account\models\Accounts The account of the newly created user.
   */
  public function create_user($data)
  {

    //Validate input.
    $data = $data->having('email', 'username', 'password', 'level', 'username', 'name', 'preposition', 'family_name')
      ->email->validate('Email address', array('required', 'email'))->back()
      ->username->validate('Username', array('string', 'between' => array(0, 30), 'no_html'))->back()
      ->level->validate('User level', array('required', 'number', 'between' => array(1, 2)))->back()
      ->name->validate('Name', array('string', 'between' => array(0, 30), 'no_html'))->back()
      ->preposition->validate('Preposition', array('string', 'between' => array(0, 30), 'no_html'))->back()
      ->family_name->validate('Family name', array('string', 'between' => array(0, 30), 'no_html'))->back();
    
    /* -- For when using hashing other then md5 is implemented in login --
    //Get the default hashing method.
    $hash_algorithm = tx('Security')->pref_hash_algo();
    
    //Hash the password.
    $data->password->set(tx('Security')->hash($data->password, $hash_algorithm));
    */
    
    //For now we do the ugly thing... ¬_¬
    //But using the data function so it will DIE straight away once it's deprecated.
    $data->password = $data->password->md5();
    
    //Check if the user is already created.
    if(tx('Sql')->table('account', 'Accounts')->join('UserInfo', $ui)->where('email', $data->email)->where("$ui.status", '>', 0)->count()->get('int') > 0){
      return tx('Sql')->table('account', 'Accounts')->join('UserInfo', $ui)->where('email', $data->email)->where("$ui.status", '>', 0)->execute_single();
      // throw new \exception\User('A user with this email address has already been created.');
    }
    
    //Create the user in the core tables.
    $user = tx('Sql')
      ->model('account', 'Accounts')
      ->set($data->having('email', 'password', 'level'))
      ->save();
    
    //Store additional info in the account tables.
    tx('Sql')
      ->model('account', 'UserInfo')
      ->set($data->having('username', 'name', 'preposition', 'family_name')->merge($user->having(array('user_id'=>'id'))))
      ->save();

    //Return the user model.
    return $user;
    
  }
  
  /**
   * Whether the logged in user should claim their account or not.
   *
   * @author Beanow
   * @return Boolean Whether the logged in user should claim their account or not.
   */
  public function should_claim()
  {
    
    $user_info = $this->table('UserInfo')
      ->pk(tx('Account')->user->id->get('int'))
      ->execute_single();
    
    //If there's no user info found for the logged in user then return false.
    if(!$user_info->is_set())
      return false;
    
    $should_claim = false;
    
    //Check the user status is claimable.
    $user_info->check_status('claimable')
    ->success(function()use(&$should_claim){
      //If it is then check if the user is logged in.
      $should_claim = tx('Account')->user->check('login');
    });
    
    return $should_claim;
    
  }
  
}
