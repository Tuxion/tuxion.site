<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.'); tx('Account')->page_authorisation(2);

?>

<div id="tuxion-item-list">

  <a href="<?php echo url('section=tuxion/edit_item'); ?>" id="new-item">Nieuw item</a>

  <?php

  echo $item_list->items->as_table(array(
    'Datum' => 'dt_created',
    'Titel' => 'title',
    __('actions', 1) => array(
      function($row)use($item_list){return '<a class="edit" href="'.url('section=tuxion/edit_item&item_id='.$row->id).'">'.__('edit', 1).'</a>';},
      function($row){return '<a class="delete" href="'.url('action=tuxion/delete_item&item_id='.$row->id).'">'.__('delete', 1).'</a>';}
    )
  ));

  ?>

</div>

<script type="text/javascript">

$(function(){

  //delete and edit
  $("#tuxion-item-list")
  
    .on("click", ".edit, #new-item", function(e){
  
      e.preventDefault();
      
      $.ajax({
        url: $(this).attr("href")
      }).done(function(d){
        $("#tuxion-item-list").replaceWith(d);
      });
  
    })
    
    /* ---------- Delete item ---------- */
    .on('click', ".delete", function(e){

      e.preventDefault();

      if(confirm("<?php __('Are you sure?'); ?>")){

        $(this).closest('tr').fadeOut();

        $.ajax({
          url: $(this).attr('href')
        });
      }

    });
  });

</script>