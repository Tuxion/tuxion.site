<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.'); ?>

<script id="item-column" type="text/template">
  <div class="col" data-id="<%- id %>">
  </div>
</script>

<script id="item" type="text/x-jquery-tmpl">
  <section class="item <%- category_color %>" data-id="<%- id %>">
    <header>
      <h1><a href="#<%- id %>" target="_self"><%- title %></a></h1>
      <time pubdate="pubdate" title="<%- dt_created %>"><%- moment(dt_created, "YYYY-MM-DD HH:mm:ss").format("DD[-]MM") %></time>
    </header>
    <%= description %>
    <footer>
      <a href="#<%- id %>" class="read-more button" target="_self">Lees meer</a>
      <?php if(tx('Account')->user->level->get() >= 2){ ?><a href="admin/?section=tuxion/edit_item&item_id=<%- id %>" target="_blank" class="edit">edit</a><?php } ?>
      <span class="author" data-id="<%- user_id %>"><%- username %></span>
    </footer>
  </section>
</script>

<script id="blog" type="text/template">
  
  <article class="inner <%- category_name %> <%- category_color %>">
    <header>
      <a style="float:right;color:#666 !important;background:none;" href="#" class="back-to-overview button" target="_self">Terug naar het overzicht</a>
      <h1><%- title %></h1>
      <p>
        Gepubliceerd op <time pubdate="pubdate"><%- moment(dt_created, "YYYY-MM-DD HH:mm:ss").format("D MMMM YYYY [om] H:mm [uur]") %></time>
        <span title="Categorie: <%- category_title %>" class="tooltip left"><%- category_title %></span>
      </p>
    </header>
    
    <div class="body">
      <%= text %>
    </div>
    
    <footer>
      <div class="author-and-date">Geschreven door <%- name %> <%- preposition %> <%- family_name %>, op <%- moment(dt_created, "YYYY-MM-DD HH:mm:ss").format("D MMMM YYYY") %></div>
      <div class="social-buttons"></div>
      <div class="clear"></div>
      <a href="#" class="back-to-overview button" target="_self">Terug naar het overzicht</a>
      <?php if(tx('Account')->user->level->get() >= 2){ ?><a href="admin/?section=tuxion/edit_item&item_id=<%- id %>" target="_blank" class="edit">edit</a><?php } ?>
    </footer>

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
      <div><span class="tooltip right"><?php echo $row->description; ?></span></div>
      <a href="#" data-id="<?php echo $row->id; ?>" target="_self"><?php echo $row->title; ?></a>
    </li>
    <?php }); ?>
    <li class="home">
      <div><span class="tooltip right">Terug naar het begin</span></div>
      <a href="#">Home</a>
    </li>
  </ul>
</div>

<?php echo $data->admin_toolbar; ?>