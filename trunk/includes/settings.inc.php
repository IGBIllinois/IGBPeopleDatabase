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

define("mysql_host","localhost");
define("mysql_user","peopledb");
define("mysql_password", "7sKLMnsfuaBM4ZDy");
define("mysql_database","peopleDev");



define("ldap_host",'authen.igb.illinois.edu');
define("ldap_base_dn","dc=igb,dc=uiuc,dc=edu");
define("ldap_people_ou","ou=People,dc=igb,dc=uiuc,dc=edu");
define("ldap_group_ou","ou=group,dc=igb,dc=uiuc,dc=edu");
define("ldap_ssl",TRUE);
define("ldap_port",389);
?>
