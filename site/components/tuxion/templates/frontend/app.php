<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.'); ?>

<script id="item-column" type="text/template">
  <div class="col" data-id="<%- id %>">
  </div>
</script>

<script id="item" type="text/template">
  <section class="item blue" data-id="<%- id %>" style="visibility:hidden">
    <header>
      <h1><%- title %></h1>
    </header>
    <p>
      <%= description %>
    </p>
    <footer>
      <a href="#" class="read-more button">Lees meer</a>
      <a class="author" data-id="<%- user_id %>"><%- "author_name" %></a>
    </footer>
  </section>
</script>

<script type="text/javascript">
  $(function(){
    window.app = new Tuxion({});
  });
</script>

<div id="content" class="clearfix"></div>
