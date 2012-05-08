<?php namespace templates; if(!defined('TX')) die('No direct access.'); ?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1">
  
    <title>Tuxion webdevelopment</title>
    <base href="<?php echo $head->base->href; ?>" target="<?php echo $head->base->target; ?>" />

    <!-- character encoding -->
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />

    <!-- seo -->
    <meta name="description" content="Tuxion" />
    <meta name="author" content="Tuxion" />
    <meta name="robots" content="noindex, nofollow" />
    <meta name="revisit-after" content="15 days" />

    <?php echo $head->meta; ?>
    
    <?php echo $head->links; ?>
    
    <?php echo $head->plugins; ?>

    <?php echo $head->scripts; ?>

    <?php echo $head->theme; ?>

  </head>
  
  <body>

<div id="container">
  
  <?php echo $body->content; ?>

  <footer id="footer">
  </footer>

</div>
<!-- /#container -->

  </body>
</html>