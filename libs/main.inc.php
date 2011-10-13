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

include_once 'includes/settings.inc.php';
include_once 'libs/db.class.inc.php';
include_once 'libs/user.class.inc.php';
include_once 'libs/ldap.class.inc.php';
include_once 'libs/session.class.inc.php';
include 'includes/query.inc.php';


$db = new db(mysql_host,mysql_database,mysql_user,mysql_password);	
session_start();

/*
if (isset($_SESSION['login']) && ($_SESSION['login'] == true)) {
        $ldap = new ldap(__LDAP_HOST__,__LDAP_SSL__,__LDAP_PORT__);
        $session = new session($db,$ldap,$_SESSION['username'],$_SESSION['password']);
        if ($session->authenticate(__LDAP_PEOPLE_OU__)) {
                define("login",$_SESSION['login']); 
                define("login_user",$_SESSION['username']);
                define("login_pass",$_SESSION['password']);
        }
        else {
                session_start();
                $_SESSION['webpage'] = $_SERVER['REQUEST_URI'];
                header('Location: login.php');
        
        }
}
else {
        $_SESSION['webpage'] = $_SERVER['REQUEST_URI'];
        header('Location: login.php');



if (isset($_SESSION['username'])) {
	$username = $_SESSION['username'];
}
else {
	$_SESSION['webpage'] = $_SERVER['REQUEST_URI'];
	header('Location: login.php');
	
}*/
	
?>
