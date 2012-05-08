<?php namespace plugins; if(!defined('TX')) die('No direct access.'); ?>

  	<script type="text/javascript" src="<?php echo $plugin; ?>scripts/shCore.js"></script>
    <script type="text/javascript" src="<?php echo $plugin; ?>scripts/shBrushJScript.js"></script>
    <script type="text/javascript" src="<?php echo $plugin; ?>scripts/shBrushPhp.js"></script>

    <link type="text/css" rel="stylesheet" href="<?php echo $plugin; ?>styles/shCoreDefault.css"/>

    <?php tx('Ob')->script('plugin', 'syntaxhighlighter') ?>
    <script type="text/javascript">
    $(function(){
      SyntaxHighlighter.all({
        toolbar:false
      });
    });
    </script>
    <?php tx('Ob')->end(); ?>