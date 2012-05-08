<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.'); tx('Account')->page_authorisation(2);
$form_id = tx('Security')->random_string(20);
?>

<div id="tuxion-item-form">

  <h3>Nieuw item</h3>

  <form method="post" id="form<?php echo $form_id; ?>" action="<?php echo url('action=tuxion/save_item/post'); ?>" class="form edit-tuxion-item-form">

    <input type="hidden" name="id" value="<?php echo $edit_item->item->id ?>" />

    <div class="ctrlHolder">
      <label for="l_category_id" accesskey="c"><?php __('Category_id'); ?></label>
      <input class="big large" type="text" id="l_category_id" name="category_id" value="<?php echo $edit_item->item->category_id; ?>" />
    </div>

    <div class="ctrlHolder">
      <label for="l_title" accesskey="t"><?php __('Titel'); ?></label>
      <input class="big large" type="text" id="l_title" name="title" value="<?php echo $edit_item->item->title; ?>" required />
    </div>

    <div class="ctrlHolder">
      <label for="l_description" accesskey="d"><?php __('Description'); ?></label>
      <textarea name="description" id="<?php echo $form_id; ?>-description" class="editor"><?php echo $edit_item->item->description; ?></textarea>
    </div>

    <div class="ctrlHolder">
      <label for="l_text" accesskey="x"><?php __('Text'); ?></label>
      <textarea name="text" id="<?php echo $form_id; ?>-text" class="editor"><?php echo $edit_item->item->text; ?></textarea>
    </div>

    <div class="ctrlHolder">
      <label for="l_image_id" accesskey="i"><?php __('Image id'); ?></label>
      <input class="big large" type="text" id="l_image_id" name="image_id" value="<?php echo $edit_item->item->image_id; ?>" />
    </div>

    <div class="buttonHolder">
      <input class="primaryAction button black" type="submit" value="<?php __('Save'); ?>" />
    </div>

  </form>

</div>

<script type="text/javascript">

$(function(){

  //init editor
  tx_editor.init({selector:"#<?php echo $form_id; ?>-description"});
  tx_editor.init({selector:"#<?php echo $form_id; ?>-text"});

  //submit form
  $("#form<?php echo $form_id; ?>").on("submit", function(e){

    e.preventDefault();
  
    $("#form<?php echo $form_id; ?>").ajaxSubmit(function(d){
      $('#tuxion-item-form').replaceWith(d);
    });

  });
  
});
</script>
