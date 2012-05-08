<?php namespace components\cms; if(!defined('TX')) die('No direct access.'); tx('Account')->page_authorisation(2); ?>

<div id="new-page-wrap">

  <h1>Pagina toevoegen</h1>

  <ul class="pagetypes-list">
    <?php
    
    $new_page->page_types->each(function($page_type){
      echo
        '  <li class="page-type-'.$page_type->name.'">'.
        '    <a href="'.url('action=cms/new_page&view_id='.$page_type->id.(tx('Data')->filter('cms')->menu->is_set() && tx('Data')->filter('cms')->menu->get('int') > 0 ? '&link_to='.tx('Data')->filter('cms')->menu : '')).'" title="'.$page_type->description.'">'.$page_type->title.'</a>'.
        '  </li>';
    });
    
    ?>
  </ul>

  <h2>Kies een bestaande pagina</h2><br />
  
  <select name="pages" id="page-link">
    <option value="">Kies een pagina</option>
  <?php
  $new_page->pages->each(function($page){
    echo
      '<option value="'.$page->id.'">'.$page->title.'</option>';
  });
  ?>
  </select>

</div>

<script type="text/javascript">

$(function(){

  $("#new-page-wrap .pagetypes-list a").on("click", function(e){

    e.preventDefault();

    $.ajax({
      url: $(this).attr("href")
    }).done(function(data){
      $("#app").replaceWith(data);
    });

  });
  
  $("#page-link").on("change", function(e){

    $.ajax({
      data: {
        page_id: $(this).val(),
        redirect: true
      },
      url: "<?php echo url('action=cms/link_page&menu_item_id='.(tx('Data')->filter('cms')->menu->is_set() && tx('Data')->filter('cms')->menu->get('int') > 0 ? tx('Data')->filter('cms')->menu : '')); ?>"
    }).done(function(data){
      $("#app").replaceWith(data);
    });
  
  });

});

</script>