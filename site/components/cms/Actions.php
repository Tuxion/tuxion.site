<?php namespace components\cms; if(!defined('TX')) die('No direct access.');

class Actions extends \dependencies\BaseComponent
{

  protected
    $permissions = array(
      'select_menu' => 2,
      'new_page' => 2,
      'edit_page' => 2,
      'logout' => 1
    );

  protected function select_menu($data)
  {

    $action = tx('Selecting a menu.', function()use($data){
      $data->menu_id->validate('Menu', array('number'))->moveto(tx('Data')->session->cms->filters->menu_id);
    });

    tx('Controller')->message(array(
      'notification'=>$action->get_user_message()
    ));

    tx('Url')->redirect(url('menu_id=NULL'));

  }

  protected function link_page($data)
  {

    $action = tx('Adding a new menu item <-> page link.', function()use($data){

      $page = tx('Sql')->table('cms', 'MenuItems')->pk($data->menu_item_id)->execute_single()->is('empty', function()use($data){
        throw new \exception\EmptyResult('No menu item entry was found with id %s.', $data->menu_item_id);
      })
      ->merge($data->having('page_id'))->save();

    });

    $action->failure(function($info){
      // tx('Controller')->messages('error', $info->get_user_message());
    });

    if($data->redirect->is_set()){
      tx('Url')->redirect(url("section=cms/app&menu={$data->menu_item_id}", true));
    }

  }

  protected function detach_page($data)
  {

    $data
      ->menu->validate('Menu identifier', array('required', 'number'))->back()
      ->pid->validate('Page identifier', array('required', 'number'));
  
    $action = tx('Detaching a page from a menu item.', function()use($data){

      $test = tx('Sql')->table('cms', 'MenuItems')->pk($data->menu)->where('page_id', $data->pid)->execute_single()->is('empty', function()use($data){
        throw new \exception\EmptyResult('No menu item entry was found with id %s.', $data->menu);
      })
      ->merge(array('page_id' => 'NULL'))
      ->save();

    })->failure(function($info){
      tx('Controller')->message(array(
        'error' => $info->get_user_message()
      ));
    });
    
    tx('Url')->redirect("section=cms/app&menu={$data->menu}");

  }

  protected function new_page($data)
  {

    $page = null;

    tx('Adding a new page.', function()use($data){

      //save page
      $page = tx('Sql')->model('cms', 'Pages')->set(array(
        'title' => 'New Page',
        'view_id' => $data->view_id->validate('View', array('required', 'number'))
      ))
      ->save();
      
      if($data->link_to->is_set())
      {

        tx('Component')->actions('cms')->call('link_page', Data(array(
          'page_id' => $page->id,
          'menu_item_id' => $data->link_to
        )));

        tx('Url')->redirect(url("section=cms/app&menu={$data->link_to}&pid={$page->id}", true));

      }

      else{
        tx('Url')->redirect("section=cms/app&pid={$page->id}");
      }

    })

    ->failure(function($info){
      tx('Controller')->message(array(
        'error' => $info->get_user_message()
      ));
    });

  }

  protected function edit_page($data)
  {

    tx('Editing page.', function()use($data){

      // $data
        // -> title         ->validate('Title', array('required', 'not_empty', 'no_html', 'between'=>array(2, 250)))->back()
        // -> theme_id      ->validate('Theme', array('number', 'gt'=>0))->back()
        // -> template_id   ->validate('Template', array('number', 'gt'=>0))->back()
        // -> layout_id     ->validate('Layout', array('number', 'gt'=>0))->back()
        // -> keywords      ->validate('Keywords', array('string', 'no_html'))->back()
        // -> access_level  ->validate('Access level', array('number', 'between'=>array(0, 2)))->back()
        // -> published     ->validate('Published', array('number', 'between'=>array(0, 1)))->back()
        // -> p_from        ->un_set()->back()
        // -> p_to          ->un_set()->back()
        // -> trashed       ->un_set()->back();
      
      tx('Sql')->table('cms', 'Pages')->pk($data->page_id)->execute_single()->not('set', function(){
        throw new \exception\EmptyResult('Could not retrieve the page you were trying to edit. This could be because the ID was invalid.');
      })
      ->merge($data)
      ->save();

    })

    ->failure(function($info){
      tx('Controller')->message(array(
        'error' => $info->get_user_message()
      ));
    });

  }

  protected function delete_page($data)
  {

    tx('Deleting page.', function()use($data){

      //delete
      tx('Sql')->table('cms', 'Pages')->pk($data->page_id)->execute_single()->not('set', function(){
        throw new \exception\EmptyResult('Could not retrieve the page you were trying to delete. This could be because the ID was invalid.');
      })
      ->merge(array('trashed' => 1))
      ->save();

    })

    ->failure(function($info){
      tx('Controller')->message(array(
        'error' => $info->get_user_message()
      ));

    });

  }

  protected function edit_menu_item($data)
  {

    $item_id = 0;
    tx($data->id->get('int') > 0 ? 'Updating a menu item.' : 'Adding a new menu item', function()use($data, &$item_id){

      //append user object for easy access
      $user_id = tx('Data')->session->user->id;

      //save item
      $item = tx('Sql')->table('cms', 'MenuItems')->pk($data->id->get('int'))->execute_single()->is('empty')
        ->success(function()use($data, $user_id, &$item_id){
          $item = tx('Sql')->model('cms', 'MenuItems')->merge($data->having('menu_id', 'page_id'))->hsave(null, 0);
          $item_id = mysql_insert_id();
        })
        ->failure(function($item)use($data, $user_id, &$item_id){
          $item->merge($data->having('page_id'))->merge(array('user_id' => $user_id))->save();
          $item_id = $item->id->get('int');
        });

      //save menu item info
      $menu_item_info = tx('Sql')->table('cms', 'MenuItemInfo')->where('item_id', "'{$item_id}'")->execute_single()->is('empty')
        ->success(function()use($data, $item_id){
          tx('Sql')->model('cms', 'MenuItemInfo')->set($data->having('title', 'description'))->merge(array('language_id' => LANGUAGE))->merge(array('item_id' => $item_id))->save();
        })
        ->failure(function($menu_item_info)use($data){
          $menu_item_info->merge($data->having('title', 'description'))->merge(array('language_id' => LANGUAGE))->save();
        });

    })

    ->failure(function($info){
      tx('Controller')->message(array(
        'error' => $info->get_user_message()
      ));

    });

    tx('Url')->redirect(url('section=cms/app&menu='.$item_id, true));

  }


  protected function login($data)
  {

    $data = $data->having('email', 'pass');

    tx('Logging in.', function()use($data){

      $data
        -> email  ->validate('Email address', array('required', 'not_empty', 'email'))->back()
        -> pass   ->validate('Password', array('required', 'not_empty', 'between'=>array(3, 30)));

      tx('Account')->login($data->email, $data->pass);

    })

    ->failure(function($info){

      tx('Controller')->message(array(
        'error' => $info->get_user_message()
      ));

    });

    $prev = tx('Url')->previous();

    if($prev !== false){
      tx('Url')->redirect($prev);
    }

    tx('Url')->redirect(url('email=NULL&pass=NULL', false, ($prev !== false)));

  }

  protected function logout($data)
  {

    tx('Logging out.', function(){tx('Account')->logout();})->failure(function($info){
      tx('Controller')->message(array(
        'error' => $info->get_user_message()
      ));

    });

  }

  protected function register($data)
  {

    $data = $data->having('email', 'pass');

    tx('Registering a new account.', function()use($data){

      $data
        -> email  ->validate('Email address', array('required', 'not_empty', 'email'))->back()
        -> pass   ->validate('Password', array('required', 'not_empty', 'between'=>array(3, 30)));
      tx('Account')->register($data->email, $data->pass);

    })

    ->failure(function($info){
      tx('Controller')->message(array(
        'error' => $info->get_user_message()
      ));

    });

    tx('Url')->redirect('email=NULL&pass=NULL');

  }

  protected function language($data)
  {

    tx('Setting the language.', function()use($data){
      $data->lang_id->validate('Language', array('number', 'type'=>'integer'))->moveto(tx('Data')->session->tx->language);
    })

    ->failure(function($info){
      tx('Controller')->message(array(
        'error' => $info->get_user_message()
      ));

    });

    tx('Url')->redirect('lang_id=NULL');

  }

  protected function pause_redirects($data=null)
  {
    if(DEBUG){
      tx('Data')->session->tx->debug->pause_redirects = true;
    }else{
      throw new \exception\Authorisation('DEBUG mode has to be on in order to pause redirects.');
    }
  }

  protected function play_redirects($data=null)
  {
    tx('Data')->session->tx->debug->pause_redirects->un_set();
  }

}
