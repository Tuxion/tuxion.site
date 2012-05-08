<?php namespace components\menu; if(!defined('TX')) die('No direct access.');

class Modules extends \dependencies\BaseViews
{

  /**
   * Returns a result set with the menu items you asked for.
   * 
   * @param array $options Array with options.
   *  @key int $parent_pk The menu item id to select submenu items from.
   *  @key int $min_depth Minimum depth to show items from.
   *  @key int $max_depth Number that indicates how far in submenus it should go.
   *  @key bool $display_select_menu If true: a select menu will be returned.
   *  @key bool $select_from_root If true: select items from root.
   *             tx('Data')->get->menu will be used to calculate root.
   *             $parent_pk will be overwritten.
   */
  protected function menu($options)
  {

    //if $select_from_root is true: select root item to show items from.
    $no_menu_items_found  = false;
    if($options->select_from_root->is_set() && tx('Data')->get->menu->get('int') > 0)
    {
      tx('Sql')->table('menu', 'MenuItems')->pk(tx('Data')->get->menu)->execute_single()->not('empty', function($item)use(&$options){

        tx('Sql')

          ->table('menu', 'MenuItems')

          //filter menu.
          ->sk(tx('Data')->filter('menu')->menu_id->is_set() ? tx('Data')->filter('menu')->menu_id : '1')

          //add absolute depth.
          ->add_absolute_depth('depth')
          
          //set minimum depth to show.
          ->is($options->min_depth->is('set'), function($q)use($options){
            $q->where('depth', '>=', $options->min_depth->get('int'));
          })

          ->where('lft', '<', $item->lft)
          ->where('rgt', '>', $item->rgt)
          ->order('lft')
          ->limit(1)
          ->execute_single()
          ->is('empty', function()use(&$options){
              $options->parent_pk->set(tx('Data')->get->menu);
            })->failure(function($root_item)use(&$options){
              $options->parent_pk->set($root_item->id);
            });

      })->failure(function()use(&$no_menu_items_found){
        $no_menu_items_found = true;
      });
    }
    
    if($no_menu_items_found){
      return array();
    }

    //Get menu items.
    $menu_items =

      tx('Sql')

        ->table('cms', 'MenuItems')

          //filter menu.
          ->sk(tx('Data')->filter('menu')->menu_id->is_set() ? tx('Data')->filter('menu')->menu_id : '1')
          
          //add absolute depth.
          ->add_absolute_depth('depth')
          
          //set the menu item id to select submenu items from.
          ->is($options->parent_pk->is('set')->and_not('empty'), function($q)use($options){
            $q->parent_pk($options->parent_pk->get('int'));
          })

          //set minimum depth to show.
          ->is($options->min_depth->is('set'), function($q)use($options){
            $q->where('depth', '>=', $options->min_depth->get('int'));
          })
          
          //set how far in submenus it should go. 3 will let it go on to a sub-sub-sub menu and no further.
          ->is($options->max_depth->is('set'), function($q)use($options){
            $q->max_depth($options->max_depth->get('int'));
          })

          //join menu item info.
          ->join('MenuItemInfo', $mii)->inner()

          //join page.
          ->join('Pages', $p)
          ->select("$p.access_level", 'access_level')

        ->workwith($mii)

          ->select('title', 'title')
          ->where('language_id', LANGUAGE)

        ->execute();

    //Create menu.
    $menu =

      $menu_items

        ->not('empty')

        //If menu items are found:
        ->success(function($items)use($options){
          
          //Show a 'select menu'.
          if($options->display_select_menu->get('bool') == true)
          {
            //as_options($name, $title, $value[, $default[, $tooltip[, $multiple[, $placeholder_text]]]])
            return $items->as_options('menu', 'title', 'id', array('placeholder_text' => __('Select a season', 1), 'rel' => 'page_id', 'default' => tx('Data')->get->menu));
          }

          //Or show a normal menu.
          else
          {
            return $items->as_hlist('_menu'.($options->classes->is_set() ? ' '.$options->classes->get() : ''), function($item, $key, $delta, &$properties)use(&$active_depth){
              if(tx('Account')->check_level($item->access_level->get('int'))){

                $properties['class'] = '';
                //Add class 'active' if this is the active menu item.
                if($item->id->get('int') === tx('Data')->get->menu->get('int')){
                  $properties['class'] .= 'active';
                  $active_depth = $item->depth->get('int');
                }
                //Add class 'selected' if this is an selected item.
                if($delta < 0 && $item->depth->get('int') == $active_depth -1 || $item->depth->get('int') - $active_depth >= 1){
                  $active_depth = null;
                }
                if(!is_null($active_depth)){
                  // $properties['class'] .= ' selected';
                }

                return '<a href="'.url('menu='.$item->id.'&pid='.$item->page_id, true).'">'.$item->title.'</a>';
              }

            });
          }

        })

        ->failure(function(){
          echo '<!-- '.__('No menu items found.', 1).' -->';
        });

    return array(
      'menu' => $menu
    );

  }

  protected function breadcrumb($options)
  {

    $options->having('menu_item_id')
      ->menu_item_id->validate('Menu item id', array('number', 'gt'=>0))->back();
  
    $menu_item_info =
      tx('Sql')
      ->table('cms', 'MenuItems')
      ->pk($options->menu_item_id)
      ->execute_single();

    //get menu items
    return array(

      'menu_items' =>

        tx('Sql')

          ->table('cms', 'MenuItems', $mi)

            //filter menu
            ->sk(tx('Data')->filter('menu')->menu_id->is_set() ? tx('Data')->filter('menu')->menu_id : '1')
          
            //join menu item info
            ->join('MenuItemInfo', $mii)->inner()

          ->workwith($mii)

            ->select('title', 'title')
            ->where('language_id', LANGUAGE)
            
          ->workwith($mi)
          
            ->where('lft', '<=', $menu_item_info->lft)
            ->where('rgt', '>=', $menu_item_info->rgt)
            ->order('lft', 'DESC')

          ->execute(),

      'page_info' =>

        tx('Sql')
          ->table('cms', 'Pages', $p)
          ->pk(tx('Data')->get->pid)
          ->execute_single()

    );

  }

}