<?php namespace components\cms; if(!defined('TX')) die('No direct access.'); tx('Account')->page_authorisation(2); ?>

<div id="edit-menu-item">
  
  <form id="form-menu-item" method="post" action="<?php echo url('action=cms/edit_menu_item/post'); ?>" class="form-inline-elements">
    
    <div class="title-bar page-title">
      <h2><span class="title"><?php echo $edit_menu_item->info->title; ?></span> <span style="font-weight:normal;">(menu-item)</span></h2>
      <ul class="title-bar-icons clearfix" style="visibility:hidden;">
        <li><a href="#" class="icon" id="detach-menu-item">Detach menu item from page</a></li>
      </ul>
      <div class="clear"></div>
    </div>
    
    <div class="body">
      
      <input type="hidden" name="id" value="<?php echo $edit_menu_item->info->item_id; ?>" />
      <div class="inputHolder">
        <label for="l_title_menu_item"><?php echo __('Menu-item titel'); ?></label>
        <input class="big" type="text" id="l_title" name="title" placeholder="<?php __('Title'); ?>" value="<?php echo $edit_menu_item->info->title; ?>" />
      </div>
      <div class="inputHolder last">
        <label for="l_menu"><?php echo __('Menu'); ?></label>
        <select id="l_menu" name="menu_id">
          <?php
          $edit_menu_item->menus->each(function($menu)use($edit_menu_item){
            echo '<option value="'.$menu->id.'"'.($edit_menu_item->info->menu_id->get('int') === $menu->id->get('int') ? ' selected="selected"' : '').'>'.$menu->title.'</option>';
          });
          ?>
        </select>
      </div>
      <div class="clear"></div>


    </div>
    
    <?php if(tx('Data')->get->menu->is_set() && tx('Data')->get->menu->get('int') == 0){ ?>
    
    <div class="footer">
      
      <input type="submit" id="save-menu-item" value="<?php __('Save'); ?>" />
      
    </div>

    <style type="text/css">

    #edit-menu-item:after {
      background:none;
    }

    </style>
    
    <?php } ?>
  
  </form>
  
</div>


<script type="text/javascript">

var com_cms = (function(TxComCms){

  TxComCms.init_edit_menu_item = function(){

    //submit edit_page form
    $('#form-menu-item').on('submit', function(e){

      e.preventDefault();

      //save page
      if($.isFunction(com_cms.save_page)){
        com_cms.save_page();
      }

      // save page content
      if($.isFunction(com_cms.save_page_content)){
        com_cms.save_page_content();
      }

      // save menu item
      if($.isFunction(com_cms.save_menu_item)){
        com_cms.save_menu_item();
      }
      
    });

    $('#menu_app')
  
      //Update menu item title on change.
      .on('keyup', '#l_title_menu_item', function(){
        $('#menu_app .title-bar.page-title .title').text($(this).attr('value'));
      });

  }

  //public save_menu_item()
  TxComCms.save_menu_item = function(){
    $("#form-menu-item").ajaxSubmit(function(d){

      //show page app if necessary
      if(!($.isFunction(com_cms.save_page))){
        $("#app").replaceWith(d);
      }
      
      //update menu items in left sidebar
      $.ajax({url: "<?php echo url('section=cms/menu_items'); ?>"}).done(function(d){
        $("#page-main-left .content .inner").html(d);
      });

    });
    return this;
  }
  
  return TxComCms;

})(com_cms||{});

com_cms.init_edit_menu_item();

</script>