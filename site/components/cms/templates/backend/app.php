<?php namespace components\cms; if(!defined('TX')) die('No direct access.'); ?>

<div id="page-main">

  <div id="page-main-left">

    <?php echo $app->menus; ?>

  </div>
  <!-- /PAGE-MAIN-LEFT -->

  <div id="page-main-right">
    <?php echo $app->app; ?>
  </div>
  <!-- /PAGE-MAIN-RIGHT -->

</div>
<!-- /PAGE-MAIN -->

<div id="page-topbar">

  <!-- LOGO -->
  <h1 id="logo"><a href="<?php echo url('', true); ?>"></a></h1>
  <!-- /LOGO -->

  <?php echo $app->topbar; ?>

  <div class="clear"></div>

</div>
<!-- /PAGE-TOPBAR -->

<?php echo $app->feedback_form; ?>
