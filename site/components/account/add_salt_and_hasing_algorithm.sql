ALTER TABLE `tx__cms_users`
  ADD `salt` VARCHAR( 255 ) NULL DEFAULT NULL AFTER `password`,
  ADD `hashing_algorithm` VARCHAR( 255 ) NULL DEFAULT NULL AFTER `salt`
