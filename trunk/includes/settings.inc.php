<?php
/////////////////////////////////////////////////
//
//	IGB People Database
//	settings.inc.php
//
//	Settings for the scripts.
//
//	Crystal Ahn
//	April 2010
//
////////////////////////////////////////////////

define("enable",TRUE);
//define("mysql_host","127.0.0.1");
//define("mysql_user","posteruser");
//define("mysql_password","m5qEGaQacAsp");
//define("mysql_database","people");
define("mysql_host","localhost");
define("mysql_user","test");
define("mysql_password","pass");
define("mysql_database","people");



define("ldap_host",'authen.igb.uiuc.edu');
define("ldap_base_dn","dc=igb,dc=uiuc,dc=edu");
define("ldap_people_ou","ou=People,dc=igb,dc=uiuc,dc=edu");
define("ldap_group_ou","ou=group,dc=igb,dc=uiuc,dc=edu");
define("ldap_ssl",FALSE);
define("ldap_port",389);

?>
