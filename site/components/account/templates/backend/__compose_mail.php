<?php namespace components\account; if(!defined('TX')) die('No direct access.');
$uid = tx('Security')->random_string(20);
?>
<form method="post" action="<?php echo url('action=account/send_mail/post'); ?>" class="form compose-mail-form">

  <div class="ctrlHolder">
    <label for="l_recievers" accesskey="e"><?php __('Reciever(s)'); ?></label>
    <?php echo $data->users->as_options('recievers[]', 'email', 'id', array('default' => $data->default_user_email, 'tooltip' => 'username', 'multiple' => true)); ?>
  </div>

  <div class="ctrlHolder">
    <label for="l_username" accesskey="g"><?php __('Subject'); ?></label>
    <input class="big large" type="text" id="l_username" name="subject" value="Een bericht van <?php echo URL_BASE; ?>" />
  </div>

  <div class="ctrlHolder">
    <label for="l_message" accesskey="a"><?php __('Message'); ?></label>
    <textarea id="<?php echo $uid; ?>-message" name="message" class="editor"></textarea>
  </div>

  <div class="buttonHolder">
    <input class="primaryAction button black" type="submit" value="<?php __('Verstuur e-mail'); ?>" />
  </div>

</form>

<script type="text/javascript">

$(function(){

  // Init editor
  tx_editor.init({selector:"#<?php echo $uid; ?>-message", config: {path_ckfinder:"<?php echo URL_PLUGINS; ?>ckfinder/"}});

});

</script>
