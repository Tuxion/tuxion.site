<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.'); ?>

<script id="item-column" type="text/template">
  <div class="col" data-id="<%- id %>">
  </div>
</script>

<script id="item" type="text/template">
  <section class="item <%- category_color %>" data-id="<%- id %>">
    <header>
      <h1><%- title %></h1>
    </header>
    <p>
      <%= description %>
    </p>
    <footer>
      <a href="#<%- id %>" class="read-more button">Lees meer</a>
      <a class="author" data-id="<%- user_id %>"><%- username %></a>
    </footer>
  </section>
</script>

<script id="blog" type="text/template">
  
  <article class="inner <%- category_name %> <%- category_color %>">
    <header>
      <h1><%- title %></h1>
      <p>Published: <time pubdate="pubdate"><%- (new Date(dt_created)).toLocaleDateString() %></time></p>
    </header>
    
    <div class="body">
      <%= text %>
    </div>
    
    <footer>
      <p><small>By %username%</small></p>
    </footer>

    <a href="#" class="back-to-overview button">Terug naar het overzicht</a>

  </article>
  
</script>

<script type="text/javascript">
  $(function(){
    window.app = new Tuxion({});
  });
</script>

<div id="content" class="clearfix"></div>
