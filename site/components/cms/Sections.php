<?php namespace components\cms; if(!defined('TX')) die('No direct access.');

class Sections extends \dependencies\BaseViews
{

  protected function app()
  {

    return array(
      'menu' => $this->section('menu_app'),
      'page' => $this->section('page_app')
    );

  }


  protected function config_app()
  {

    $view_arr = explode('/', tx('Data')->get->view->get());

    switch(count($view_arr)){
      case 1:
        $component = $this->component;
        $view = $view_arr[0];
        break;
      case 2:
        $component = $view_arr[0];
        $view = $view_arr[1];
        break;
    }

    $paths = array(
      'theme' => URL_THEMES.'system/backend/',
      'template' => URL_TEMPLATES.'system/backend/',
      'components' => URL_COMPONENTS,
      'component' => URL_COMPONENTS.$component.'/',
      'cms' => URL_SITE.'tx.cms/cms/'
    );

    return tx('Component')->views($component)->get_html($view, array($paths));

  }


  protected function menu_app()
  {

    if(tx('Data')->get->menu->is_set() && tx('Data')->get->menu->get('int') > 0){
      $content = $this->section('edit_menu_item', array('id'=>tx('Data')->get->menu));
    }

    elseif(tx('Data')->get->menu->is_set() && tx('Data')->get->menu->get('int') == 0){
      $content = $this->section('edit_menu_item');
    }

    elseif(tx('Data')->get->pid->is_set()){
      $content = $this->section('link_menu_item');
    }

    else{
      $content = '';
    }

    return $content;

  }

  protected function link_menu_item($options)
  {

    return array();

  }

  protected function edit_menu_item($data)
  {

    return array(
      'info' => tx('Sql')->table('cms', 'MenuItemInfo')->join('MenuItems', $item)->select("$item.menu_id", 'menu_id')->where('language_id', LANGUAGE)->where('item_id', $data->id)->execute_single(),
      'menus' => tx('Sql')->table('cms', 'Menus')->execute()
    );

  }


  protected function page_app()
  {

    if(tx('Data')->get->menu->is_set() && tx('Data')->get->menu->get('int') > 0)
    {

      $menu_item_info = tx('Sql')->table('cms', 'MenuItems')->pk(tx('Data')->get->menu)->execute_single();

      if($menu_item_info->page_id->is(function($d){return $d->is_set() && $d->get('int') > 0;})->success()){
        $content = $this->section('edit_page', array('id'=>$menu_item_info->page_id));
      }

      else{
        $content = $this->section('new_page');
      }

    }

    //edit page
    elseif(tx('Data')->get->pid->is_set())
    {
      $content = $this->section('edit_page', array('id'=>tx('Data')->get->pid));
    }

    //new menu item
    elseif(tx('Data')->get->menu->is_set() && tx('Data')->get->menu->get('int') == 0){
      $content = '';
    }

    //new page
    else{
      $content = $this->section('new_page');
    }

    return $content;

  }

  protected function new_page($options)
  {

    return array(
      'page_types' => tx('Sql')
        ->table('cms', 'ComponentViews')
          ->join('ComponentViewInfo', $cvi)
            ->inner()
          ->where('is_config', 0)
        ->workwith($cvi)
          ->select('title', 'title')
          ->select('description', 'description')
          ->where('lang_id', LANGUAGE)
        ->execute(),
      'pages' => tx('Sql')->table('cms', 'Pages')
        ->join('LayoutInfo', $li)
        ->join('ComponentViewInfo', $cvi)
        ->select("$li.title", 'layout_title')
        ->select("$cvi.title", 'view_title')
        ->where("$cvi.lang_id", LANGUAGE)
        ->where('trashed', 0)
        ->order('title')
      ->execute()
    );

  }

  protected function edit_page($options)
  {

    $page_info = $this->helper('get_page_info', $options->id->is_set() ? $options->id->get('int') : tx('Data')->filter('cms')->pid->get('int'));

    return array(
      'layout_info' => tx('Sql')->table('cms', 'LayoutInfo')->execute(),
      'page' => $page_info,
      'content' => $page_info === false ? 'Page was removed.' : tx('Component')->views($page_info->component)->get_html($page_info->view_name, array('pid' => $page_info->id)),
      'themes' => $this->table('Themes')->order('title')->execute(),
      'templates' => $this->table('Templates')->order('title')->execute()
    );

  }


  protected function module_app()
  {

    return (tx('Data')->get->mid->is_set() ? tx('Controller')->load_module(tx('Data')->get->mid) : 'no module selected');

  }



  protected function menus($options)
  {

    return array(
      'all' => tx('Sql')->table('cms', 'Menus')->execute(),
      'selected' => tx('Sql')->table('cms', 'Menus')->pk(tx('Data')->filter('cms')->menu_id->is_set() ? tx('Data')->filter('cms')->menu_id : 1)->execute_single()
    );

  }

  protected function menu_items($options)
  {

    return tx('Sql')
      ->table('cms', 'MenuItems')
        ->sk(tx('Data')->filter('cms')->menu_id->is_set() ? tx('Data')->filter('cms')->menu_id : '1')
        ->add_absolute_depth('depth')
        ->join('MenuItemInfo', $mii)->left()
      ->workwith($mii)
        ->select('title', 'title')
        ->where('language_id', LANGUAGE)
      ->execute();
  }

  protected function page_list($options)
  {

    return tx('Sql')->table('cms', 'Pages')
      ->join('LayoutInfo', $li)
      ->join('ComponentViewInfo', $cvi)
      ->select("$li.title", 'layout_title')
      ->select("$cvi.title", 'view_title')
      ->where("$cvi.lang_id", LANGUAGE)
      ->where('trashed', 0)
      ->order('title')
    ->execute();
  }

  protected function configbar()
  {

    return array(
      'items' => tx('Sql')
        ->table('cms', 'ComponentViews')
          ->join('ComponentViewInfo', $cvi)
            ->inner()
          ->join('Components', $com)
          ->where('is_config', 1)
        ->workwith($cvi)
          ->select('title', 'title')
          ->select('description', 'description')
          ->where('lang_id', LANGUAGE)
        ->workwith($com)
          ->select('name', 'component_name')
        ->execute()
    );

  }

  protected function admin_toolbar()
  {

    return array(
      'website_url'=>url(URL_BASE.'?menu=KEEP&pid=KEEP', true),
      'edit_url'=>url(URL_BASE.'?action=cms/editable', true),
      'advanced_url'=>url(URL_BASE.'admin/?menu=KEEP&pid=KEEP', true),
      'admin_url'=>url(URL_BASE.'admin/?project_id=KEEP', true)
    );

  }

}
