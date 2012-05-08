<?php namespace components\cms; if(!defined('TX')) die('No direct access.'); ?>

<ul id="configbar">

  <?php foreach($configbar->items as $item){ ?>
  <li class="<?php echo $item->component_name.'-'.$item->name; ?>">
    <a href="<?php echo url("view={$item->component_name}/{$item->name}", true) ?>" title="<?php echo $item->description ?>"><?php echo $item->title ?></a>
  </li>
  <?php } ?>
  
</ul>