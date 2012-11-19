<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.'); ?>

<div id="content" class="clearfix">
  <div class="seo">

<?php
$data->items->each(function($row){

  echo
    '<h2>'.$row->title.'</h2>'."\n".
    '<time pubdate="pubdate" title="'.$row->dt_created.'">'.$row->dt_created.'</time>'.
    '<p>'.$row->description.'</p>'.
    '<p><a href="#'.$row->id.'">Lees meer</a></p>'
  ;

});
?>

  </div><!-- /.seo -->
</div>

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

<script id="item-column" type="text/template">
  <div class="col" data-id="<%- id %>">
  </div>
</script>

<script id="item" type="text/x-jquery-tmpl">
  <section class="item <%- category_color %>" data-id="<%- id %>">
    <header>
      <h1><a href="#<%- id %>" target="_self"><%- title %></a></h1>
      <time pubdate="pubdate" title="<%- moment(dt_created, "YYYY-MM-DD HH:mm").format("YYYY-MM-DD HH:mm") %>"><%- moment(dt_created, "YYYY-MM-DD HH:mm:ss").format("D MMM") %></time>
    </header>
    <%= output %>
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
      <a href="#" class="back-to-overview button" target="_self">Terug naar het overzicht</a>
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

  !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");

</script>

<?php echo $data->admin_toolbar; ?>
