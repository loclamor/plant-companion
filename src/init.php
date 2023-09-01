<?php
//set a title to your application
define('SITE_NAME', 'Plant Companion');

//table name prefix for the database
define('TABLE_PREFIX','plant_');

//default controller and action
define('DEFAULT_CONTROLLER', 'home');
define('DEFAULT_ACTION', 'index');

//define the location folder of the BPC Framework
define('BPCF', 'src/bpc_framework');
define('BPCF_ROOT', 'src');

define('APPLICATION_ENV', 'dev');

define('ENTITIES_AUTO_INSTALL', true);
define('LOG_LEVEL', 4);
define('FORCE_DEBUG', true);

require_once 'dbpass.php';

if(APPLICATION_ENV == 'dev') {//on est en local
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
}

require_once BPCF.'/conf.php';
// require_once('src/PlantUrl.php');

define('DEFAULT_PAGE_LENGTH', 10);

ini_set('post_max_size', '30M');

ini_set('upload_max_filesize', '20M');

session_start();