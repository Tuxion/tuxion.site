<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.');

echo
  load_plugin('jquery_tmpl'),
  load_plugin('jquery_scrollTo')

?>

<script type="text/javascript">

var app;
$(function(){
  app = new TuxionApp();
});

</script>

<script id="col" type="text/x-jquery-tmpl">
  <div class="col ${classes}"></div>
</script>

<script id="item" type="text/x-jquery-tmpl">
  <article class="item ${category_color}" rel="${id}">
    <header>
      <h1>${title}</h1>
    </header>
    {{html description}}
    <footer>
      <a href="#" class="read-more button">Lees meer</a>
    </footer>
  </article>
</script>

<script id="full_item" type="text/x-jquery-tmpl">
  <div id="full-item" class="col wide">
    <article class="item full-item wide ${category_color}" rel="${id}">
      <header>
        <h1>${title}</h1>
      </header>
      {{html text}}
      <footer>
        <a href="#" class="close button">Close</a>
      </footer>
    </article>
  </div>
</script>

<div id="item-container" class="clearfix"></div>
