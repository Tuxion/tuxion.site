<?php namespace templates; if(!defined('TX')) die('No direct access.'); ?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<html>
  <head>

    <script type="text/javascript">var _sf_startpt=(new Date()).getTime()</script>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1">
  
    <title>Tuxion webdevelopment</title>
    
    <base target="_blank" />

    <!-- character encoding -->
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />

    <!-- seo -->
    <meta name="author" content="Tuxion" />
    <meta name="description" content="Tuxion, aangenaam. Wij zijn een jong, creatief team gespecialiseerd in het ontwikkelen van websites en webapplicaties." />
    <meta name="robots" content="index, follow" />
    <meta name="revisit-after" content="15 days" />

    <?php echo $head->meta; ?>
    
    <?php echo $head->links; ?>
    
    <?php echo $head->plugins; ?>

    <?php echo $head->scripts; ?>

    <?php echo $head->theme; ?>

  </head>
  
  <body>
    
    <div id="sidebar">
      <?php echo $body->sidebar; ?>
    </div>
    
    <div id="container">
      <?php echo $body->content; ?>
    </div>
    
    <footer id="footer">
      <?php echo $body->footer; ?>
    </footer>
  
    <script type="text/javascript">
      var _sf_async_config = { uid: 41808, domain: 'tuxion.nl' };
      (function() {
        function loadChartbeat() {
          window._sf_endpt = (new Date()).getTime();
          var e = document.createElement('script');
          e.setAttribute('language', 'javascript');
          e.setAttribute('type', 'text/javascript');
          e.setAttribute('src',
            (("https:" == document.location.protocol) ? "https://a248.e.akamai.net/chartbeat.download.akamai.com/102508/" : "http://static.chartbeat.com/") +
            "js/chartbeat.js");
          document.body.appendChild(e);
        };
        var oldonload = window.onload;
        window.onload = (typeof window.onload != 'function') ?
          loadChartbeat : function() { oldonload(); loadChartbeat(); };
      })();
    </script>

  </body>
</html>