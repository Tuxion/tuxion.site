<?php namespace components\cms; if(!defined('TX')) die('No direct access.');

class Helpers extends \dependencies\BaseComponent
{

  public function get_page_info($pid)
  {
    
    static $page_info = array();
    $pid = data_of($pid);
    
    if(array_key_exists($pid, $page_info)){
      return $page_info[$pid];
    }
    
    $return = Data();
    
    $result = tx('Sql')->table('cms', 'Pages', $p)
      ->pk($pid)
      ->join('Templates', $te)
      ->join('Themes', $th)
      ->join('ComponentViews', $cv)
      ->select("$te.name", 'template')
      ->select("$th.name", 'theme')
      ->select("$cv.name", 'view_name')
    ->workwith($cv)
      ->join('Components', $co)
      ->select("$co.name", 'component')
    ->execute_single();

    if($result->is_empty()){
      return false;
    }
    
    $page_info[$pid] = $result;
    
    return $result;
  
  }
  
}