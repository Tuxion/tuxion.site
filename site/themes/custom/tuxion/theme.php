<?php namespace themes; if(!defined('TX')) die('No direct access.');

if( tx('Data')->get->view->not('empty') && (tx('Data')->get->view == 'light' || tx('Data')->get->view == 'dark') ){
  $view = tx('Data')->get->view->get();
}elseif( date("H") < 19 && date("H") > 7 ){
  $view = 'light';
}else{
  $view = 'dark';
}

?>

    <script type="text/javascript" src="<?php echo $theme ?>js/javascript.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $theme ?>css/style.css" />
    <!--<link rel="stylesheet" type="text/css" href="<?php echo $theme ?>css/style-dark.css" />-->
    <link rel="stylesheet" type="text/css" href="<?php echo $theme ?>css/style-<?php echo $view; ?>.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $theme ?>css/scroll.css" />

    <script type="text/javascript">

      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-81474-2']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();

    </script>