<?php
//////////////////////////////////////////////////////
//
//	Poster Printer Order Submission
//	man.inc.php
//
//	Used to verify the user is logged in before proceeding
//
//	David Slater
//	April 2007
//
//////////////////////////////////////////////////////

	ini_set('display_errors',1);

set_include_path(get_include_path().";libs;");
include_once('settings.inc.php');
function my_autoloader($class_name) {
	if(file_exists("libs/" . $class_name . ".class.inc.php")) {

		require_once "libs/" .$class_name . '.class.inc.php';
	}

}

require_once 'vendor/autoload.php';


include 'query.inc.php';
include_once 'functions.inc.php';

spl_autoload_register('my_autoloader');

$db = new db(mysql_host,mysql_database,mysql_user,mysql_password);
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();

	
?>
