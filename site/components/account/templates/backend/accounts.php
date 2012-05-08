<?php namespace components\account; if(!defined('TX')) die('No direct access.'); tx('Account')->page_authorisation(2); ?>

<h1><?php echo __('Gebruikers'); ?></h1>

<div class="tabs" id="tabs-accounts">

  <!-- TABS -->
  <ul>
    <li id="tabber-users" class="active"><a href="#tab-users"><?php __('Summary'); ?></a></li>
    <li id="tabber-user"><a href="#tab-user"><?php __('New user'); ?></a></li>
    <li id="tabber-mail"><a href="#tab-mail"><?php __('Mail'); ?></a></li>
  </ul>
  <!-- /TABS -->

  <!-- CONTENT -->

  <!-- users -->
  <div id="tab-users" class="tab-content">
    <?php echo $accounts->users; ?>
  </div>

  <div id="tab-user" class="tab-content">
    <?php echo $accounts->new_user; ?>
  </div>

  <div id="tab-mail" class="tab-content">
    <?php echo $accounts->compose_mail; ?>
  </div>

  <!-- /CONTENT -->

</div>

<script type="text/javascript">
  $(function(){

    $("#tabs-accounts ul").idTabs(function(id){

      if(id != "#tab-user" || $("#tab-user").find("input[name=id]").val() == ""){
        $("#tabber-user").find("a").text("<?php __('New user'); ?>");
        $("#tab-user").find(':input:not([type=submit], [type=checkbox], [type=radio])').val('');
      }

      return true;

    });

  });

  $(function(){

    $('#tabs-accounts').on('submit', '.edit-user-form', function(e){

      e.preventDefault();

      $(this).ajaxSubmit(function(d){
        $('#tab-users').html(d);
        $('#tabber-users a').trigger('click');
      });

    });

  });

</script>
