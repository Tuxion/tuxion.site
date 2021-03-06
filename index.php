<?php

//set variables
$error_handler      = 'error_handler';
$exception_handler  = 'exception_handler';

//indicate we're not installing
define('INSTALLING', false);

//initiate
require_once('init.php');

//start session
tx('Session');

//initiate url class
tx('Url');

//enter a pageload log line
tx('Logging')->log('Core', 'Frontend Pageload', tx('Url')->url->input, true);

//check account details and progress user activity
tx('Account');

//start filtering data
tx('Data');

//set language
tx('Language');

//check if the website is editable
tx('Editable');

//component singleton
tx('Component');

//config
tx('Config')->system('backend', false);
tx('Config')->system('component', 'tuxion');

//start doing stuff
tx('Router')->start();

//enter a pageload log line
tx('Logging')->log('Core', 'Frontend Pageload', 'SUCCEEDED');
