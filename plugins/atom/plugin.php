<?php namespace plugins; if(!defined('TX')) die('No direct access.');

//Disable errors for unset variables.
$error_reporting_old = error_reporting(error_reporting() & ~E_NOTICE);

//Include Atom class.
require_once('Atom.php');

//Enable old error reporting.
error_reporting($error_reporting_old);
unset($error_reporting_old);

?>