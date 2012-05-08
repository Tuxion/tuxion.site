<?php namespace components\account; if(!defined('TX')) die('No direct access.');

class Actions extends \dependencies\BaseComponent
{
  
  protected
    $default_permission = 2,
    $permissions = array(
      'edit_profile' => 1,
      'claim_account' => 0,
      'save_avatar' => 1
    );
  
  protected function edit_profile($data)
  {
    
    tx('Editing profile', function()use($data){
      
      //Validate input.
      $data = $data->having('id', 'avatar_image_id', 'password1', 'password2', 'name', 'preposition', 'family_name')
        ->id->validate('User ID', array('required', 'number', 'gt'=>0))->back();
      
      //Check if operation is allowed.
      //Asuming default permission requirement of level 1.
      if(tx('Account')->user->level->get('int') !== 2 && tx('Account')->user->id->get('int') !== $data->id->get('int')){
        throw new \exception\Authorisation('You\'re not allowed to edit this user profile.');
      }
      
      //Get the user object.
      $user = tx('Sql')->table('account', 'Accounts')
        ->pk($data->id)
        ->execute_single();
        
      //Get the user info object.
      $user_info = tx('Sql')->table('account', 'UserInfo')
        ->where('user_id', $data->id)
        ->execute_single();
      
      //Check if the user is found. This should not fail when the user is logged in,
      // but it could fail if you're an admin trying to edit a non-existing user.
      $user->is('empty', function(){
        throw new \exception\User('User with this ID is not found.');
      });
      
      //Validate password
      $data->password1->is('empty', function()use(&$data){
        
        //See if a password should have been given.
        if(tx('Component')->helpers('account')->should_claim())
          throw new \exception\Validation('You are required to set a new password.');
        
        //If no new password is given; unset the password.
        $data->password1->un_set();
        $data->password2->un_set();

      })->failure(function()use(&$data){

        $data
          ->password1->validate('Password', array('required', 'password'))->back()
          ->password2->validate('Confirm password', array('required'))->back();

        //Check the passwords are equal.
        $data->password1->eq($data->password2)->success(function()use(&$data){
          
          //Set the password to md5(password1). EWWWWW!
          //And unset password1 and password2.
          $data
            ->password->set($data->password1->md5())->back()
            ->password1->un_set()->back()
            ->password2->un_set()->back();

          })
        
        //If passwords are not equal, throw exception.
        ->failure(function(){
          throw new \exception\Validation('Passwords are not the same.');
        });

      });
      
      //Store data in database.
      $user->set(true, $data)->save();
      $user_info->merge($data->having('name', 'preposition', 'family_name'))->save();

      //See if we should claim the user.
      $user_info
        ->check_status('claimable', function($user_info){

          //Set status and unset claim key.
          $user_info
            ->set_status('claimed')
            ->claim_key->un_set()->back()
            ->save();
          
        });
            
    })
    
    //Show error message.
    ->failure(function($info){
      tx('Controller')->message(array(
        'error' => $info->get_user_message()
      ));
    })
    
    //Show notification.
    ->success(function($info){
      tx('Controller')->message(array(
        'notification' => $info->get_user_message()
      ));
    });
    
    //Redirect.
    tx('Url')->redirect(url(($data->redirect_url->is_set() ? $data->redirect_url : 'id=NULL'), true));
    
  }
  
  protected function edit_user($data)
  {

    //update
    tx($data->id->get('int') > 0 ? 'Updating a user.' : 'Adding a new user.', function()use($data){
      $data = $data->having('id', 'email', 'username', 'password', 'choose_password', 'notify_user', 'name', 'preposition', 'family_name')
        ->email->validate('Email address', array('required', 'email'))->back()
        ->username->validate('Username', array('between' => array(0, 30), 'no_html'))->back()
        ->level->set($data->admin->is_set() ? 2 : 1)->back();

      if($data->id->get('int') > 0)
      {
      
        //Since we use the fugly script here. Use md5... ¬_¬
        $data->password->is('set')->and_not('empty')->success(function()use(&$data){
          $data->password = $data->password->md5();
        })->failure(function()use(&$data){
          $data->password->un_set();
        });

        $user = tx('Sql')->table('account', 'Accounts')->pk($data->id)->execute_single()->is('empty', function(){
          throw new \exception\User('Could not update because no entry was found in the database with id %s.', $data->id);
        })
        ->merge($data->having('email', 'password'))->save();
        
        tx('Sql')->table('account', 'UserInfo')->pk($user->id)->execute_single()->is('empty')
          ->success(function($user_info)use($data, $user){
            tx('Sql')->model('account', 'UserInfo')->set($data->having('username', 'name', 'preposition', 'family_name')->merge($user->having(array('user_id'=>'id'))))->save();
          })
          ->failure(function($user_info)use($data){
            $user_info->merge($data->having('username', 'name', 'preposition', 'family_name'))->save();
          });

      }

      //insert
      else{

        //If the user is the choose their own password.
        if($data->choose_password->get('boolean'))
        {
          
          //Invite the user.
          $user = tx('Component')->helpers('account')->call('invite_user', array(
            'email' => $data->email,
            'username' => $data->username,
            'level' => $data->level,
            'for_title' => url('/', true)->output,
            'for_link' => '/'
          ))
          
          ->failure(function($info){
            
            tx('Controller')->message(array(
              'error' => $info->get_user_message()
            ));
            
            tx('Url')->redirect('section=account/user_list&user_id=NULL');
            
            return;
            
          });
          
        }
        
        else
        {

          //Create the user.
          $user = tx('Component')->helpers('account')->call('create_user', array(
            'email' => $data->email,
            'username' => $data->username,
            'password' => $data->password,
            'level' => $data->level
          ))
          
          ->failure(function($info){
            
            tx('Controller')->message(array(
              'error' => $info->get_user_message()
            ));
            
            tx('Url')->redirect('section=account/user_list&user_id=NULL');
            
            return;
            
          });
          
          //If we need to notify the user.
          if($data->notify_user->get('boolean'))
          {
            
            //Send email.
            tx('Component')->helpers('mail')->send_fleeting_mail(array(
              'to' => $data->username.' <'.$user->email.'>',
              'subject' => __('Account created', 1),
              'html_message' => tx('Component')->views('account')->get_html('email_user_created', $data->having('email', 'username', 'user_id', 'level'))
            ))
            
            ->failure(function($info){
              tx('Controller')->message(array(
                'error' => $info->get_user_message()
              ));
            }); 
            
          } 
          
        }
        
      }
      
    })
    
    ->failure(function($info){
      tx('Controller')->message(array(
        'error' => $info->get_user_message()
      ));
    });
    
    tx('Url')->redirect('section=account/user_list&user_id=NULL');
    
  }
  
  protected function save_avatar($data)
  {

    //Check if operation is allowed.
    if(tx('Account')->user->level->get('int') !== 2 && tx('Account')->user->id->get('int') !== $data->user_id->get('int')){
      throw new \exception\Authorisation('You\'re not allowed to edit this user profile.');
    }
    
    tx($data->user_id->get('int') > 0 ? 'Updating an avatar.' : 'Adding a new avatar.', function()use($data){

      tx('Sql')->table('account', 'UserInfo')->pk($data->user_id)->execute_single()->is('empty')
        ->success(function($user_info)use($data){
          tx('Sql')->model('account', 'UserInfo')->set($data->having('avatar_image_id'))->save();
        })
        ->failure(function($user_info)use($data){
          $user_info->merge($data->having('avatar_image_id'))->save();
        });

    })

    ->failure(function($info){

      tx('Controller')->message(array(
        'error' => $info->get_user_message()
      ));

    });

    // tx('Url')->redirect('section=sevendays/project_list&project_id=NULL');
    
  }

  protected function deactivate_user($data)
  {
    $this->set_user_status($data->push('status', 'deactivated'));
  }

  protected function delete_user($data)
  {
    $this->set_user_status($data->push('status', 0));
  }

  protected function set_user_status($data)
  {
    
    tx('Changing user-status.', function()use($data){
      
      //Validate input.
      $data = $data->having('user_id', 'status')
        ->user_id->validate('User #ID', array('required', 'number'))->back()
        ->status->validate('New User Status', array('required', 'string'))->back();

      //Set status.
      tx('Sql')
        ->table('account', 'UserInfo')
        ->pk($data->user_id)
      ->execute_single()
        ->set_status($data->status)
        ->save();
      
    })
    
    ->failure(function($info){
      tx('Controller')->message(array(
        'error' => $info->get_user_message()
      ));
    });
    
    tx('Url')->redirect('user_id=NULL&status=NULL');
    
  }
  
  protected function reset_password($data)
  {
    
    $this->helper('reset_password', $data->user_id)
    
    ->failure(function($info){
      tx('Controller')->message(array(
        'error' => $info->get_user_message()
      ));
    })
    
    ->success(function($info){
      tx('Controller')->message(array(
        'notification' => $info->get_user_message()
      ));
    });
    
    tx('Url')->redirect('id=NULL');
    
  }
  
  protected function claim_account($data)
  {
    
    tx('Claiming account.', function()use($data){
      
      //Validate input.
      $data = $data->having('id', 'claim_key')
        ->id->validate('User ID', array('required', 'number', 'gt'=>0))->back()
        ->claim_key->validate('Claim key', array('required', 'string'))->back();
        
      $user = tx('Sql')
        ->table('account', 'UserInfo')
        ->where('user_id', $data->id)
        ->execute_single();
      
      $error = false;
      
      //Check user is found.
      $user->is('empty', function(){
        $error = true;
      });
      
      //Check if user is claimable.
      if(!$error){
        
        $user->check_status('not_claimable', function($user){
          $error = true;
        }); 
        
      }
      
      //Check the claim key is right.
      if(!$error){
        
        $user->claim_key->eq($data->claim_key)
        ->failure(function(){
          $error = true;
        }); 
        
      }
      
      //Use identical error messages and send them from the same line of code.
      //To make finding claimable accounts impossible through this action.
      if($error)
        throw new \exception\User('Claim is invalid.');
      
      //Don't claim the user in the database here.
      //Use tx('Component')->helpers('account')->should_claim() to check if the account should be claimed.
      //Then do the actual claiming when editing the user profile.
      
      //Save the current IP address and current session as the last login.
      $account = $user->account
        ->ipa->set(tx('Data')->server->REMOTE_ADDR)->back()
        ->session->set(tx('Session')->id)->back()
        ->save();
      
      //Set user in session.
      tx('Data')->session->user->set(array(
        'id' => $account->id->get('int'),
        'email' => $account->email->get('string'),
        'level' => $account->level->get('int'),
        'ipa' => $account->ipa->get('string'),
        'login' => true
      ));
      tx('Account')->user->set(tx('Data')->session->user);
      
    })
    
    ->failure(function($info){
      tx('Controller')->message(array(
        'error' => $info->get_user_message()
      ));
    })
    
    ->success(function($info){
      tx('Controller')->message(array(
        'notification' => $info->get_user_message()
      ));
    });
    
    tx('Url')->redirect('id=NULL&claim_key=NULL');
    
  }
  
  protected function send_mail($data)
  {
    
    tx('Sending mail.', function()use($data){
      
      $recievers = tx('Sql')
        ->table('account', 'Accounts')
        ->pk($data->recievers)
        ->execute()
        ->map(function($node){ return $node->email; });
      
      //Send email.
      tx('Component')->helpers('mail')->send_fleeting_mail(array(
        'bcc' => $recievers,
        'subject' => $data->subject->get(),
        'html_message' => $data->message->get()
      ))
      
      ->failure(function($info){
        tx('Controller')->message(array(
          'error' => $info->get_user_message()
        ));
      });
      
    })
    
    ->success(function($info){
      tx('Controller')->message(array(
        'notification' => $info->get_user_message()
      ));
    });
    
    tx('Url')->redirect('user_id=NULL');
    
  }
  
}
