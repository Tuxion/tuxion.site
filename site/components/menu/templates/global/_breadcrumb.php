<?php namespace components\menu; if(!defined('TX')) die('No direct access.');

echo '<span>Home</span>';

$breadcrumb
  ->menu_items
  ->not('empty')
  ->success(function($items)use(&$last_menu_item){
    $items->each(function($item, $i)use(&$last_menu_item){
      if($item->title->get() != 'Home'){
        echo ' / <span>'.$item->title.'</span>';
        $last_menu_item = $item->title;
      }
    });
  })

  ->failure(function(){
    __('<!-- No breadcrumb items found. -->');
  });

$page_title =
  trim(str_replace(array('tekst', 'nog bekijken', 'gedaan', 'Nog uitbreiden?', 'nog wel naar info kijken'), '', $breadcrumb->page_info->title->get()));

if(!empty($last_menu_item) && $page_title != $last_menu_item){
  echo ' / <span>'.$page_title.'</span>';
}

?>
