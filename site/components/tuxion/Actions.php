<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.');

class Actions extends \dependencies\BaseComponent
{

  protected function send_mail($data)
  {

    tx('Component')->helpers('mail')->send_fleeting_mail(array(
      'to' => array('name'=>'team@tuxion.nl'),
      'subject' => 'web.tuxion.nl | '.$data->subject,
      'html_message' => tx('Component')->views('tuxion')->get_html('email_template', $data->having('name', 'company', 'email', 'phone', 'subject', 'message'))
    ))
    
    ->failure(function($info){
      throw $info->exception;
    });
    
  }

  protected function save_item($data)
  {
    
    $item_id = 0;
    tx($data->id->get('int') > 0 ? 'Updating a tuxion item.' : 'Adding a new tuxion item', function()use($data, &$item_id){
      
      //append user object for easy access
      $user_id = tx('Data')->session->user->id;
      
      $data->image_id = $data->image_id->otherwise(0);

      //save item
      $item = tx('Sql')->table('tuxion', 'Items')->pk($data->id->get('int'))->execute_single()->is('empty')
        ->success(function()use($data, $user_id, &$item_id){
          tx('Sql')->model('tuxion', 'Items')->merge($data->having('dt_created', 'category_id', 'image_id', 'url_key', 'title', 'description', 'text', 'user_id'))->save();
          $item_id = mysql_insert_id();
        })
        ->failure(function($item)use($data, $user_id, &$item_id){
          $item->merge($data->having('dt_created', 'category_id', 'image_id', 'url_key', 'title', 'description', 'text', 'user_id'))->merge(array('dt_last_modified', date("Y-m-d H:i:s")))->save();
          $item_id = $item->id->get('int');
        });

    })
    
    ->failure(function($info){
      throw $info->exception;
    });

    tx('Url')->redirect(url('section=tuxion/item_list&item_id=NULL'));
    
  }

  protected function delete_item($data)
  {
    $item = tx('Sql')->table('tuxion', 'Items')->pk($data->item_id)->execute_single()->is('empty', function()use($data){
      throw new \exception\User('Could not delete this item, because no entry was found in the database with id %s.', $data->id);
    })
    ->delete();
  }
 

}
