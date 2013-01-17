<?php namespace templates; if(!defined('TX')) die('No direct access.'); ?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="nl"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="nl"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="nl"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="nl"> <!--<![endif]-->
<html>
  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1">
  
    <base target="_blank" />

    <!-- character encoding -->
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />

    <!-- seo -->
    <meta name="author" content="Tuxion" />
    <meta name="robots" content="index, follow" />
    <meta name="revisit-after" content="7 days" />
    <meta http-equiv="content-language" content="nl-NL">

    <?php echo $head->meta; ?>
    
    <?php echo $head->links; ?>
    
    <?php echo $head->plugins; ?>

    <?php echo $head->scripts; ?>

    <?php echo $head->theme; ?>

  </head>
  
  <body>
    
    <div id="container">
      <?php echo $body->content; ?>
    </div>

    <div id="sidebar">
      <?php echo $body->sidebar; ?>
    </div>
    
    <footer id="footer">
      <?php echo $body->footer; ?>
    </footer>

  </body>
</html>