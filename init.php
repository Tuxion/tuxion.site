<?php if(!isset($error_handler)) die('No direct access.');

//debug mode if not overwritten by $_GET['debug']
$debug = true;

//define delimiter sign
define('DS', '/');

//define default file-extension
define('EXT', '.php');

//define Tuxion to be true, very useful, obviously the system doesn't work if this is false
define('TX', true);

//define debug mode
define('DEBUG', (array_key_exists('debug', $_GET) && $_GET['debug']==='0' ? false : $debug));

//unset debug related vars
unset($debug, $_GET['debug']);

//set default timezone
date_default_timezone_set('Europe/Amsterdam');

//display errors if debug is true
ini_set('display_errors', (int)DEBUG);
error_reporting(E_ALL);

header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');/* http://adamyoung.net/IE-Blocking-iFrame-Cookies */

//load config files
require_once('config'.DS.'database'.EXT);
require_once('config'.DS.'paths'.EXT);
require_once('config'.DS.'email'.EXT);
require_once('config'.DS.'exceptions'.EXT);
require_once('config'.DS.'miscelanious'.EXT);

//load helpers
foreach(glob(PATH_HELPERS.DS.'[a-z_]*'.EXT) as $helper) require_once($helper); unset($helper);

//set error handler
set_error_handler($error_handler); unset($error_handler);

//set exception handler
set_exception_handler($exception_handler); unset($exception_handler);

//system core
require_once(PATH_SITE.DS.'tx.cms'.DS.'cms'.EXT);
