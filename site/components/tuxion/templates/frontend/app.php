<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.'); ?>

<script id="item-column" type="text/template">
  <div class="col" data-id="<%- id %>">
  </div>
</script>

<script id="item" type="text/x-jquery-tmpl">
  <section class="item <%- category_color %>" data-id="<%- id %>">
    <header class="clearfix">
      <h1><%- title %></h1>
      <time pubdate="pubdate" title="<%- dt_created %>"><%- moment(dt_created, "YYYY-MM-DD HH:mm:ss").format("DD[-]MM") %></time>
    </header>
    <p>
      <%= description %>
    </p>
    <footer>
      <a href="#<%- id %>" class="read-more button">Lees meer</a>
      <?php if(tx('Account')->user->level->get() >= 2){ ?><a href="admin/?section=tuxion/edit_item&item_id=<%- id %>" target="_blank" class="edit">edit</a><?php } ?>
      <span class="author" data-id="<%- user_id %>"><%- username %></span>
    </footer>
  </section>
</script>

<script id="blog" type="text/template">
  
  <article class="inner <%- category_name %> <%- category_color %>">
    <header>
      <h1><%- title %></h1>
      <p>Gepubliceerd op <time pubdate="pubdate"><%- moment(dt_created, "YYYY-MM-DD HH:mm:ss").format("D MMMM YYYY [om] H:mm [uur]") %></time></p>
    </header>
    
    <div class="body">
      <%= text %>
    </div>
    
    <footer>
      <p><small>By <%- username %></small></p>
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

<div id="filters">
  <ul>
    <?php $data->categories->each(function($row){ ?>
    <li class="<?php echo $row->name.' '.$row->color; ?>">
      <div><span><?php echo $row->description; ?></span></div>
      <a href="#" data-id="<?php echo $row->id; ?>"><?php echo $row->title; ?></a>
    </li>
    <?php }); ?>
    <li class="home">
      <div><span>Terug naar het begin</span></div>
      <a href="#">Home</a>
    </li>
  </ul>
</div>

<?php echo $data->admin_toolbar; ?>