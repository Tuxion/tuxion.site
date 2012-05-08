<?php namespace components\cms; if(!defined('TX')) die('No direct access.');?>

<nav id="menu-items-toolbar">
  <ul class="_menu toolbar">
    <li><a href="<?php echo url('section=cms/app&menu=0', true); ?>" id="btn-new-menu-item"><?php __('New menu item'); ?></a></li>
    <li><a href="<?php echo url('section=cms/menu_items'); ?>" id="btn-refresh-menu-items"><?php __('Refresh menu items'); ?></a></li>
    <li class="menu-state" id="dropdown-menu">
      <a href="<?php echo url('section=menu/json_update_menu_items') ?>" id="btn-save-menu-items"><?php __('Save menu items'); ?></a>
      <a href="#" id="user-message"><?php __('Saved successfully'); ?></a>
      <a href="#" id="btn-select-menu"><?php __('Main menu'); ?></a>
    </li>
  </ul>
</nav>

<?php

$menu_items

  ->not('empty')

  ->success(function($menu_items){
    echo $menu_items->as_hlist('_menu menu-items-list nestedsortable', function($item, $key, $delta, &$properties){

      $properties['class'] = 'depth_'.$item->depth;

      return
        '<div>'.
        '  <a href="'.url('menu='.$item->id.'&pid='.$item->page_id, true).'">'.$item->title.'</a>'.
        '  <span href="'.url('action=menu/menu_item_delete&item_id='.$item->id).'" class="small-icon icon-delete"></span>'.
        '</div>';
    });
  })

  ->failure(function(){
    __('No menu items found.');
  });

?>

<script type="text/javascript">

  $(function(){
		$('ul.menu-items-list').nestedSortable({
			disableNesting: 'no-nest',
			forcePlaceholderSize: true,
			handle: 'div',
			helper:	'clone',
			listType: 'ul',
			items: 'li',
			maxLevels: 6,
			opacity: .6,
			placeholder: 'placeholder',
			revert: 250,
			tabSize: 25,
			tolerance: 'pointer',
			toleranceElement: '> div'
		})

    .on('sortupdate', function(){

      $('#btn-refresh-menu-items').addClass('revert');
      $('#dropdown-menu').removeClass('menu-state').addClass('save-state');

    });

		$('#btn-save-menu-items').click(function(e){

      e.preventDefault();

      $.ajax({
        url: $(this).attr('href'),
        type: 'POST',
        dataType: 'JSON',
        data: {
          menu_items: $('.menu-items-list').nestedSortable('toArray', {startDepthCount: 0, attribute: 'rel', expression: (/()([0-9]+)/), omitRoot: true})
        }
      })

      .done(function(d){

        //Change revert icon back into refresh icon.
        $('#btn-refresh-menu-items').removeClass('revert');

        //Show success message.
        $('#dropdown-menu').removeClass('save-state').addClass('message-state');

        //Show menu dropdown menu again.
        setTimeout(function(){
          $('#dropdown-menu').removeClass('message-state').addClass('menu-state');
        }, 2000);

      })

      //If something went wrong: throw error message.
      .fail(function(d){
        alert('Something went wrong. Please check your internet connection and try again.');
      });

    });

    /* =New menu item
    -------------------------------------------------------------- */
    $("#btn-new-menu-item").on('click', function(e){

      e.preventDefault();

      $.ajax({
        url: $(this).attr('href')
      }).done(function(d){
        $("#page-main-right").html(d);
      });

    });

    /* =Revert/refresh menu
    -------------------------------------------------------------- */
    $("#btn-refresh-menu-items").on('click', function(e){

      e.preventDefault();

      $.ajax({
        url: $(this).attr('href')
      }).done(function(d){
        $("#page-main-left .content .inner").html(d);
      });

    });

    /* =Delete menu item
    -------------------------------------------------------------- */

    $(".menu-items-list .icon-delete").on("click", function(){

      if(confirm("Weet u zeker dat u dit menu-item wilt verwijderen?")){

        var _this = $(this);

        $.ajax({
          url: $(this).attr("href")}
        ).done(function(){
          $(_this).closest("li").slideUp();
        });

      }

    });

	});


</script>

<style type="text/css">


/* =Nested sortable styles
-------------------------------------------------------------- */

  .nestedsortable .placeholder{
    background-color: #cfcfcf;
    margin-left:40px;
  }
  .nestedsortable li .placeholder{
    margin-left:60px;
  }
  .nestedsortable li li .placeholder{
    margin-left:80px;
  }
  .nestedsortable li li li .placeholder{
    margin-left:100px;
  }
  .nestedsortable li li li li .placeholder{
    margin-left:120px;
  }
  .nestedsortable li li li li li .placeholder{
    margin-left:140px;
  }

  .nestedsortable .ui-nestedSortable-error {
    background:#fbe3e4;
    color:#8a1f11;
  }

</style>
