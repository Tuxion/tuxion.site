ALTER TABLE `tx__cms_users`
  ADD `username` VARCHAR( 255 ) NULL AFTER `email` ,
  ADD INDEX ( `username` ) 
