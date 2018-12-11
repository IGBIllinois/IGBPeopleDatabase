<?php #profile

$page_title = "IGB People Database Search"; 

require_once 'includes/header.inc.php';

error_reporting(E_ERROR | E_PARSE);

$igb_edit = FALSE;
$personal_edit = FALSE;
$dept_edit = FALSE;
$home_edit = FALSE;
$remove = 0;
$reactivate = 0;

if(isset($_GET['user_id'])){$user_id = $_GET['user_id'];}
if(isset($_GET['igb_edit'])){$igb_edit = $_GET['igb_edit']; }
if(isset($_GET['personal_edit'])){$personal_edit = $_GET['personal_edit']; }
if(isset($_GET['dept_edit'])){$dept_edit = $_GET['dept_edit']; }
if(isset($_GET['home_edit'])){$home_edit = $_GET['home_edit']; }
if(isset( $_GET['remove'])){$remove = $_GET['remove']; }
if(isset($_GET['reactivate'])){$reactivate = $_GET['reactivate'];}


			
$user = new user($db, $user_id);
$array = $user->get_user($user_id);

$current_user = new user($db, $user->get_current_user_id());

$theme_list = $db->query($select_theme);
$type_list = $db->query($select_type);
$dept_list = $db->query($select_dept);
$dept_array = array ();

$checked = "checked";

//personal
$first_name = $user->get_first_name();
$last_name = $user->get_last_name();
$netid = $user->get_netid();
$uin = $user->get_uin();
$email = $user->get_email();
$cell_phone = $user->get_cell_phone();
$other_phone = $user->get_other_phone();
$gender = $user->get_gender();

//igb
$igb_address = $user->get_igb_address();
$igb_city = $user->get_igb_city();
$igb_state = $user->get_igb_state();
$igb_zip = $user->get_igb_zip();
$igb_room = $user->get_igb_room();
$igb_phone = $user->get_igb_phone();
$fax = $user->get_fax();
$image = $user->get_image_location();

//dept
$dept_drop = $user->get_dept_id();
$dept_address1 = $user->get_dept_address1();
$dept_address2 = $user->get_dept_address2();
$dept_city = $user->get_dept_city();
$dept_state = $user->get_dept_state();
$dept_zip = $user->get_dept_zip();
$dept_phone = $user->get_dept_phone();

//home
$home_address1 = $user->get_home_address1();
$home_address2 = $user->get_home_address2();
$home_city = $user->get_home_city();
$home_state = $user->get_home_state();
$home_zip = $user->get_home_zip();
$default_address = $user->get_default_address();


//booleans
$status = $user->get_status();
$safety_training = $user->get_training();
$key_deposit = $user->get_key_deposit();
$prox_card = $user->get_prox_card();
$admin = $user->get_admin();
$bool = array(0=>"No", 1=>"Yes");
$checked_bool = array(0=>"", 1=>"checked");

//error messages
$netid_error = "";
$email_error = "";
$supervisor_error = "";
$uin_error  = "";

//button row (bottom)
$html = "";
$html .= "<div class='alignright'>";
$html .= "<input type='button' class='wide' name='additional_info' id='additional_info' 
			value='View Additional Info' onClick='window.location=\"moreinfo.php?user_id=" . $user_id . "\" ' > ";
if($user->get_status()) {			
$html .= "<input type='button' name='remove' id='remove' 
			value='Remove'  > ";
} else {
$html .= "<form method='post' action='profile.php?user_id=".$user_id."' name='remove_member'>
			<input type='submit' name='reactivate' id='reactivate'
			value='Reactivate'>
			</form>";
}
$html .= "</div >";


/*
UPDATE PERSONAL
*/
if(isset($_GET['from_theme'])) {
    $theme_id = $_GET['from_theme'];
    $theme = new theme($db);
    $theme->get_theme($theme_id);
    $theme_link = "<A href=themedir.php?theme=". $theme_id . ">Back to " . $theme->get_short_name() . "</A><BR>";
}
if (isset($_POST['update_personal'])){
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$netid = $_POST['netid'];
	$email = $_POST['email'];
	$uin = $_POST['uin'];
	$cell_phone = $_POST['cell_phone'];	
	$gender = $_POST['gender'];
	$error_count = 0;
	$netid_error= "";
	$uin_error= "";
	
	
        $netid_exists = $user->user_exists('netid', $netid, " AND user_id != :user_id ", array("user_id"=>$user_id));
	$uin_exists = $user->user_exists('uin', $uin, "AND user_id != :user_id", array("user_id"=>$user_id));
        
	if (!empty($netid_exists)){  
		$netid_error= $tab . "* NetID already exists in database";
		$error_count++;
		$personal_edit=TRUE;
	}
        if($uin != '') {
	if (!empty($uin_exists)){ 
		$uin_error=$tab ."* UIN already exists in database";
		$error_count++;
		$personal_edit=TRUE;
	}
        }
	if (!($user->is_valid_email($email))){ 
		$email_error= "* Invalid email address";
		$error_count++;
		$personal_edit=TRUE;
	}
	
	if ($error_count == 0){
		$result = $user->update($user_id, 'users', 'first_name', $first_name);
		$result = $user->update($user_id, 'users', 'last_name', $last_name);			
		$result = $user->update($user_id, 'users', 'netid', $netid);
		$result = $user->update($user_id, 'users', 'uin', $uin);
		$result = $user->update($user_id, 'users', 'email', $email);
		$result = $user->update($user_id, 'phone', 'cell', $cell_phone);
		$result = $user->update($user_id, 'users', 'gender', $gender);			
	}

}
if (isset($_POST['cancel_personal'])){
    $personal_edit=FALSE;
}


/*
PERSONAL INFO TABLE HTML
*/
$personal_info = "<div class='profile_header'>
			
    <p class='alignleft'>[ personal ]</p>
    <p class='alignright'><a class='edit' href='profile.php?user_id=".$user_id."&personal_edit=TRUE'> edit </a></p>
    </div>
    <div class='profile'>
    <table class = 'profile'>
<tr >
      <td class='xs'><label>netid </label></td>
      <td class='noborder'>". $netid ."</td>
    </tr>
    <table class = 'profile'>
<tr >
      <td class='xs'><label>email </label></td>
      <td class='noborder'>". $email ."</td>
    </tr>
<tr >
      <td class='xs'><label>uin </label></td>
      <td class='noborder'>". $uin ."</td>
    </tr>
<tr >
      <td class='xs'><label>gender </label></td>
      <td class='noborder'>". $gender."</td>
    </tr>
<tr >
      <td class='xs'><label>cell phone </label></td>
      <td class='noborder'>". $cell_phone."</td>
    </tr>
<tr >
      <td class='xs'><label>other phone </label></td>
      <td class='noborder'>". $other_phone."</td>
    </tr>

    </table><br>
    </div>
    <br>
    ";

/*
PERSONAL INFO EDIT TABLE HTML
*/
$personal_info_edit = "<div class='profile_header' >
<form method='post' action='profile.php?user_id=".$user_id."' name='update_personal'>
    <h2>[ personal ]</h2>
    </div>
    <div class='profile'>
    <table class = 'profile'>
<tr >
      <td class='xs'><label>first name </label><br> </td>
      <td class='noborder'><input type='text' name='first_name' maxlength='30'  
    value=". $first_name ."></td>
    </tr>
<tr >
      <td class='xs'><label>last name </label><br> </td>
      <td class='noborder'><input type='text' name='last_name' maxlength='30'  
    value=". $last_name ."></td>
    </tr>
<tr >
      <td class='xs'><label>netid </label><br> </td>
      <td class='noborder'><input type='small' name='netid' maxlength='8'  
    value=". $netid ."><label class='errormsg'>".$netid_error."</label></td>
    </tr>
<tr >
      <td class='xs'><label>email </label></td>
      <td class='noborder'><input type='large' name='email' maxlength='50'  
    value=". $email ."><label class='errormsg'>".$email_error."</label></td>
    </tr>
<tr >
      <td class='xs'><label>uin </label></td>
      <td class='noborder'><input type='small' id='uin' name='uin' maxlength='9'  
    value=". $uin ."><label class='errormsg'>".$uin_error."</label></td>
    </tr>
<tr >
      <td class='xs'><label>gender </label></td>
      <td class='noborder'>

            <input type='radio' name='gender' value='M'" ;
                    if ($gender == 'M') {$personal_info_edit .=  $checked;} 
$personal_info_edit .= ">M
            <input type='radio' name='gender' value='F' ";
               if ($gender == 'F') {$personal_info_edit .=  $checked;} 
$personal_info_edit .= ">F
      </td>
    </tr>
<tr >
      <td class='xs'><label>cell phone </label></td>
      <td class='noborder'><input type='text' class='phone' name='cell_phone' maxlength='14'  
    value='". $cell_phone."'></td>
    </tr>
<tr >
      <td class='xs'><label>other phone </label></td>
      <td class='noborder'><input type='text' class='phone' name='other_phone' maxlength='14'  
    value='". $other_phone."'></td>
    </tr>

    </table><br>
    <div class='alignright'>
            <input type='submit' name='update_personal' value='Update'>
            <input type='submit' name='cancel_personal' value='Cancel'>
    </div>
    </div>
    <br>
    ";
			
			

/*
UPDATE IGB
*/

if (isset($_POST['update_igb'])){
        $igb_address = $_POST['igb_address'];
        
        $igb_state = $_POST['igb_state'];
        $igb_city = $_POST['igb_city'];
        $igb_zip = $_POST['igb_zip'];
	$igb_room = $_POST['igb_room'];
        $add_theme = $_POST['add_theme'];
        $add_type = $_POST['add_type'];
        $remove_theme = $_POST['remove_theme'];
	$igb_phone = $_POST['igb_phone'];
	$fax = $_POST['fax'];
	$start_date = $_POST['start_date']; 
        $end_date = $_POST['end_date'];
        $reason_leaving = $_POST['reason_leaving'];
	$supervisor = $_POST['supervisor'];
        $safety_training = $user->is_checked($_POST['safety_training']);
        $key_deposit = $user->is_checked($_POST['key_deposit']);
        $prox_card = $user->is_checked($_POST['prox_card']);
        $admin = $user->is_checked($_POST['admin']);
                        
        $add_permission = $_POST['add_permission'];
        $remove_permission = $_POST['remove_permission'];

	$error_count = 0;
	$supervisor_error= "";
	
	$supervisor_id = $user->user_exists("netid",$supervisor);
	
	if (empty($supervisor_id)){  
		$supervisor_error= $tab . "* Invalid Supervisor NetID";
		$error_count++;
		$igb_edit=TRUE;
	}

        $basename = "";

        if($user->is_checked($_POST['delete_image'])) {
            $user->delete_current_image();
        }

        if($_FILES['imageFile'] != null && $_FILES['imageFile']['name'] != null) {
            $mimetypes = array("image/jpeg", "image/gif", "image/png", "image/pjpeg");

            if (!(in_array($_FILES["imageFile"]["type"], $mimetypes))) {
                echo(html::error_message("ERROR: File must be an image file."));
                //return;
            } else {
            try {

            $uploadfile = IMAGE_DIR . basename($_FILES['imageFile']['name']);

            $basename = basename($_FILES['imageFile']['name']);
           
            $basebasename = strstr($basename, ".", TRUE);
            $basesuffix = strstr($basename, ".");
            $i=1;
            while(file_exists(IMAGE_DIR.$basename)) {
                $basename = $basebasename . $i . $basesuffix;
                $i++;
            }
            $uploadfile = IMAGE_DIR . $basename;
            $localuploadfile = LOCAL_IMAGE_DIR .$basename;
            $fileresult = 0;
            try{ 
                $fileresult = (move_uploaded_file($_FILES['imageFile']['tmp_name'], $localuploadfile)) ;

            } catch (Exception $ex) {
                    echo($ex);
                    echo($ex->getTraceAsString());
            }
            if($fileresult == "" || $fileresult) {
                try {
                    functions::resizeImage($localuploadfile, $_FILES["imageFile"]["type"]);
                } catch(Exception $e) {
                    echo(html::error_message("Error in uploading image.<BR>"));
                }

                //delete old image
                $user->delete_current_image();
                
                $result = $user->update($user_id, 'users', 'image_location', $basename);
                
                // set new image
                $image = IMAGE_DIR_URL . $basename;

            } else {

                echo(html::error_message("FILE ERROR: #".$FILES['imageFile']['error']));
                $error_count++;
            }
            } catch(Exception $e) {
                echo($e->getTrace());
            }

        }
        }
	
	if ($error_count == 0){

		$result = $user->update($user_id, 'address', 'type', 'IGB', "AND type = 'IGB'");
                $result = $user->update($user_id, 'address', 'address1', $igb_address, " AND type = 'IGB'");
                $result = $user->update($user_id, 'address', 'city', $igb_city, " AND type = 'IGB'");
                $result = $user->update($user_id, 'address', 'state', $igb_state, " AND type = 'IGB'");
                $result = $user->update($user_id, 'address', 'zip', $igb_zip, " AND type = 'IGB'");
		$result = $user->update($user_id, 'address', 'address2', $igb_room, " AND type = 'IGB'");

                if(isset($_POST['add_theme'])&& $_POST['add_theme'] != 0) {

                    $user->add_theme($user_id, $_POST['add_theme'], $_POST['add_type']);
                    
                }
                if(isset($_POST['remove_theme']) && $_POST['remove_theme'] != 0) {

                    $user->remove_theme($user_id, $_POST['remove_theme']);  
                }
                
                if(isset($_POST['change_type']) && $_POST['change_type'] != 0) {
                    $user->change_type($user_id, $_POST['change_type_theme'], $_POST['change_type']);
                }
		$result = $user->update($user_id, 'phone', 'igb', $igb_phone);
		$result = $user->update($user_id, 'phone', 'fax', $fax);
		$result = $user->update($user_id, 'users', 'start_date', $start_date);
                $result = $user->update($user_id, 'users', 'end_date', $end_date);
                $result = $user->update($user_id, 'users', 'reason_leaving', $reason_leaving);
		$result = $user->update($user_id, 'users', 'supervisor_id', $supervisor_id);
                $user->add_permission($user_id, $add_permission);
                $user->remove_permission($user_id, $remove_permission);

                $image = $user->get_image_location();

		if(isset($_POST['default_address'])){
			$default_address = $_POST['default_address'];
			$result = $user->update($user_id, 'users', 'default_address', $default_address);
		}
                $result = $user->update($user_id, 'users', 'safety_training', $safety_training);
                $result = $user->update($user_id, 'users', 'key_deposit', $key_deposit);
                $result = $user->update($user_id, 'users', 'prox_card', $prox_card);
                $result = $user->update($user_id, 'users', 'admin', $admin);
		
	} else {
            echo("ERROR");
        }
        // Reload with new info
        
}


if (isset($_POST['cancel_igb'])){
	
		$igb_edit=FALSE;
}

/*
IGB INFO TABLE HTML
*/

$igb_info = "<div class='profile_header'>
			<p class='alignleft'>[ IGB ] ";
if ( $default_address == 'IGB'){
	$igb_info .= " * ";
}
$igb_address1 = $user->get_igb_address();
$igb_address = $user->get_igb_address();
$igb_room = $user->get_igb_room();
$igb_city = $user->get_igb_city();
$igb_state = $user->get_igb_state();
$igb_zip = $user->get_igb_zip();
$theme_info = $user->get_themes($user_id);
// If the IGB address is null, use default IGB Address instead
if($igb_address1 == null || $igb_address1 == "") {
        $igb_address = "1206 W. Gregory Dr.";
        $igb_city = "Urbana";
        $igb_state = "IL";
        $igb_zip = "61801";
    }
$igb_info .= "</p>
        <p class='alignright'><a class='edit' href='profile.php?user_id=".$user_id."&igb_edit=TRUE #igb'> edit </a></p>

        </div>
        <div class='profile'>
        <table class = 'profile'>
<tr >
          <td class='xs'><label>address </label><br> </td>
          <td class='noborder'>". $igb_address . ", Room #". $igb_room ."
                <br>". $igb_city . ", ". $igb_state . " " . $igb_zip . "
          </td>
        </tr>
<tr >
          <td class='xs'><label>phone </label></td>
          <td class='noborder'>". $igb_phone."</td>
        </tr>
        <tr >
<tr >
          <td class='xs'><label>fax </label></td>
          <td class='noborder'>". $fax."</td>
        </tr>
<tr >";

                    
$igb_info .= "<td class='xs'><label>themes </label></td>";
$igb_info .= "<td class='noborder'>";
foreach($theme_info as $theme) {
    $igb_info .= $theme['theme_name'];
    $igb_info .= ", ";
}
$igb_info = rtrim($igb_info);
$igb_info = rtrim($igb_info, ",");

			  

$igb_info .= "</td>
    </tr>
    <tr >
    <td class='xs'><label>types </label></td>
    <td class='noborder'>";

    foreach($theme_info as $theme) {
        $igb_info .= $theme['type_name'];
        $igb_info .= ", ";
    }
    $igb_info = rtrim($igb_info);
    $igb_info = rtrim($igb_info, ",");
		  
			  
$igb_info .= "</td>
        </tr>
        <tr >
          <td class='xs'><label>supervisor</label></td>
          <td class='noborder'>". $user->get_supervisor_name()."</td>
        </tr>
        <tr >
          <td class='xs'><label>key deposit </label></td>
          <td class='noborder'>". $bool[$key_deposit]."</td>
        </tr>
        <tr >
          <td class='xs'><label>prox card </label></td>
          <td class='noborder'>". $bool[$prox_card]."</td>
        </tr>
        <tr >
          <td class='xs'><label>safety training </label></td>
          <td class='noborder'>". $bool[$safety_training]."</td>
        </tr>
        <tr >
          <td class='xs'><label>admin </label></td>
          <td class='noborder'>". $bool[$admin]."</td>
        </tr>
        <tr>
            <td class='xs'><label>permissions</label></td>
            <td class='noborder'>". (($admin == 1) ? "ALL" : functions::list_permissions($db, $user_id)) . "</td>".
        "</tr>
          <td class='xs'><label>image </label></td>
          <td class='noborder'>". "<img src='".$image."'>"." <a href='".$user->get_large_image_location()."' target='blank'>View larger image</a></td>
        </tr>

</table>
        <br>
        </div>
        <br>
        ";


$user_theme_list = array();
$user_themes = $user->get_themes_array();
foreach($user_themes as $theme_id=>$type_id) {
    $theme = new theme($db, $theme_id);
    $user_theme_list[$theme->get_theme_id()] = array("theme_id"=>$theme->get_theme_id(), "theme_name"=>$theme->get_short_name());
}
/*
IGB INFO EDIT TABLE HTML

*/			
$igb_info_edit = "<div class='profile_header' id='igb'>
    <form method='post' enctype='multipart/form-data' action='profile.php?user_id=".$user_id."' name='update_igb'>
    <p class='alignleft'>[ IGB ]</p>
    <p class='alignright'></p>

    </div>

    <div class='profile'>
    <table class = 'profile'>

<tr >
      <td class='xs'><label>address </label><br> </td>
      <td class='noborder'><input type='large' name='igb_address' maxlength='50'  
    value=\"". $user->get_igb_address()."\"></td>

    <td class='noborder'>Room #<input type='small' name='igb_room' maxlength='13'  
        value='". $igb_room."'></td>
    </tr>
<tr >


      <td class='xs'><label>city</label></td><td class='noborder'>	
      <input type='text' name='igb_city' maxlength='30'  
      value='". $user->get_igb_city()."'>"."</td></tr>".
     "<tr><td class='xs'><label>state</label></td><td class='noborder'>".	
        
        html::simple_drop( "igb_state", $states_arr, $user->get_igb_state() )."</td></tr>".
        
      "<tr><td class='xs'><label>zip</label></td><td class='noborder'>"
        . "<input type='small' name='igb_zip' maxlength='10'  "
        . "value='".$user->get_igb_zip()."'>
      </td>



    </tr>
<tr >
      <td class='xs'><label>phone </label></td>
      <td class='noborder'><input type='text' class='phone' name='igb_phone' maxlength='14'  
    value='". $igb_phone."'></td>
    </tr>
<tr >
      <td class='xs'><label>fax </label></td>
      <td class='noborder'><input type='text' class='phone' name='fax' maxlength='14'  
    value='". $fax."'></td>
    </tr>
";
if($current_user->get_admin()) {
    $igb_info_edit .= "<tr>
        <td class='xs'><label>Add Theme </label></td>
        <td class='xs'>". html::dropdown( 'add_theme', $theme_list, 0 ). "</td>
      </tr>
      <tr >
      <td colspan=3 class='noborder'><A HREF=theme_history.php?user_id=".$user->get_user_id(). ">(View theme history)</a></td>
          </tr>
      <tr >
        <td class='xs'><label>Add Type </label></td>
        <td class='noborder'>". html::dropdown( 'add_type', $type_list, 0 )."</td>
      </tr>";
}

$igb_info_edit  .="<tr>
    <td class='xs'><label>Remove Theme </label></td>
    <td class='xs'>". html::dropdown( 'remove_theme', $user_theme_list, 0 ). "</td>
  </tr>
  <tr>
    <td class='xs'><label>Change Type</label></td>
    <td class='xs'>". html::dropdown( 'change_type_theme', $user_theme_list, 0 ). " &nbsp; ". html::dropdown( 'change_type', $type_list, 0 ) ."</td>
  </tr>
  <tr >
    <td class='xs'><label>supervisor (NetID)</label></td>
    <td class='noborder'><input type='text' name='supervisor' maxlength='30'  
  value=". $user->get_supervisor_netid()."></td>
          <td class='noborder'><label class='errormsg'>".$supervisor_error."</label></td>
  </tr>
  <tr >
    <td class='xs'><label>key deposit </label></td>
    <td class='noborder'>
          <input type='checkbox' name='key_deposit' value='checked' ".  $checked_bool[$key_deposit] .">
    </td>
  </tr>
  <tr >
    <td class='xs'><label>prox card </label></td>
    <td class='noborder'>
          <input type='checkbox' name='prox_card' value='checked' ".  $checked_bool[$prox_card] .">
    </td>
  </tr>
  <tr >
    <td class='xs'><label>safety training </label></td>
    <td class='noborder'>
          <input type='checkbox' name='safety_training' value='checked' ".  $checked_bool[$safety_training] .">
    </td>
  </tr>
  <tr >
    <td class='xs'><label>admin </label></td>
    <td class='noborder'>
          <input type='checkbox' name='admin' value='checked' ".  $checked_bool[$admin] .">
    </td>
  </tr>
  <tr >
    <td class='xs'><label>start date </label></td>
    <td class='noborder'><input type='date' name='start_date' maxlength='12'  
  value=". $user->get_start_date()."></td>
  </tr>
  <tr >
    <td class='xs'><label>departure date </label></td>
    <td class='noborder'><input type='date' name='end_date' maxlength='12'  
  value=". $user->get_end_date()."></td>
  </tr>
  <tr >
    <td class='xs'><label>reason leaving </label></td>
    <td class='noborder'><input type='date' name='reason_leaving'  
  value='". $user->get_reason_leaving()."'></td>
  </tr>
  <tr>
      <td class='xs'><label>add permissions</label></td>
      <td class='noborder'>".html::dropdown( 'add_permission', $theme_list )."</td>
  </tr>
  <tr>
      <td class='xs'><label>remove permissions</label></td>
      <td class='noborder'>".html::dropdown( 'remove_permission', $theme_list )."</td>
  </tr>
  <tr>
      <td class='xs'><label>thumbnail image</label></td>
      <td colspan=3 class='noborder'><img src='".$user->get_image_location()."'> "
        . "<a href='".$user->get_large_image_location().
        "' target='blank'>View larger image</a></td>
  <tr>
  <tr>
      <td class='xs'><label>Image</label></td>
      <td colspan=3 class='noborder'><!-- The data encoding type, enctype, MUST be specified as below -->

              <!-- MAX_FILE_SIZE must precede the file input field -->
              <!-- <input type='hidden' name='MAX_FILE_SIZE' value='30000' /> -->
              <!-- Name of input element determines name in \$_FILES array -->
              <input name='imageFile' value='".$user->get_image().
        "' type='file' style='width:500px;height:25px' accept='image/jpeg, image/gif, image/png, image/pjpeg, image/tiff' />                             
      </td>
   </tr>
   <tr >
    <td class='xs'><label>Delete current image </label></td>
    <td class='noborder'>
          <input type='checkbox' name='delete_image' value='checked'>
    </td>
  </tr>
  </table>

  <div class='alignright'>
  <input type='checkbox' name='default_address' value='IGB'>
          <label>Set IGB as preferred address </label>
  </div>
  <div class='alignright'>
          <input type='submit' name='update_igb' value='Update'>
          <input type='submit' name='cancel_igb' value='Cancel'>
  </div>
  </div>
  </form>
  <br>
  ";
			
			
			

/*
UPDATE DEPARTMENT
*/

if (isset($_POST['update_dept'])){
	$dept_drop = $_POST['dept_drop'];
	$dept_address1 = $_POST['dept_address1'];
	$dept_address2 = $_POST['dept_address2'];
	$dept_city = $_POST['dept_city'];
	$dept_state = $_POST['dept_state'];
	$dept_zip = $_POST['dept_zip'];
	$dept_phone = $_POST['dept_phone'];
	
	$result = $user->update($user_id, 'address', 'type', 'DEPT', "AND type = 'DEPT'");

        $result = $user->update($user_id, 'address', 'address1', $dept_address1, "AND type = 'DEPT'");
        $result = $user->update($user_id, 'address', 'address2', $dept_address2, "AND type = 'DEPT'");	
        $result = $user->update($user_id, 'address', 'city', $dept_city, "AND type = 'DEPT'");	
        $result = $user->update($user_id, 'address', 'state', $dept_state, "AND type = 'DEPT'");
        $result = $user->update($user_id, 'address', 'zip', $dept_zip, "AND type = 'DEPT'");		
		
		
	$result = $user->update($user_id, 'users', 'dept_id', $dept_drop);
	$result = $user->update($user_id, 'phone', 'dept', $dept_phone);
	
	
        if(isset($_POST['default_address'])){
                $default_address = $_POST['default_address'];
                $result = $user->update($user_id, 'users', 'default_address', $default_address);
        }
	
}
if (isset($_POST['cancel_dept'])){
    $dept_edit=FALSE;
}

		
/*
DEPT INFO TABLE HTML
*/
$dept_info = "<div class='profile_header'>
			<p class='alignleft'>[ department ] ";
if ( $default_address == 'DEPT'){
	$dept_info .= " * ";
}		
$dept_info .= "</p>
    <p class='alignright'><a class='edit' href='profile.php?user_id=".$user_id."&dept_edit=TRUE #dept'> edit </a></p>
    </div>
    <div class='profile'>
    <table class = 'profile'>
<tr >
      <td class='xs'><label>department </label></td>
      <td class='noborder'>". $user->get_dept()."</td>
    </tr>
<tr >
      <td class='xs'><label>address </label><br> </td>
      <td class='noborder'>"; 

if (!empty($dept_address1)){
        $dept_info .=  $dept_address1." ".$dept_address2." <br>".
                                   $dept_city.", ".$dept_state." ".$dept_zip;
}
				
$dept_info .= "</td>
    </tr>
<tr >
      <td class='xs'><label>phone </label></td>
      <td class='noborder'>". $user->get_dept_phone()."</td>
    </tr>

    </table><br>
    </div>
    <br>
    ";
			

/*
DEPT INFO EDIT TABLE HTML

*/			
$dept_info_edit = " <div class='profile_header' id='dept'>
    <form method='post' action='profile.php?user_id=".$user_id."' name='update_dept'>
    <p class='alignleft'>[ department ]</p>
    <p class='alignright'></p>

    </div>

    <div class='profile'>
    <table class = 'profile'>
    <tr>
      <td class='xs'><label>department </label></td>
      <td class='noborder'>". html::dropdown( 'dept_drop', $dept_list, $dept_drop ). "</td>

    </tr>
<tr >
      <td class='xs'><label>address </label><br> </td>
      <td class='noborder'><input type='large' name='dept_address1' maxlength='50'  
    value=\"". $dept_address1."\"></td>
    </tr>
<tr >
      <td class='xs'><label></label><br> </td>
      <td class='noborder'><input type='large' name='dept_address2' maxlength='30'  
    value=\"". $dept_address2."\"></td>
    </tr>
<tr >
      <td class='xs'><label>city, state, zip</label><br> </td>
      <td class='noborder'>
            <input type='text' name='dept_city' maxlength='30'  value='". $dept_city."'>".$tab.
            html::simple_drop( "dept_state", $states_arr, $dept_state ).$tab. 
            "<input type='small' name='dept_zip' maxlength='10'  value='".$dept_zip."'>
      </td>
    </tr>
<tr >
      <td class='xs'><label>phone </label></td>
      <td class='noborder'><input type='text' class='phone' name='dept_phone' maxlength='14'  
    value='". $dept_phone."'></td>
    </tr>
    </table>
    <div class='alignright'>
    <input type='checkbox' name='default_address' value='DEPT'>
            <label>Set Department as preferred address </label>
    </div>
    <div class='alignright'>
            <input type='submit' name='update_dept' value='Update'>
            <input type='submit' name='cancel_dept' value='Cancel'>
    </div>
    </div>
    </form>
    <br>
    ";




/*
UPDATE HOME
*/

if (isset($_POST['update_home'])){
	$home_address1 = $_POST['home_address1'];
	$home_address2 = $_POST['home_address2'];
	$home_city = $_POST['home_city'];
	$home_state = $_POST['home_state'];
	$home_zip = $_POST['home_zip'];
	
	$result = $user->update($user_id, 'address', 'type', 'HOME', "AND type = 'HOME'");
        $result = $user->update($user_id, 'address', 'address1', $home_address1, "AND type = 'HOME'");
        $result = $user->update($user_id, 'address', 'address2', $home_address2, "AND type = 'HOME'");	
        $result = $user->update($user_id, 'address', 'city', $home_city, "AND type = 'HOME'");	
        $result = $user->update($user_id, 'address', 'state', $home_state, "AND type = 'HOME'");
        $result = $user->update($user_id, 'address', 'zip', $home_zip, "AND type = 'HOME'");
	
        if(isset($_POST['default_address'])){
            $default_address = $_POST['default_address'];
            $result = $user->update($user_id, 'users', 'default_address', $default_address);
        }
	
}


if (isset($_POST['cancel_home'])){
	
		$home_edit=FALSE;
}

/*
HOME INFO TABLE HTML
*/			
$home_info = "<div class='profile_header'>
			<p class='alignleft'>[ home ]";
if ( $default_address == 'HOME'){
	$home_info .= " * ";
}			
$home_info .= "</p>
    <p class='alignright'><a class='edit' onclick='return:false;' 
        href='profile.php?user_id=".$user_id."&home_edit=TRUE #home'> edit </a></p>
    </div>
    <div class='profile'>
    <table class = 'profile'>
<tr >
      <td class='xs'><label>address </label><br> </td>
      <td class='noborder'>";

if (!empty($home_address1)){
        $home_info .=  $home_address1." ".$home_address2." <br>".
            $home_city.", ".$home_state." ".$home_zip;
}
			  
$home_info .=  "</td>
    </tr>

    </table><br>
    </div>
    <br>
    ";
	

/*
HOME INFO EDIT TABLE HTML

*/			
$home_info_edit = " <div class='profile_header' id='home'>
    <form method='post' action='profile.php?user_id=".$user_id."' name='update_home'>
    <p class='alignleft'>[ home ]</p>
    <p class='alignright'></p>

    </div>

    <div class='profile'>
    <table class = 'profile'>
<tr >
      <td class='xs'><label>address </label><br> </td>
      <td class='noborder'><input type='large' name='home_address1' maxlength='50'  
    value=\"". $home_address1."\"></td>
    </tr>
<tr >
      <td class='xs'><label></label><br> </td>
      <td class='noborder'><input type='large' name='home_address2' maxlength='30'  
    value=\"". $home_address2."\"></td>
    </tr>
<tr >
      <td class='xs'><label>city, state, zip</label><br> </td>
      <td class='noborder'>
            <input type='text' name='home_city' maxlength='30'  value='". $home_city."'>".$tab.
            html::simple_drop( "home_state", $states_arr, $home_state ).$tab. 
            "<input type='small' name='home_zip' maxlength='10'  value='".$home_zip."'>
      </td>
    </tr>
    </table>
    <div class='alignright'>
    <input type='checkbox' name='default_address' value='HOME'>
            <label>Set Home as preferred address </label>
    </div>
    <div class='alignright'>
            <input type='submit' name='update_home' value='Update'>
            <input type='submit' name='cancel_home' value='Cancel'>
    </div>
    </div>
    </form>
    <br>
    ";



/*
REMOVE MEMBER
*/

if (isset($_POST['remove_member'])){
	
	$fwd_address_type = $_POST['fwd_address_type'];
	$fwd_address1 = $_POST['fwd_address1'];
	$fwd_city = $_POST['fwd_city'];
	$fwd_state = $_POST['fwd_state'];
	$fwd_zip = $_POST['fwd_zip'];
	
	$end_date = $_POST['end_date'];
	$reason_leaving = $_POST['reason_leaving'];
	$other_reason_leaving = $_POST['other_reason_leaving'];
	$fwd_country = $_POST['fwd_country'];
	$fwd_phone = $_POST['fwd_phone'];
	$fwd_phone_type = $_POST['fwd_phone_type'];	
        $fwd_email = $_POST['fwd_email'];
	
	
	$fwd_array = array ();
	$fwd_array["type"]=$fwd_address_type;
	$fwd_array["address1"]=$fwd_address1;
	$fwd_array["address2"]="";
	$fwd_array["city"]=$fwd_city;
	$fwd_array["state"]=$fwd_state;
	$fwd_array["zip"]=$fwd_zip;
	
	$address_result = $user->add_address($user_id, $fwd_array, $fwd_country, '1');	
	$date_result = $user->update($user_id, 'users', 'end_date', $end_date);
	$phone_result = $user->update($user_id, 'phone', 'fwd', $fwd_phone);
	$phone_type_result = $user->update($user_id, 'phone', 'fwd_type', $fwd_phone_type);
        if($fwd_email != null) {
            $email_result = $user->update($user_id, 'users', 'email', $fwd_email);
        }
	
	if($reason_leaving == "Other"){		
		$reason_result = $user->update($user_id, 'users', 'reason_leaving', $other_reason_leaving);
	}
	else{	
		$reason_result = $user->update($user_id, 'users', 'reason_leaving', $reason_leaving);		
	}
	

	$result = $user->update($user_id, 'users', 'user_enabled', '0');	
        $alum_result = $db->query("select type_id from type where name='Alumnus'");
        $alum_id = $alum_result[0]['type_id'];
        $result = $user->update($user_id, 'users', 'type_id', $alum_id);
	$result = $user->update($user_id, 'users', 'default_address', 'FWD');
	unset($_POST['remove_member']);

	$redirectpage= "/search.php";
	header ("Location: https://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . $redirectpage); 	
	exit(); 
	
}

/* REACTIVATE MEMBER
*/
if(isset($_POST['reactivate'])) {
	unset($_POST['reactivate']);
	$redirectpage= "/profile.php?user_id=".$user_id;
	$result = $user->update($user_id, 'users', 'user_enabled', '1');	
	header ("Location: https://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . $redirectpage); 	
	exit(); 
}

/*
EXIT HTML

*/			
$remove_html = " <div id='remove_member'>
    <form method='post' action='profile.php?user_id=".$user_id."' name='remove_member'>

            <label class='required'>IGB Member Exit Info </label>	
            <br>
            <br>

            <table class = 'profile'>
                    <tr >
                      <td class='small'><label>IGB Departure Date </label><br> </td>
                      <td class='noborder'><input type='date' name='end_date' maxlength='12'  ></td>
                    </tr>
                    <tr >
                      <td class='small'><label>Reason For Leaving</label><br> </td>
                      <td class='noborder'>".
                            html::simple_drop( "reason_leaving", $leave_arr).$tab."

                      </td>
                    </tr>
                    <tr >
                      <td class='small'><label>If Other, please specify</label><br> </td>
                      <td class='noborder'>
                            <input type='large' name='other_reason_leaving' maxlength='100'  >					  
                      </td>
                    </tr>
            </table>
            <br>
            <label class='required'>Contact Info for Future Correspondence	</label>	
            <br>
            <br>
            <table class = 'profile'>
                <tr >
                  <td class='xs'><label>Address </label><br> </td>
                  <td class='noborder'>
                        <input type='large' name='fwd_address1' maxlength='50'  
                                value=\"". $fwd_address1."\">".$tab."
                        <input type='radio' name='fwd_address_type' value='HOME'><label class='note'>Home</label>
                        <input type='radio' name='fwd_address_type' value='WORK'><label class='note'>Business</label>


                        </td>
                </tr>
                <tr >
                  <td class='xs'><label>City/State/Zip</label><br> </td>
                  <td class='noborder'>
                        <input type='small' name='fwd_city' maxlength='30'  value='". $fwd_city."'>".$tab.
                        html::simple_drop( "fwd_state", $states_arr, $fwd_state ).$tab. 
                        "<input type='small' name='fwd_zip' maxlength='10'  value='".$fwd_zip."'>
                  </td>
                </tr>

                <tr >
                  <td class='xs'><label>Country </label><br> </td>
                  <td class='noborder'>
                        ".html::country_dropdown('fwd_country')."
                  </td>
                </tr>
                <tr >
                  <td class='xs'><label>Phone </label><br> </td>
                  <td class='noborder'>
                        <input type='text' name='fwd_phone' maxlength='14'  value='".$fwd_phone."'>".$tab."
                        <input type='radio' name='fwd_phone_type' value='HOME'><label class='note'>Home</label>
                        <input type='radio' name='fwd_phone_type' value='WORK'><label class='note'>Business</label>
                        <input type='radio' name='fwd_phone_type' value='CELL'><label class='note'>Cell</label>

                  </td>
                </tr>
                <tr>
                    <td class='xs'><label>Email</label><BR></td>
                    <td class='noborder'>
                        <input type='small' name='fwd_email' maxlength='50'>
                    </td>
                </tr>
            </table>
    <br>
    </div>
    <div class='alignright'>
            <input type='submit' style='width:150px;' name='remove_member' id='remove_member' value='Remove Member'>
    </div>
    </form>
    <br>
    ";
?> 


<h1> <?php echo $user->get_name();?> </h1>
 <?php 
	if (!$status){

		$phpdate = strtotime( $user->get_end_date() );
                
                $removed = $_GET['removed'];
                
                if($removed == '1') {
                    echo( "<br><br><h4> User successfully removed from the database</h4>");
                
                
                    echo("<br>
                    <a href='profile.php?user_id=" . $user_id . "'>View Profile >></a>
                    <br>
                    <a href='search.php'>Search another IGB member >></a><BR><BR>")	;
                }

		echo "<h3>[ left IGB ".date('F j Y',$phpdate)." ]";
		echo "<BR>";
		echo "[ Reason: ". $user->get_reason_leaving(). " ]</h3>";
                
	}
	else{
		$phpdate = strtotime( $user->get_start_date() );
		echo "<h3>[ IGB member since ".date('F j Y',$phpdate)." ]</h3>";
	}
        
        if(isset($theme_link)) {
            echo($theme_link . "<BR>");
        }
	 ?> 
<br>
<?php 

	if ($personal_edit){ echo $personal_info_edit;	}
		else {echo $personal_info;	}
	if ($igb_edit){echo $igb_info_edit;	}
		else {echo $igb_info;} 
	if ($dept_edit){echo $dept_info_edit;}
		else { echo $dept_info;	}
	if ($home_edit){echo $home_info_edit;	}
		else { echo $home_info;}
	echo $html;

	
?>
<label class='foot note'>* denotes preferred address</label>

<div style='display:none'>
		<div id='remove_html' >
        <?php
			echo $remove_html;
		?>
		</div>
     
</div>
<br>
<?php 

require_once ("includes/footer.inc.php"); 





 
?> 
