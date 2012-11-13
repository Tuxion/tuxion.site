<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.'); tx('Account')->page_authorisation(2);
$form_id = tx('Security')->random_string(20);
?>

<a href="#" class="back-to-overview cancel">naar het item-overzicht</a>

<div id="tuxion-item-form">

  <form method="post" id="form<?php echo $form_id; ?>" action="<?php echo url('action=tuxion/save_item/post'); ?>" class="form edit-tuxion-item-form">

    <input type="hidden" name="id" value="<?php echo $edit_item->item->id ?>" />

    <div class="ctrlHolder user_id">
      <label for="l_user_id" accesskey="c"><?php __('User'); ?></label>
      <?php echo $data->users->as_options('user_id', 'username', 'id', array('id' => 'l_user_id', 'default' => $data->item->user_id->otherwise(tx('Account')->user->id), 'placeholder_text' => __('Who are you? Who do you pretend to be?', 1))); ?>
    </div>

    <div class="ctrlHolder category_id">
      <label for="l_category_id" accesskey="c"><?php __('Category'); ?></label>
      <?php echo $data->categories->as_options('category_id', 'title', 'id', array('id' => 'l_category_id', 'default' => $data->item->category_id->get('int'), 'placeholder_text' => __('Select a category', 1))); ?>
    </div>

    <div class="ctrlHolder dt_created">
      <label for="l_dt_created" accesskey="t"><?php __('Date/time created'); ?></label>
      <input class="big large" type="text" id="l_dt_created" name="dt_created" value="<?php echo $data->item->dt_created->otherwise(date("Y-m-d H:i:s")); ?>" />
    </div>

    <div class="ctrlHolder title">
      <label for="l_title" accesskey="t"><?php __('Titel'); ?></label>
      <input class="big large" type="text" id="l_title" name="title" value="<?php echo $edit_item->item->title; ?>" />
    </div>

    <div class="ctrlHolder">
      <label for="l_description" accesskey="d"><?php __('Description'); ?></label>
      <textarea name="description"><?php echo $edit_item->item->description; ?></textarea>
    </div>

    <div class="ctrlHolder text">
      <label for="l_text" accesskey="x"><?php __('Text'); ?></label>
      <textarea name="text"><?php echo $edit_item->item->text; ?></textarea>
    </div>

    <div class="ctrlHolder" id="item-image-wrapper">
      <label><?php __('Image'); ?></label><br />
      <img id="item-image" height="181" src="<?php echo url('?section=media/image&fill=868/181&allow_growth=false&id='.$data->item->image_id, true); ?>" />
      <input id="item-image-id" type="hidden" name="image_id" value="<?php echo $data->item->image_id; ?>">
    </div>
    
    <div class="ctrlHolder">
      <?php echo $data->image_uploader; ?>
    </div>

    <div class="buttonHolder">
      <a href="#" class="cancel">Annuleren</a>
      <input class="primaryAction button black" type="submit" value="<?php __('Save'); ?>" />
    </div>

  </form>

</div>

<?php if($data->item->image_id->get() <= 0){ ?>
<style>#item-image-wrapper{display:none;}</style>
<?php } ?>

<script type="text/javascript">

$(function(){

  //submit form
  $("#container")

    .on("submit", function(e){

      e.preventDefault();
    
      $("#form<?php echo $form_id; ?>").ajaxSubmit(function(d){
        $('#container').html(d);
      });

    })

    .on('click', '.cancel', function(e){
      
      e.preventDefault();

      $.ajax({
        url: '<?php echo url('section=tuxion/item_list', true); ?>'
      }).done(function(d){
        $('#container').html(d);
      });

    })
  
});

$(function(){

  var form = $('#form<?php echo $form_id; ?>');
  
  //On uploaded file.
  window.plupload_image_file_id = function(up, ids, file_id){
    
    form
      .find('#item-image')
        .attr('src', '<?php echo url('?section=media/image&fill=868/181&allow_growth=false', true); ?>&id='+file_id).end()
      .find('#item-image-wrapper')
        .show();
    
    form.find('#item-image-id')
      .val(file_id);
    
  };

});

</script>
