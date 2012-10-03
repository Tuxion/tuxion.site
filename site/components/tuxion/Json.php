<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.');

class Json extends \dependencies\BaseComponent
{
  
  /* ---------- Frontend ---------- */

  public function get_items($data, $args)
  {

    $that = $this;
    
    return tx('Fetching items.', function()use($data, $args, $that, &$return){

      $result = $that->table('Items')
        ->join('Categories', $c)->left()
          ->select("$c.name", 'category_name')
          ->select("$c.title", 'category_title')
          ->select("$c.color", 'category_color')
        ->join('Accounts', $a)->left()
          ->select("$a.first_name", 'first_name')
        ->is($filter->id->is('set')->and_not('empty'), function($q)use($filter){
          $q->where('id', $filter->id);
        })
      ->order('dt_created')
      ->execute();

    });

  }
  
  //Get the closest (default 50) items to the given item id.
  public function get_closest($data, $args)
  {

    $that = $this;
    
    tx('Fetching the closest items.', function()use($data, $args, $that, &$return){
      
      //The total amount of items we should end up with. Defaults to 50.
      $amount = $data->amount->otherwise(50)->get();
      $half = floor($amount/2);
      
      //The id of the item "in the middle".
      $id = $args[0]->validate('item identifier', array('number' => 'int'))->otherwise(false)->get();
      
      //Get information about the item itself.
      if($id){
        $item = $that
          ->table('Items')
          ->join('Categories', $c)->left()
            ->select("$c.name", 'category_name')
            ->select("$c.title", 'category_title')
            ->select("$c.color", 'category_color')
          ->join('Accounts', $a)->left()
            ->select("$a.username", 'username')
          ->pk($id)
          ->execute_single();
      }
      
      //No item? Use the first.
      if(!$id || $item->is_empty()){
        $item = $that
          ->table('Items')
          ->join('Categories', $c)->left()
            ->select("$c.name", 'category_name')
            ->select("$c.title", 'category_title')
            ->select("$c.color", 'category_color')
          ->join('Accounts', $a)->left()
            ->select("$a.username", 'username')
          ->order('dt_created', 'DESC')
          ->execute_single();
      }
      
      //No items at all?
      if($item->is_empty()){
        return array('first', 'last');
      }
      
      //Get items before the middle item.
      $before = $that->table('Items')
        ->join('Categories', $c)->left()
          ->select("$c.name", 'category_name')
          ->select("$c.title", 'category_title')
          ->select("$c.color", 'category_color')
        ->join('Accounts', $a)->left()
          ->select("$a.username", 'username')
        ->where('dt_created', '>', $item->dt_created)
        ->order('dt_created', 'ASC')
        ->limit($half)
      ->execute()
      ->convert('reverse');
        
      //Get items after the middle item.
      $after = $that->table('Items')
        ->join('Categories', $c)->left()
          ->select("$c.name", 'category_name')
          ->select("$c.title", 'category_title')
          ->select("$c.color", 'category_color')
        ->join('Accounts', $a)->left()
          ->select("$a.username", 'username')
        ->where('dt_created', '<', $item->dt_created)
        ->order('dt_created', 'DESC')
        ->limit($half)
      ->execute();
      
      //First? Prepend "first".
      if($before->size() < $half){
        $before = $before->reverse()->push('first')->reverse();
      }
      
      //Last? Append "last".
      if($after->size() < $half){
        $after->push('last');
      }
      
      $return = array('item' => $item, 'before' => $before, 'after' => $after);
      
    });

    return $return;
    
  }

  public function get_item($data, $args)
  {

    $that = $this;
    
    tx('Fetching item.', function()use($data, $args, $that, &$return){
      
      $args[0]->validate('item identifier', array('required', 'number' => 'int'));

      $result =$that
        ->table('Items')
        ->join('Categories', $c)->left()
          ->select("$c.name", 'category_name')
          ->select("$c.title", 'category_title')
          ->select("$c.color", 'category_color')
        ->join('Accounts', $a)->left()
          ->select("$a.username", 'username')
        ->pk($args[0])
        ->execute_single();
      
      $result->is('empty', function()use($args){
        throw new \exception\EmptyResult('Item %s was not found.', $args[0]);
      });
      
      $return = $result;

    });

    return $return;

  }

}
