<?php #search people

$page_title = "IGB People Database Search"; 

include 'includes/header.inc.php'; 
include 'includes/functions.inc.php'; 
include_once 'includes/main.inc.php';


if (!$_SESSION['admin']){
header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/login.php"); 	
exit(); 
}

echo "<body onLoad=\"document.add.first_name.focus()\">"; 


$igb_edit = FALSE;
$personal_edit = FALSE;
$dept_edit = FALSE;
$home_edit = FALSE;
$remove = 0;

if(isset($_GET['user_id'])){$user_id = $_GET['user_id'];}
if(isset($_GET['igb_edit'])){$igb_edit = $_GET['igb_edit']; }
if(isset($_GET['personal_edit'])){$personal_edit = $_GET['personal_edit']; }
if(isset($_GET['dept_edit'])){$dept_edit = $_GET['dept_edit']; }
if(isset($_GET['home_edit'])){$home_edit = $_GET['home_edit']; }
if(isset( $_GET['remove'])){$remove = $_GET['remove']; }


			
$user = new user($db, $user_id);
$array = $user->get_user($user_id);

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
$igb_room = $user->get_igb_room();
$igb_phone = $user->get_igb_phone();
$fax = $user->get_fax();

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
$theme_name = $user->get_theme();
$other_theme_name = $user->get_other_theme();
$default_address = $user->get_default_address();

//booleans
$status = $user->get_status();
$safety_training = $user->get_training();
$key_deposit = $user->get_key_deposit();
$prox_card = $user->get_prox_card();
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
$html .= "<input type='button' name='add_key' id='add_key' 
			value='Add Key'  > ";
$html .= "<input type='button' name='remove' id='remove' 
			value='Remove'  > ";
$html .= "</div >";




/*
UPDATE PERSONAL
*/

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
	
	$netid_exists = $user->user_exists('netid', $netid, "AND user_id != '".$user_id."'");
	$uin_exists = $user->user_exists('uin', $uin, "AND user_id != '".$user_id."'");
	
	if (!empty($netid_exists)){  
		$netid_error= $tab . "* NetID already exists in database";
		$error_count++;
		$personal_edit=TRUE;
	}
	if (!empty($uin_exists)){ 
		$uin_error=$tab ."* UIN already exists in database";
		$error_count++;
		$personal_edit=TRUE;
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
UPDATE IGB
*/

if (isset($_POST['update_igb'])){
	$igb_room = $_POST['igb_room'];
	$theme_drop = $_POST['theme_drop'];
	$other_theme_drop = $_POST['other_theme_drop'];
	$type_drop = $_POST['type_drop'];	
	$igb_phone = $_POST['igb_phone'];
	$fax = $_POST['fax'];
	$start_date = $_POST['start_date']; 
	$supervisor = $_POST['supervisor'];
			$safety_training = $user->is_checked($_POST['safety_training']);
			$key_deposit = $user->is_checked($_POST['key_deposit']);
			$prox_card = $user->is_checked($_POST['prox_card']);
			
	
	$error_count = 0;
	$supervisor_error= "";
	
	$supervisor_id = $user->user_exists("netid",$supervisor);
	
	if (empty($supervisor_id)){  
		$supervisor_error= $tab . "* Invalid Supervisor NetID";
		$error_count++;
		$igb_edit=TRUE;
	}
	
	if ($error_count == 0){
		$result = $user->update($user_id, 'address', 'type', 'IGB', "AND type = 'IGB'");
		$result = $user->update($user_id, 'address', 'address2', $igb_room, "AND type = 'IGB'");
		$result = $user->update($user_id, 'users', 'theme_id', $theme_drop);
		$result = $user->update($user_id, 'users', 'other_theme_id', $other_theme_drop);
		$result = $user->update($user_id, 'users', 'type_id', $type_drop);	
		$result = $user->update($user_id, 'phone', 'igb', $igb_phone);
		$result = $user->update($user_id, 'phone', 'fax', $fax);
		$result = $user->update($user_id, 'users', 'start_date', $start_date);
		$result = $user->update($user_id, 'users', 'supervisor_id', $supervisor_id);
		$theme_name = $user->get_theme();
		$other_theme_name = $user->get_other_theme();
		if(isset($_POST['default_address'])){
			$default_address = $_POST['default_address'];
			$result = $user->update($user_id, 'users', 'default_address', $default_address);
		}
			$result = $user->update($user_id, 'users', 'safety_training', $safety_training);
		
			$result = $user->update($user_id, 'users', 'key_deposit', $key_deposit);
		
			$result = $user->update($user_id, 'users', 'prox_card', $prox_card);
		
		
	}


}


if (isset($_POST['cancel_igb'])){
	
		$igb_edit=FALSE;
}


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
PERSONAL INFO EDIT TABLE HTML
*/
$personal_info_edit = "
			<form method='post' action='profile.php?user_id=".$user_id."' name='update_personal'>
			<div class='profile' id='profile_edit' style='display:none;'>
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
				<input type='submit' name='update_personal' id='update_personal' class='submit' value='Update'>
				<input type='submit' name='cancel_personal' id='cancel_personal' class='cancel' value='Cancel'>
			</div>
			</div>
			</form>
			<br>
			";


/*
IGB INFO EDIT TABLE HTML
<input type='submit' class='profile' name='update' value='update'>
				<input type='button' name='cancel' value='Cancel'>
*/			
$igb_info_edit = "<div class='profile_header'>
			<form method='post' action='profile.php?user_id=".$user_id."' name='update_igb'>
			<p class='alignleft'>[ IGB ]</p>
			<p class='alignright'></p>
			
			</div>
			
			<div class='profile'>
			<table class = 'profile'>
    		<tr >
			  <td class='xs'><label>address </label><br> </td>
			  <td class='xs'>". $user->get_igb_acsz()."</td>

			  <td class='noborder'><input type='small' name='igb_room' maxlength='13'  
        		value='". $igb_room."'></td>
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
			<tr>
			  <td class='xs'><label>themes </label></td>
			  <td class='xs'>". dropdown( 'theme_drop', $theme_list, $user->get_theme_id() ). "</td>
			  <td class='noborder'>". dropdown( 'other_theme_drop', $theme_list, $user->get_other_theme_id() ). "</td>

			</tr>
			<tr >
			  <td class='xs'><label>type </label></td>
			  <td class='noborder'>". dropdown( 'type_drop', $type_list, $user->get_type_id() )."</td>
   			</tr>
			<tr >
			  <td class='xs'><label>supervisor </label></td>
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
			  <td class='xs'><label>start date </label></td>
			  <td class='noborder'><input type='date' name='start_date' maxlength='12'  
        		value=". $user->get_start_date()."></td>
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
DEPT INFO EDIT TABLE HTML

*/			
$dept_info_edit = " <div class='profile_header'>
			<form method='post' action='profile.php?user_id=".$user_id."' name='update_dept'>
			<p class='alignleft'>[ department ]</p>
			<p class='alignright'></p>
			
			</div>
			
			<div class='profile'>
			<table class = 'profile'>
			<tr>
			  <td class='xs'><label>department </label></td>
			  <td class='noborder'>". dropdown( 'dept_drop', $dept_list, $dept_drop ). "</td>

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
				simple_drop( "dept_state", $states_arr, $dept_state ).$tab. 
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
HOME INFO EDIT TABLE HTML

*/			
$home_info_edit = " <div class='profile_header'>
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
				simple_drop( "home_state", $states_arr, $home_state ).$tab. 
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





?> 

<script>

$(document).ready(function(){
						   
	var personal_edit = $("div#profile_edit").html();
    var personal = $("div#profile").html();
  
  	$("#cancel_personal").click(function(){
	  $("div#personal").html(personal);
	});
	$("p#personal").click(function(){
	  $("div#personal").html(personal_edit);
	});
  


});

</script>





<h1> <?php echo $user->get_name();?> </h1>
<h3> <?php 
	if (!$status){
		$phpdate = strtotime( $user->get_end_date() );
		echo "[ left IGB ".date('F j Y',$phpdate)." ]";
	
	}
	else{
		
		$phpdate = strtotime( $user->get_start_date() );
		echo "[ IGB member since ".date('F j Y',$phpdate)." ]";;
	}
	 ?> </h3>
<br>

<div class='profile_header'>
			
    <p class='alignleft'>[ personal ]</p>
    <p class='alignright' id='personal'> edit </p>
</div>
<div class='profile' id='personal'>
			<table class = 'profile'>
    		<tr >
			  <td class='xs'><label>netid </label></td>
			  <td class='noborder'>
              	<?php echo $netid; ?>
              </td>
   			</tr>
			<table class = 'profile'>
    		<tr >
			  <td class='xs'><label>email </label></td>
			  <td class='noborder'>
              	<?php echo $email; ?>
              </td>
   			</tr>
    		<tr >
			  <td class='xs'><label>uin </label></td>
			  <td class='noborder'>
              	<?php echo $uin; ?>
              </td>
   			</tr>
    		<tr >
			  <td class='xs'><label>gender </label></td>
			  <td class='noborder'>
              	<?php echo $gender; ?>
              </td>
   			</tr>
    		<tr >
			  <td class='xs'><label>cell phone </label></td>
			  <td class='noborder'>
              	<?php echo $cell_phone; ?>
              </td>
   			</tr>
    		<tr >
			  <td class='xs'><label>other phone </label></td>
			  <td class='noborder'>
              	<?php echo $other_phone; ?>
              </td>
   			</tr>

			</table>
            <br>
</table>
</div>
<br>

<div class='profile_header'>
      <p class='alignleft'>[ IGB ] 
      <?php
          if ( $default_address == 'IGB'){
              echo " * ";
          }

      ?>
      </p>
      <p class='alignright'><a class='edit' href='' onclick='return:false;'> edit </a></p>
			
</div>
<div class='profile' id='igb'>
			<table class = 'profile'>
    		<tr >
			  <td class='xs'><label>address </label><br> </td>
			  <td class='noborder'>1206 W. Gregory Dr.  
              
              	<?php echo $igb_room; ?>
             
			  	<br>Urbana, IL 61801
			  </td>
   			</tr>
    		<tr >
			  <td class='xs'><label>phone </label></td>
			  <td class='noborder'>
              	<?php echo $igb_phone; ?>
              
              </td>
   			</tr>
			<tr >
    		<tr >
			  <td class='xs'><label>fax </label></td>
			  <td class='noborder'>
              	<?php echo $fax; ?>
              </td>
   			</tr>
    		<tr >
			  <td class='xs'><label>themes </label></td>
			  <td class='noborder'>
              	<?php 
					echo $theme_name; 
					if ($other_theme_name != NULL){
						echo ", ". $other_theme_name;
					}
				?>
				</td>
            </tr>
            <tr >
			  <td class='xs'><label>type </label></td>
			  <td class='noborder'>
              	<?php echo $user->get_type(); ?>
              </td>
   			</tr>
			<tr >
			  <td class='xs'><label>supervisor </label></td>
			  <td class='noborder'>
              	<?php echo $user->get_supervisor_name(); ?>
              </td>
   			</tr>
			<tr >
			  <td class='xs'><label>key deposit </label></td>
			  <td class='noborder'>
              	<?php echo $bool[$key_deposit]; ?>
              </td>
   			</tr>
			<tr >
			  <td class='xs'><label>prox card </label></td>
			  <td class='noborder'>
              	<?php echo $bool[$prox_card]; ?>
              </td>
   			</tr>
			<tr >
			  <td class='xs'><label>safety training </label></td>
			  <td class='noborder'>
              	<?php echo $bool[$safety_training]; ?>
              </td>
   			</tr>
			</table>
			<br>
</div>
<br>
   
<div class='profile_header'>
      <p class='alignleft'>[ department ] 
      <?php
          if ( $default_address == 'DEPT'){
              echo " * ";
          }

      ?>
      </p>
      <p class='alignright'><a class='edit' href='' onclick='return:false;'> edit </a></p>
			
</div>  
<div class='profile'>
			<table class = 'profile'>
    		<tr >
			  <td class='xs'><label>department </label></td>
			  <td class='noborder'>
              	<?php echo $user->get_dept(); ?>
              </td>
   			</tr>
    		<tr >
			  <td class='xs'><label>address </label><br> </td>
			  <td class='noborder'>
              	<?php 
					if (!empty($dept_address1)){
						$dept_info .=  $dept_address1." ".$dept_address2." <br>".
									   $dept_city.", ".$dept_state." ".$dept_zip;
					}
				?>
              </td>
   			</tr>
    		<tr >
			  <td class='xs'><label>phone </label></td>
			  <td class='noborder'>
              	<?php echo $user->get_dept_phone(); ?>
              </td>
   			</tr>

			</table><br>
</div>
<br>

<div class='profile_header'>
      <p class='alignleft'>[ home ] 
      <?php
          if ( $default_address == 'HOME'){
              echo " * ";
          }

      ?>
      </p>
      <p class='alignright'><a class='edit' href='' onclick='return:false;'> edit </a></p>
			
</div> 
<div class='profile'>
			<table class = 'profile'>
    		<tr >
			  <td class='xs'><label>address </label><br> </td>
			  <td class='noborder'>
              
              	<?php 
					if (!empty($home_address1)){
						$home_info .=  $home_address1." ".$home_address2." <br>".
									   $home_city.", ".$home_state." ".$home_zip;
					}
				?>
              
			  </td>
   			</tr>

			</table><br>
</div>
<br>
            
            
            
            


<?php /*

	if ($personal_edit){*/
		echo $personal_info_edit;
	/*
	}
	else {
		echo $personal_info;
	}

	if ($igb_edit){
		echo $igb_info_edit;
	
	}
	else {
		echo $igb_info;
	} 
	if ($dept_edit){
		echo $dept_info_edit;
	
	}
	else { 
		echo $dept_info;
	}

	if ($home_edit){
		echo $home_info_edit;
	
	}
	else { 
		echo $home_info;
	}
	
	*/
	
?>
<label class='foot'>* denotes preferred address</label>
<br>
<?php 
echo $html;
include ("includes/footer.inc.php"); 

?> 
