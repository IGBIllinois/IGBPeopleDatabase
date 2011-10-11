<?php
//////////////////////////////////////////////////////
//
//	IGB People Database
//	query.inc.php
//
//	List of queries as variables
//
//	Crystal Ahn
//	April 2010
//
//////////////////////////////////////////////////////

$tab = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

$states_arr = array('AK'=>"AK - Alaska",'AL'=>"AL - Alabama",'AR'=>"AR - Arkansas",'AZ'=>"AZ - Arizona",'CA'=>"CA - California",
					'CT'=>"CT - Connecticut",'CO'=>"CO - Colorado",'DC'=>"DC - District Of Columbia",'DE'=>"DE - Delaware",
					'FL'=>"FL - Florida",'GA'=>"GA - Georgia",'HI'=>"HI - Hawaii",'IA'=>"IA - Iowa",'ID'=>"ID - Idaho", 
					'IL'=>"IL - Illinois",'IN'=>"IN - Indiana",'KS'=>"KS - Kansas",'KY'=>"KY - Kentucky",'LA'=>"LA - Louisiana",
					'MA'=>"MA - Massachusetts",'MD'=>"MD - Maryland",'ME'=>"ME - Maine",'MI'=>"MI - Michigan",'MN'=>"MN - Minnesota",
					'MO'=>"MO - Missouri",'MS'=>"MS - Mississippi",'MT'=>"MI - Montana",'NC'=>"NC - North Carolina",'ND'=>"ND - North Dakota",
					'NE'=>"NE - Nebraska",'NH'=>"NH - New Hampshire",'NJ'=>"NJ - New Jersey",'NM'=>"NM - New Mexico",'NV'=>"NV - Nevada",
					'NY'=>"NY - New York",'OH'=>"OH - Ohio",'OK'=>"OK - Oklahoma", 'OR'=>"OR - Oregon",
					'PA'=>"PA - Pennsylvania",'RI'=>"RI - Rhode Island",'SC'=>"SC - South Carolina",'SD'=>"SD - South Dakota",
					'TN'=>"TN - Tennessee",'TX'=>"TX - Texas",'UT'=>"UT - Utah",'VA'=>"VA - Virginia",'VT'=>"VT - Vermont",
					'WA'=>"WA - Washington",'WI'=>"WI - Wisconsin",'WV'=>"WV - West Virginia",'WY'=>"WY - Wyoming");




$select_theme = "SELECT theme_id, short_name FROM themes WHERE theme_active = '1' ORDER BY short_name ASC "; 
$select_theme_leaders = "SELECT user_id, CONCAT(first_name, ' ', last_name) AS full_name, netid FROM users where type_id = '11' "; 
$select_type = "SELECT type_id, name, type_active FROM type where type_active = '1'"; 
$select_all_types = "SELECT type_id, name, type_active FROM type "; 
$select_dept = "SELECT dept_id, name, dept_code FROM department ORDER BY name"; 
$select_key = "SELECT key_id, key_room, key_name, key_active FROM key_list ORDER BY key_active DESC, key_room ASC";
$select_active_key = "SELECT key_id, key_room, key_name FROM key_list WHERE key_active = '1' ORDER BY key_room ASC";

$semester_arr = array("FALL", "SPRING", "SUMMER");
$year_arr = array("2011", "2012", "2013", "2014", "2015", "2016");
$key_condition_arr = array("Returned", "Lost", "Broken", "Cancelled");



$leave_arr = array("Graduation"=>"Graduation","New Position at UIUC"=>"Accepted another position on UIUC Campus",
				   "Resignation"=>"Resignation","Other"=>"Other");
$status_arr = array(0=>"Inactive", 1=>"Active");




	
?>
