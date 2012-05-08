<?php namespace components\cms; if(!defined('TX')) die('No direct access.'); tx('Account')->page_authorisation(2); ?>

<?php if($edit_page->page->get() === false): ?>

<div id="edit_page">

  <div class="title-bar page-title">
    <h2><?php __('Page was removed.') ?></h2>
    <ul class="title-bar-icons clearfix" style="visibility:hidden;">
      <?php if(tx('Data')->get->menu->is_set()){ ?><li><a href="<?php echo url('action=cms/detach_page&menu='.tx('Data')->get->menu.'&pid='.$edit_page->page->id); ?>" class="icon detach-page" id="detach-page" title="<?php __('Detach page from menu item'); ?>">Detach page from menu item</a></li><?php } ?>
    </ul>
    <div class="clear"></div>
  </div>

  <div class="body">
    <?php __('This page was deleted from the database. Unlink it from the menu item.'); ?>
  </div>

</div>

<?php return; endif; ?>

<div id="edit_page">

  <div class="title-bar page-title">
    <h2><span class="title"><?php echo $edit_page->page->title; ?></span> <span style="font-weight:normal;">(pagina)</span></h2>
    <ul class="title-bar-icons clearfix">
      <li><a href="#" class="icon page-settings" id="toggle-page-settings" title="<?php __('Toggle page settings'); ?>">Toggle page settings</a></li>
      <?php if(tx('Data')->get->menu->is_set()){ ?><li><a href="<?php echo url('action=cms/detach_page&menu='.tx('Data')->get->menu.'&pid='.$edit_page->page->id); ?>" class="icon detach-page" id="detach-page" title="<?php __('Detach page from menu item'); ?>">Detach page from menu item</a></li><?php } ?>
    </ul>
    <div class="clear"></div>
  </div>

  <div class="header">

    <form method="post" action="<?php echo url("action=cms/edit_page/post&menu=NULL"); ?>" class="form-inline-elements">

      <input type="hidden" name="page_id" id="page_id" value="<?php echo $edit_page->page->id; ?>" />

      <fieldset class="fieldset-general clearfix">

        <div class="inputHolder">
          <label for="page_title"><?php __('Page title'); ?></label>
          <input id="page_title" class="big" type="text" name="title" value="<?php echo $edit_page->page->title ?>" placeholder="<?php __('Page title') ?>" />
        </div>

        <div class="inputHolder last">
          <label for="l_layout"><?php echo __('Layout'); ?></label>
          <select name="layout_id" id="l_layout">
            <?php
            foreach($edit_page->layout_info as $layout){
              echo '<option value="'.$layout->layout_id.'"'.($layout->layout_id->get()===$edit_page->page->layout_id->get() ? ' selected' : '').'>'.$layout->title.'</option>';
            }
            ?>
          </select>
        </div>

      </fieldset>

    <!-- PAGE CONFIG -->
      <div id="page-config">

        <div class="inner">

          <br />
          <br />
          <h3><?php __('Page settings') ?></h3>

          <div class="page-item">

            <div class="left">

              <fieldset class="fieldset-display">

                <legend>Weergave</legend>

                <div class="inputHolder">
                  <label><?php __('Template'); ?></label>
                  <?php echo $edit_page->templates->as_options('template_id', 'title', 'id', array('id' => 'template_id', 'default' => ($edit_page->page->template_id->get('int') > 0 ? $edit_page->page->template_id->get('int') : tx('Config')->user('template_id')), 'placeholder_text' => __('Select a template', 1))); ?>
                </div>

                <div class="inputHolder">
                  <label><?php __('Theme'); ?></label>
                  <?php echo $edit_page->themes->as_options('theme_id', 'title', 'id', array('default' => ($edit_page->page->theme_id->get('int') > 0 ? $edit_page->page->theme_id->get('int') : tx('Config')->user('theme_id')), 'placeholder_text' => __('Select a theme', 1))); ?>
                </div>

  <?php
  /*
                <div id="appearance-slider" style="height:auto;">

                  <div class="toolbar theme">
                    <a href="#" class="btn-prev"></a>
                    <div class="title">
                      <?php
                      echo $edit_page->themes->as_options('theme_id', 'title', 'id', array('default' => ($edit_page->page->theme_id->get('int') > 0 ? $edit_page->page->theme_id->get('int') : tx('Config')->user('theme_id')), 'placeholder_text' => __('Select a theme', 1)));
                      ?>
                    </div>
                    <a href="#" class="btn-next"></a>
                  </div>

  <!--
                  <div class="preview-container">

                  </div>
  -->

                  <div class="toolbar template">
                    <a href="#" class="btn-prev"></a>
                    <div class="title">
                      <?php
                      echo $edit_page->templates->as_options('template_id', 'title', 'id', array('id' => 'template_id', 'default' => ($edit_page->page->template_id->get('int') > 0 ? $edit_page->page->template_id->get('int') : tx('Config')->user('template_id')), 'placeholder_text' => __('Select a template', 1)));
                      ?>
                    </div>
                    <a href="#" class="btn-next"></a>
                  </div>

                </div>
  */
  ?>

              </fieldset>

            </div>

            <div class="right">

  <!--
              <fieldset class="fieldset-variables">

                <legend>Variabelen</legend>

                <label for="variable_1"><?php __('Variable'); ?> 1</label>
                <input id="variable_1" class="big" type="text" name="title" value="<?php echo $edit_page->page->title ?>" placeholder="<?php __('Page title') ?>" />

                <label for="variable_2"><?php __('Variable'); ?> 2</label>
                <input id="variable_2" class="big" type="text" name="title" value="<?php echo $edit_page->page->title ?>" placeholder="<?php __('Page title') ?>" />

              </fieldset>

              <fieldset class="fieldset-metatags">

                <legend>Meta tags</legend>

                <label for="metatags"><?php __('Meta tags'); ?></label>
                <input id="metatags" class="big" type="text" name="title" value="<?php echo $edit_page->page->title ?>" placeholder="<?php __('Page title') ?>" />

              </fieldset>

              <fieldset class="fieldset-publish">

                <legend>Variabelen</legend>

                <label for="variable_1"><?php __('Variable'); ?> 1</label>
                <input id="variable_1" class="big" type="text" name="title" value="<?php echo $edit_page->page->title ?>" placeholder="<?php __('Page title') ?>" />

                <label for="variable_2"><?php __('Variable'); ?> 2</label>
                <input id="variable_2" class="big" type="text" name="title" value="<?php echo $edit_page->page->title ?>" placeholder="<?php __('Page title') ?>" />

              </fieldset>
  -->

              <fieldset class="fieldset-rights">

                <legend>User rights</legend>

                Toegang voor:
                <ul>
                  <li><label><input type="radio" name="access_level" value="0"<?php echo ($edit_page->page->access_level->get('int') <= 0 ? ' checked="checked"' : ''); ?> /> Iedereen</label></li>
                  <li><label><input type="radio" name="access_level" value="1"<?php echo ($edit_page->page->access_level->get('int') == 1 ? ' checked="checked"' : ''); ?> /> Ingelogde gebruikers</label></li>
                  <!--<li><label><input type="radio" name="access_level" value="2"<?php echo ($edit_page->page->access_level->get('int') == 2 ? ' checked="checked"' : ''); ?> class="members" /> Groepsleden</label></li>-->
                  <li><label><input type="radio" name="access_level" value="3"<?php echo ($edit_page->page->access_level->get('int') == 3 ? ' checked="checked"' : ''); ?> /> Beheerders</label></li>
                </ul>

                <fieldset class="fieldset-groups">

                  <legend>Groepen met toegang</legend>

                  <ul>
                    <li><label><input type="checkbox" name="permission_groups[]" value="id" /> Titel van groep</label></li>
                  </ul>

                </fieldset>

              </fieldset>

            </div>

            <div class="clear"></div>

          </div>

        </div>

      </div><!-- eof:#page-config -->

    </form>

    
  </div><!-- eof:.header -->

  <div class="body">

    <div id="page_content">

      <div class="inner">
        <h3>Pagina-inhoud</h3>
        <?php echo $edit_page->content; ?>
      </div>

    </div><!-- eof:#page-content -->
    
    <div class="reset"></div>    

  </div>
  
  <div class="footer">

    <button id="save-page" class="button black"><?php __('Save'); ?></button>
    <button id="save-page-return" href="<?php echo url(('section='.(tx('Data')->get->pid->is_set() ? 'cms/config_app&view=cms/pages' : 'cms/app')), true); ?>" class="button grey"><?php __(htmlspecialchars('Save and return')); ?></button>
    <button id="cancel-page" href="<?php echo url(('section='.(tx('Data')->get->pid->is_set() ? 'cms/config_app&view=cms/pages' : 'cms/app')), true); ?>" class="button grey"><?php __('Cancel'); ?></button>

  </div>

</div>

<script type="text/javascript">

// $('select').selectmenu({style: 'dropdown'});

var com_cms = (function(TxComCms){

  var //private properties
    defaults = {
    }

  //public init(o)
  TxComCms.init_edit_page = function(){

    //page permissions
    $('.fieldset-rights')

      .on('click', 'input[name=access_level]', function(){
        if($(this).hasClass('members')){
          $('.fieldset-groups').show();
        }else{
          $('.fieldset-groups').hide();
        }
      });

    $('#edit_page')

      //Toggle page settings.        
      .on('click', '#toggle-page-settings', function(){
        $('#page-config').toggle();
        $('#page_content').toggle();
      })
      $('#toggle-page-settings').toggleClass('page-content')

      //Update page title on change.
      .on('keyup', '#l_title_page', function(){
        $('#edit_page .title-bar.page-title .title').text($(this).attr('value'));
      });

/*    //layout select
    $('#edit_page select[name=layout_id]').change(function(e){

      e.preventDefault();

      $.ajax({
        data : {
          part: $(this).children(':selected').val().toInt(),
          pid: <?php echo $edit_page->page->page_info->id->get('int'); ?>
        }
      });

    }); */
    
    //cycle themes
    $('#appearance-slider')

      .on('click', '.theme a.btn-prev', function(e){
        e.preventDefault();
        var to_select = $('.theme .tx-select').find('option:selected').prev('option');
        $('.theme .tx-select option').removeAttr('selected');
        $(to_select).attr('selected', 'selected');
      })
      .on('click', '.theme a.btn-next', function(e){
        e.preventDefault();
        var to_select = $('.theme .tx-select option:selected').next('option');
        $('.theme .tx-select option').removeAttr('selected');
        $(to_select).attr('selected', 'selected');
      })
      .on('click', '.template a.btn-prev', function(e){
        e.preventDefault();
        var to_select =  $('.template .tx-select option:selected').prev('option');
        $('.template .tx-select option').removeAttr('selected');
        $(to_select).attr('selected', 'selected');
      })
      .on('click', '.template a.btn-next', function(e){
        e.preventDefault();
        var to_select = $('.template .tx-select option:selected').next('option');
        $('.template .tx-select option').removeAttr('selected');
        $(to_select).attr('selected', 'selected');
      });

    $("#detach-page").on("click", function(e){
    
      e.preventDefault();
      
      $.ajax({
        url: $(this).attr("href")
      }).done(function(data){
        $("#page-main-right").html(data);
      });
    
    });
    
    //submit edit_page form
    $('#edit_page .header form').on('submit', function(e){

      e.preventDefault();

      //save page
      if($.isFunction(com_cms.save_page)){
        com_cms.save_page();
      }

      // save page content
      if($.isFunction(com_cms.save_page_content)){
        com_cms.save_page_content();
      }
      $("#page_app form").each(function(){
        $(this).ajaxSubmit({
          data: {
            page_id: <?php echo ($edit_page->page->id->get('int') > 0 ? $edit_page->page->id->get('int') : tx('Data')->get->pid->get('int')); ?>
          },
          success: function(data){
            // alert(data);
          }
        });
      });

      // save menu item
      if($.isFunction(com_cms.save_menu_item)){
        com_cms.save_menu_item();
      }
      
    });
  
    //button:save-page buttonhandler
    $('#save-page').click(function(e){
      e.preventDefault();
      $('#edit_page .header form').trigger('submit');
    });
  
    //button:save-page-return handler
    $('#save-page-return').click(function(e){
      e.preventDefault();
      $('#edit_page .header form').trigger('submit');
      $.ajax({
        url: $(this).attr('href')
      }).done(function(data){
        $("#page-main-right").html(data);
      });
    });

    //button:cancel-page handler
    $('#cancel-page').click(function(e){
      e.preventDefault();
      $.ajax({
        url: $(this).attr('href')
      }).done(function(data){
        $("#page-main-right").html(data);
      });
    });
    
    return this;

  }
  
  //public save_page()
  TxComCms.save_page = function(){
    $("#edit_page .header form").ajaxSubmit();
    return this;
  }
  
  return TxComCms;

})(com_cms||{});

com_cms.init_edit_page();

</script>
