<?php if(!defined('TX')) die('No direct access.');

/** paths.php
*
* define common used paths and urls
* @dir /config/
* @since 2.0
*
*/

define('URL_PATH', 'path/to/subfolder');

//$_SERVER['DOCUMENT_ROOT']
define('PATH_BASE', 'D:/path/to/your/www/rootfolder'.(URL_PATH ? '/'.URL_PATH : ''));
define('PATH_SITE', PATH_BASE.DS.'site');
define('PATH_PLUGINS', PATH_BASE.DS.'plugins');
define('PATH_LIBS', PATH_BASE.DS.'libraries');
define('PATH_LOGS', PATH_BASE.DS.'logs');

define('PATH_COMPONENTS', PATH_SITE.DS.'components');
define('PATH_TEMPLATES', PATH_SITE.DS.'templates');
define('PATH_THEMES', PATH_SITE.DS.'themes');
define('PATH_INCLUDES', PATH_SITE.DS.'includes');
define('PATH_SYSTEM', PATH_SITE.DS.'system');
define('PATH_HELPERS', PATH_SITE.DS.'helpers');

define('PATH_SYSTEM_CORE', PATH_SYSTEM.DS.'core');
define('PATH_SYSTEM_DEPENDENCIES', PATH_SYSTEM.DS.'dependencies');
define('PATH_SYSTEM_EXCEPTIONS', PATH_SYSTEM.DS.'exceptions');

define('URL_BASE', 'http://localhost/'.(URL_PATH ? URL_PATH.'/' : ''));
define('URL_SITE', URL_BASE.'site/');
define('URL_PLUGINS', URL_BASE.'plugins/');
define('URL_LIBS', URL_BASE.'libraries/');

define('URL_COMPONENTS', URL_SITE.'components/');
define('URL_TEMPLATES', URL_SITE.'templates/');
define('URL_THEMES', URL_SITE.'themes/');
define('URL_INCLUDES', URL_SITE.'includes/');
