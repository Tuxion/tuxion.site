<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.');

class Helpers extends \dependencies\BaseComponent
{
  
  public function is_member($user_id, $project_id)
  {
  
    return tx('Sql')
      ->table('sevendays', 'ProjectMembers')
      ->where('user_id', $user_id)
      ->where('project_id', $project_id)
      ->execute_single()
      ->is_set();
    
  }
  
  //Gets the permissions
  public function get_permissions_for_user($project_id, $user_id)
  {

    $membership = $this->table('ProjectMembers')
      ->where('project_id', $project_id)
      ->where('user_id', $user_id)
      ->execute_single();
    
    return $this->table('UserGroupPermissions')
      ->where('project_id', $project_id)
      ->where('user_group_id', $membership->user_group_id)
      ->join('UserGroups', $ug)
      ->select("$ug.title", 'title')
      ->execute_single();
    
  }
  
  public function get_user_info($project_id, $user_id=null)
  {
    
    if( ! $this->validate_user_view_rights($project_id, tx('Account')->user->id, $user_id) ){
      throw new \exception\Authorisation('You are not authorised to view this user.');
    }
    
    $user = $this->table('ProjectMembers', $PM)
      ->join('Accounts', $A)
      ->join('UserInfo', $UI)
      ->join('Remarks', $R)
      ->where('user_id', $user_id)
      ->where('project_id', $project_id)
    ->workwith($A)
      ->select('id', 'id')
      ->select('email', 'email')
    ->workwith($UI)
      ->select('avatar_image_id', 'image_id')
      ->select('name', 'name')
      ->select('preposition', 'preposition')
      ->select('family_name', 'family_name')
    ->workwith($R)
      ->select('text', 'remarks')
    ->execute_single()
      ->is('empty', function(){
        throw new \exception\EmptyResult('The requested user could not be detected amongst the poject members.');
      });
    
    return $user;
    
  }
  
  public function get_user_postits($project_id, $user_id, $question_id=null)
  {
    
    raw($project_id, $user_id, $question_id);
    
    if( ! $this->validate_user_view_rights($project_id, tx('Account')->user->id, $user_id) ){
      throw new \exception\Authorisation('You are not authorised to view this user.');
    }
    
    $results = $this->table('QuestionPostits')
      ->join('Questions', $qu)
      ->where('user_id', $user_id)
      ->where("$qu.project_id", $project_id)
      ->is(is_numeric($question_id), function($q)use($question_id){
        $q->where('question_id', $question_id);
      })
      ->execute();
      
    //loop results and validate if the viewer has rights
    foreach($results as $result){
      if( ! $this->validate_user_view_rights($project_id, tx('Account')->user->id, $result->author_id) ){
        $result->un_set();
      }
    }
    
    return $results;
    
  }
  
  public function get_user_icons($project_id, $user_id, $question_id=null)
  {
  
    raw($project_id, $user_id, $question_id);
    
    if( ! $this->validate_user_view_rights($project_id, tx('Account')->user->id, $user_id) ){
      throw new \exception\Authorisation('You are not authorised to view this user.');
    }
    
    $results = $this->table('QuestionIcons')
      ->join('Questions', $qu)
      ->join('Icons', $icons)
      ->where('user_id', $user_id)
      ->where("$qu.project_id", $project_id)
      ->is(is_numeric($question_id), function($q)use($question_id){
        $q->where('question_id', $question_id);
      })
    ->workwith($icons)
      ->join('Images', $img)
      ->select("$img.id", 'image_id')
    ->execute();
      
    //loop results
    foreach($results as $result)
    {
      
      //add an image_src
      $result->image_src->set(url("?section=media/image&id={$result->image_id}&resize=0/75", true)->output);
      
      
      //validate if the viewer has rights
      if( ! $this->validate_user_view_rights($project_id, tx('Account')->user->id, $result->author_id) ){
        $result->un_set();
      }
      
    }
    
    return $results;
    
  }
  
  public function get_user_images($project_id, $user_id, $question_id=null)
  {
    
    raw($project_id, $user_id, $question_id);
    
    if( ! $this->validate_user_view_rights($project_id, tx('Account')->user->id, $user_id) ){
      throw new \exception\Authorisation('You are not authorised to view this user.');
    }
    
    $results = $this->table('QuestionImages')
      ->join('Questions', $qu)
      ->join('Images', $image)
      ->where('user_id', $user_id)
      ->where("$qu.project_id", $project_id)
      ->is(is_numeric($question_id), function($q)use($question_id){
        $q->where('question_id', $question_id);
      })
      ->select("$image.id", 'image_id')
    ->execute();
      
    //loop results
    foreach($results as $result)
    {
      
      //add an image_src
      $result->image_src->set(url("?section=media/image&id={$result->image_id}", true)->output);
      
      
      //validate if the viewer has rights
      if( ! $this->validate_user_view_rights($project_id, tx('Account')->user->id, $result->author_id) ){
        $result->un_set();
      }
      
    }
    
    return $results;
    
  }
  
  public function get_user_($thing, $project_id, $user_id, $question_id=null)
  {
    
    return null;
    
  }
  
  public function validate_user_view_rights($project_id, $viewer_id, $viewee_id)
  {
    
    static $validated = array();
    
    raw($project_id, $viewer_id, $viewee_id);
    
    if(array_key_exists("$viewer_id.$viewee_id", $validated)){
      return $validated["$viewer_id.$viewee_id"];
    }
    
    $valid = false;
    
    //validate if the user is requesting its own data
    if(!$valid && ($viewer_id == $viewee_id)){
      $valid = true;
    }
    
    //validate if the viewer is in a group which has rights to view the viewee
    if(!$valid)
    {
      
      //Get membership of the viewer.
      $viewer_membership = $this->table('ProjectMembers')
        ->where('project_id', $project_id)
        ->where('user_id', $viewer_id)
        ->execute_single();
      
      //Get membership of the viewee.
      $viewee_membership = $this->table('ProjectMembers')
        ->where('project_id', $project_id)
        ->where('user_id', $viewee_id)
        ->execute_single();
      
      
      //If there's no membership for either the viewee or viewer then no viewing rights.
      if($viewee_membership->is_set() && $viewer_membership->is_set())
      {
        
        //Get inter group permissions.
        $inter_group_permissions = $this->table('InterUserGroupPermissions')
          ->where('project_id', $project_id)
          ->where('user_group_id', $viewer_membership->user_group_id)
          ->where('target_user_group_id', $viewee_membership->user_group_id)
          ->execute_single();
        
        //If no inter group permissions available, default is no permissions.
        if($inter_group_permissions->is_set()){
          
          //Set the value from the permissions retrieved.
          $valid = $inter_group_permissions->can_view->get('boolean');
          
        }
        
      }
      
    }
    
    $validated["$viewer_id.$viewee_id"] = $valid;
    
    return $valid;
    
  }
  
  public function validate_node_data($data)
  {
    
    if( ! tx('Account')->user->check('login') ){
      throw new \exception\Authorisation('You are not logged in!');
    }
    
    $data->question_id->validate('Question identifier', array('required', 'number' => 'int'));
    $data->user_id->validate('User identifier', array('required', 'number' => 'int'));
    $data->author_id->validate('Author identifier', array('required', 'number' => 'int'));
    $data->x->validate('Horizontal location', array('number' => 'int'));
    $data->y->validate('Vertical location', array('number' => 'int'));
    $data->z->validate('Layer number', array('number' => 'int'));
    
    if( $data->author_id->get('int') !== tx('Account')->user->id->get('int') ){
      throw new \exception\Authorisation('You are not allowed to edit other users their stuff.');
    }
    
    if($data->image_id->is_set())
    {
    
      $this->table('UserImages')
        ->where('project_id', tx('Data')->get->project_id)
        ->where('user_id', tx('Account')->user->id)
        ->where('image_id', $data->image_id)
      ->execute_single()
        ->is('empty', function(){
          throw new \exception\Authorisation('You can only use your own images.');
        });
      
    }
    
    if($data->icon_id->is_set())
    {
      
      //TODO: validate if the icon is in an iconset allowed to use (in this project on this question)
      //$this->table('Icons');
      
    }
    
  }
  
  public function get_question_image($project_id, $question_id)
  {
    
    raw($project_id, $question_id);
    
    $result = $this->table('Questions')
      ->join('Images', $img)
      ->select("$img.filename", 'img')
      ->where('project_id', $project_id)
      ->where('id', $question_id)
      ->execute_single();
    
    return $result->img;
    
  }
  
}
