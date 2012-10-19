<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.'); tx('Account')->page_authorisation(2);

?>

<div id="tuxion-item-list">

  <table cellpadding="0" cellspacing="0">
    <tr>
      <th colspan="6"><a href="<?php echo url('section=tuxion/edit_item'); ?>" class="new-item">nieuw item</a></th>
    </tr>
    <?php $item_list->items->each(function($row){ ?>
    <tr>
      <td><?php echo $row->dt_created; ?></td>
      <td><?php echo $row->title; ?></td>
      <td><?php echo substr(strip_tags($row->description), 0, 50); ?></td>
      <td><?php echo $row->user->username; ?></td>
      <td hidden><a class="edit" href="<?php echo url('section=tuxion/edit_item&item_id='.$row->id); ?>">edit</a></td>
      <td><a class="delete" href="<?php echo url('action=tuxion/delete_item&item_id='.$row->id); ?>">delete</a></td>
    </tr>
    <?php }); ?>
    <tr>
      <th colspan="6"><a href="<?php echo url('section=tuxion/edit_item'); ?>" class="new-item">nieuw item</a></th>
    </tr>
  </table>

</div><!-- #tuxion-item-list -->

<script type="text/javascript">

$(function(){

//delete and edit
$("#tuxion-item-list")

  .on("click", ".new-item", function(e){

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

  })

  .on("click", "tr", function(e){

    e.preventDefault();
    
    $('#tuxion-item-list').slideUp(function(){
      $('#tuxion-item-list').html('Loading... brb').slideDown();
    });

    $.ajax({
      url: $(this).find('.edit').attr("href")
    }).done(function(d){
      $("#tuxion-item-list").slideUp().replaceWith(d).slideDown();
    });

  })

});

</script>