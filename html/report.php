<?php
require_once 'includes/header.inc.php';

// writing large files may take a little longer
set_time_limit(300);

$type = $_POST['report_type'];

if(isset($_POST['create_excel'])) {
    $filename = "igb_people";

    $user = new user($db);
    $user_id = "";		

    $igb_room = $_POST['igb_room']; 
    $igb_phone = $_POST['igb_phone']; 	
    $theme_drop = $_POST['theme_drop'];
    $type_drop = $_POST['type_drop'];
    $dept_drop = $_POST['dept_drop'];
    $supervisor = $_POST['supervisor'];
    $user_enabled = $_POST['user_enabled'];
    $search_value = $_POST['search_value'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $filters = array();
    $filters["users.dept_id"] = array($_POST['dept_drop'], "AND");
    $filters["address.address2"] = array($_POST['igb_room'], "AND");
    $filters["phone.igb"] = array($_POST['igb_phone'], "AND");

    $data = array(array("Name","Email","Theme","Type","Room Number","Address"));
    $all_data = user::search_2($db, $user_enabled, $search_value, $current_user_id, $filters, $theme_drop, $type_drop, $start_date, $end_date, $supervisor);
    foreach($all_data as $user) {
        $data[] = array($user->get_first_name(). " ". $user->get_last_name(),
                         $user->get_email(),
                           implode(",", $user->get_theme_short_names()),
                           implode(",", $user->get_type_names()),
                           $user->get_igb_room(),
                           $user->get_home_address1() . ", ". $user->get_home_address2()


            );
    }

}
if (isset($_POST['create_detailed_excel'])) {
    $filename = "igb_people_detail";
    $user = new user($db);
    $user_id = "";		


    $igb_room = $_POST['igb_room']; 
    $igb_phone = $_POST['igb_phone']; 	
    $theme_drop = $_POST['theme_drop'];
    $type_drop = $_POST['type_drop'];
    $dept_drop = $_POST['dept_drop'];
    $supervisor = $_POST['supervisor'];
    $user_enabled = $_POST['user_enabled'];
    $search_value = $_POST['search_value'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $filters = array();
    $filters["users.dept_id"] = array($_POST['dept_drop'], "AND");
    $filters["address.address2"] = array($_POST['igb_room'], "AND");
    $filters["phone.igb"] = array($_POST['igb_phone'], "AND");

     $data = array(array("Last Name","First Name","Theme","Status","Room Number","Phone Number",
                "Email","UIN","Supervisor","Home Department"));   

     $all_data = user::search_2($db, $user_enabled, $search_value, $current_user_id, $filters, $theme_drop, $type_drop, $start_date, $end_date, $supervisor);
     foreach($all_data as $user) {
         $data[] = array($user->get_last_name(),
                            $user->get_first_name(),
                            implode(",", $user->get_theme_short_names()),
                            ($user->get_status() ? "Active" : "Inactive"),
                            $user->get_igb_room(),
                            $user->get_igb_phone(),
                            $user->get_email(),
                            $user->get_uin(),
                            $user->get_supervisor_name(),
                            $user->get_dept()

             );

     }
 
}

if(isset($_POST['forwarding'])) {
    $filename = "forwarding_addresses";
    $user = new user($db);
    $data = $user->get_forwarding_addresses();
}

if(isset($_POST['peopledbusers'])) {
    $filename = "people_database_users";
    $user = new user($db);
    $data = $user->get_database_users();
}

$filename = $filename . "." . $type;

switch ($type) {
    
	case 'csv':
		report::create_csv_report($data,$filename);
		break;
	case 'xls':
	      	report::create_excel_2003_report($data,$filename);
                break;
	case 'xlsx':
		report::create_excel_2007_report($data,$filename);
		break;
}

?>
