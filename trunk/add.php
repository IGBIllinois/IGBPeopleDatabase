<?php #add new ticket 

$page_title = "IGB Facilities Add New Record"; 

include 'includes/header.inc.php'; 
include 'includes/functions.inc.php'; 
include_once 'includes/main.inc.php';

if (!$_SESSION['admin']){
header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/login.php"); 	
exit(); 
}



echo "<body onLoad=\"document.add.first_name.focus()\">"; 
$success = FALSE;

$error="";
$error_count=0;
$empty_form=FALSE;
$aster= array();
$checked = "checked";


$home_state = ''; 
$dept_state = ''; 
$default_address = ''; 


if (isset($_POST['add'])){
	
$user_id = "";	
$last_name = $_POST['last_name']; 
$first_name = $_POST['first_name']; 
$netid = $_POST['netid']; 
$uin = $_POST['uin']; 
$email = $_POST['email']; 

$igb_room = $_POST['igb_room']; 
$igb_phone = $_POST['igb_phone']; 
$dept_phone = $_POST['dept_phone']; 
$cell_phone = $_POST['cell_phone']; 
$fax = $_POST['fax'];
$other_phone = $_POST['other_phone'];

$dept_address1 = $_POST['dept_address1']; 
$dept_address2 = $_POST['dept_address2']; 
$dept_city = $_POST['dept_city']; 
$dept_state = $_POST['dept_state']; 
$dept_zip = $_POST['dept_zip'];

$dept_array = array ();

$home_address1 = $_POST['home_address1']; 
$home_address2 = $_POST['home_address2']; 
$home_city = $_POST['home_city']; 
$home_state = $_POST['home_state']; 
$home_zip = $_POST['home_zip']; 

$home_array = array ();

$default_address = $_POST['default_address']; 

$theme_drop = $_POST['theme_drop'];
$other_theme_drop = $_POST['other_theme_drop'];
$type_drop = $_POST['type_drop'];
$dept_drop = $_POST['dept_drop'];
$supervisor = $_POST['supervisor'];

$start_date = $_POST['start_date']; 
$gender = $_POST['gender']; 
$key_deposit = $_POST['key_deposit']; 
$key_room = $_POST['key_room']; 
$safety_training = $_POST['safety_training']; 
$prox_card = $_POST['prox_card']; 
$admin = $_POST['admin'];
$grad_drop = $_POST['grad_drop']; 
$year_drop = $_POST['year_drop']; 


$fields = array("First Name" => &$first_name,
				"Last Name" => &$last_name,
				"Net ID" => &$netid,
				"Email" => &$email,
				"UIN" => &$uin,
				"IGB Room" => &$igb_room,
				"IGB Phone" => &$igb_phone,
				"Start Date" => &$start_date,
				"Theme" => &$theme_drop,
				"Type" => &$type_drop,
				"Gender" => &$gender,
				"Supervisor" => &$supervisor);

	


if (empty($first_name) || empty($last_name) || empty($netid) || empty($email) ||  empty($uin) || 
	empty($igb_room) || empty($igb_phone) || empty($start_date) || 
	/*empty($home_address1) || empty($home_city) || empty($home_state) || empty($home_zip) ||*/
	empty($theme_drop) || empty($type_drop) || empty($gender) || empty($supervisor) 
	)
{
	$error.="* Please enter missing values<br>";
	
	foreach($fields as $field => $value) {
	    if(empty($value)) {
			$error .= $field . "<BR>";
		}
	}


/*
	$error.="
	First name = " . $first_name . "<br>
	Last name = " . $last_name . "<br>
	NetID = ". $netid . "<br>
	Email = ". $email . "<br>
	UIN = " . $uin . "<br>
	Room = ". $igb_room . "<br>
	Phone = ".$igb_phone . "<br>
	Start Date = ".$start_date ."<br>
	Theme = " . $theme_drop . "<br>
	Type = " . $type_drop . "<br>
	Gender = " . $gender . "<br>
	Supervisor = ". $supervisor . "<br>";
	*/
	
	
	
	$empty_form=TRUE;
}


if (empty($default_address))
{
	$error.="* Please select a preferred address<br>";
	$empty_form=TRUE;
}

if (!$empty_form){
			
	$user = new user($db);
	$netid_exists = $user->user_exists("netid",$netid);
	$supervisor_id = $user->user_exists("netid",$supervisor);
	$uin_exists = $user->user_exists("uin",$uin);
	if (!empty($netid_exists)){
		$error.="* NetID already exists in database<br>";
		$error_count++;
	}
	if (!empty($uin_exists)){
		$error.="* UIN already exists in database<br>";
		$error_count++;
	}
	if (empty($supervisor_id)){
		$error.="* Invalid Supervisor NetID<br>";
		$error_count++;
	}
	if (strlen($uin) < 9){
		$error.="* Please enter a valid UIN<br>";
		$error_count++;
	}
	if (!($user->is_valid_email($email))){ 
		$error.="* Invalid email address";
		$error_count++;
	}

		if ($error_count == 0){
			
			
			$user_id = $user->add_user($first_name, $last_name, $netid, $uin, 
									  $email, $theme_drop, $other_theme_drop, 
									  $type_drop, $dept_drop, $default_address, 
									  $start_date, $key_deposit, $prox_card, 
									  $safety_training, $gender, $supervisor_id, $admin);
		if ($user_id != 0){
			
			$result = $user->add_igb_address($user_id, $igb_room);	
			
			if (!empty($dept_address1)){		
			
			   $dept_array["type"]="DEPT";
			   $dept_array["address1"]=$dept_address1;
			   $dept_array["address2"]=$dept_address2;
			   $dept_array["city"]=$dept_city;
			   $dept_array["state"]=$dept_state;
			   $dept_array["zip"]=$dept_zip;
			   $result = $user->add_address($user_id, $dept_array);	
			}
			
			if (!empty($home_address1)){			
			
			   $home_array["type"]="HOME";
			   $home_array["address1"]=$home_address1;
			   $home_array["address2"]=$home_address2;
			   $home_array["city"]=$home_city;
			   $home_array["state"]=$home_state;
			   $home_array["zip"]=$home_zip;
			   $result = $user->add_address($user_id, $home_array);	
			}
			
			$insert_phone = $user->add_phone($user_id, $igb_phone, $dept_phone, $cell_phone, $fax, $other_phone);
			
			if ($type_drop == '3' || $type_drop == '10'){	//type is grad student or undergrad		
				$grad_date = $grad_drop ." ".$year_drop;
			   	$result = $user->update($user_id, 'users', 'expected_grad', $grad_date);
			}
			
			
			
			$success = TRUE;
			
			$user_id = $user->user_exists("netid",$netid);
		}
		else {
			$error = "* Error occurred when adding user.  Please try again."	;
		}
			
	  
	  }
  }
}

$dept_list = $db->query($select_dept);
$theme_list = $db->query($select_theme);
$type_list = $db->query($select_type);

$success_html = "<br><br><h4> User successfully added into the database</h4>
					
					<br>
					<a href='profile.php?user_id=" . $user_id . "'>View Profile >></a>
					<br>
					<a href='add.php'>Add another IGB member >></a>"	;

$add_header = "<h1> Add New IGB Member</h1>
				  <h3>[ All fields in bold are required ]</h3>";
$add_form_html = "";
		
		
		
$add_form_html .= "<label class='errormsg'>". $error ."</label>
				  <form method='post' action='add.php' name='add'>";
				  
$add_form_html .= "<div class='section'>
					  <table class='medium'>
						<tr>
						  <td class='noborder'>
							  <label class='required'>First Name </label>
							  <label class='error'>".$aster[empty($first_name)]." </label>
						  </td>
						  <td class='noborder'>    
							  <label class='required'>Last Name </label> 
							  <label class='error'>". $aster[empty($last_name)]." </label>
						  </td>
						</tr>
						<tr>
						  <td class='noborder'><input type='name' name='first_name' maxlength='30'  
							  value='";
					if (isset($first_name)){
						$add_form_html .= $first_name;
					}
$add_form_html .="' >
						  </td>
					  	  <td class='noborder'><input type='name' name='last_name' ' maxlength='30'  
						  value='";
					if (isset($last_name)){
						$add_form_html .= $last_name;
					}
$add_form_html .="' > 
						  </td>
					  
						</tr>
					  </table>
					  <table class='medium'>
						  <tr>
							  <td class='noborder'>    
								  <label class='required'>NetID </label> 
							  <label class='error'>".$aster[empty($netid)]." </label>
							  </td>
							  <td class='noborder'>
								  <label class='required'>UIN</label> 
							  <label class='error'>". $aster[empty($uin)]." </label>
							  </td>
							  <td class='noborder'>
								  <label class='required'>Email</label> 
							  <label class='error'>". $aster[empty($email)]." </label>
							  </td>
						  
					  
						  </tr>
						  <tr>
							  <td class='noborder'>
								  <input type='small' name='netid' maxlength='8'  
								  value='";
					if (isset($netid)){
						$add_form_html .= $netid;
					}
$add_form_html .="' > 
							  </td>
							  <td class='noborder'>
								  <input type='small' id='uin' name='uin' maxlength='9'  
								  value='";
					if (isset($uin)){
						$add_form_html .= $uin;
					}
$add_form_html .="' > 
							  </td>
							  <td class='noborder'>
								  <input type='large' name='email' maxlength='50'  
								  value='";
					if (isset($email)){
						$add_form_html .= $email;
					}
$add_form_html .="' > 
							  </td>
						   </tr>
					  </table>
					  
					  <table class = 'small'>
						  <tr >
						  
							<td class='noborder'><label class='optional'>Dept Phone </label></td>
							<td class='noborder'><label class='optional'>Cell Phone</label></td>
							<td class='noborder'><label class='optional'>Fax</label></td>
							<td class='noborder'><label class='optional'>Other Phone </label></td>
						  </tr>
						  <tr>
						  	<td class='noborder'>
								<input type='text' class='phone' name='dept_phone' maxlength='14'  
								value='";
					if (isset($dept_phone)){
						$add_form_html .= $dept_phone;
					}
$add_form_html .="' > 
							</td>
							<td class='noborder'>
								<input type='text' class='phone'  name='cell_phone' maxlength='14'  
								value='";
					if (isset($cell_phone)){
						$add_form_html .= $cell_phone;
					}
$add_form_html .="' > 
							</td>
							<td class='noborder'>      
								<input type='text' class='phone'  name='fax' maxlength='14'  
								value='";
					if (isset($fax)){
						$add_form_html .= $fax;
					}
$add_form_html .="' > 
							</td>
							<td class='noborder'>
								<input type='text' class='phone' name='other_phone' maxlength='14'  
								value='";
					if (isset($other_phone)){
						$add_form_html .= $other_phone;
					}
$add_form_html .="' > 
							</td>
					  
						 </tr>
					  
					  </table>
					  
					  <br />
					  </div>";
/*
IGB
*/
					  
					  
$add_form_html .="<div class='section'>
			<table class='small'>
			  <tr>
				<td class='noborder'><label class='required'>IGB Office/Lab/Cubicle # </label>
					<label class='error'>". $aster[empty($igb_room)]." </label>
				</td>
				<td class='noborder'><label class='required'>IGB Phone</label>
					<label class='error'>". $aster[empty($igb_phone)]." </label>
				</td>
			  </tr>
			  <tr>
				<td class='noborder'><input type='text' name='igb_room' maxlength='13'  
					value='";
			if (isset($igb_room)){
				$add_form_html .= $igb_room;
			}
$add_form_html .="' > 
					
				</td>
				<td class='noborder'>
					<input type='text' class='phone'  name='igb_phone' maxlength='14'  
					value='";
			if (isset($igb_phone)){
				$add_form_html .= $igb_phone;
			}
$add_form_html .="' > 
				</td>
			  </tr>
			</table>
			
			<div class='alignright'>
			
			<input type='radio' name='default_address' value='IGB' "; 
				if ($default_address == 'IGB') 
					{$add_form_html .= $checked;} 
$add_form_html .="> Set IGB as preferred address
			
			</div>
			</div>";

/*
DEPARTMENT
*/
			
$add_form_html .="<div class='section'>
			 
			<table class='small'>
			  <tr>
				<td class='noborder'><label class='optional'><b /> Department </b>(if other than IGB)</label></td>
				
			  </tr>
			  <tr>
				<td class='noborder'>". dropdown( 'dept_drop', $dept_list, $dept_drop )."</td>
				
							
			  </tr>
			</table>  
			<table class='small'>
			  <tr>
				<td class='noborder'><label class='optional'>Department Address </label></td>
				<td class='noborder'><label>Apt / Room/ Suite</label>
				</td>
			  </tr>
			  <tr>
				<td class='noborder'><input type='address' name='dept_address1' maxlength='150'  
					value='";
			if (isset($dept_address1)){
				$add_form_html .= $dept_address1;
			}
$add_form_html .="' > 
				</td>
				
				<td class='noborder'><input type='text' name='dept_address2' maxlength='50'  
					value='";
			if (isset($dept_address2)){
				$add_form_html .= $dept_address2;
			}
$add_form_html .="' > 
				</td>
				
			  </tr>
			</table>
			<table class='small'>
				<tr>
					<td class='noborder'><label class='optional'>City </label></td>
					<td class='noborder'><label class='optional'> State</label></td>
					<td class='noborder'><label class='optional'> Zip Code</label></td>
				</tr>
				
				<tr>
					<td class='noborder'><input type='medium' name='dept_city' maxlength='30'  
						value='";
			if (isset($dept_city)){
				$add_form_html .= $dept_city;
			}
$add_form_html .="' > 
					</td>
					
					<td class='noborder'>". simple_drop( 'dept_state', $states_arr, $dept_state ).
					"</td>
					
					
					<td class='noborder'>
						<input type='small' name='dept_zip' maxlength='10' value='";
						
				if (isset($dept_zip)){
				$add_form_html .= $dept_zip;
			}
					
						
						
$add_form_html .="' ></td>
					
			  </tr></table>
			  
			  
			  
			  
			
			<div class='alignright'>
			<input type='radio' name='default_address' value='DEPT' "; 

				if (empty($dept_address1))
					{/*echo "disabled"*/;}
				if ($default_address == 'DEPT') {
					$add_form_html .= $checked;} 
					
$add_form_html .="> Set Dept as preferred address
			
			</div>
			</div>";
		
			
$add_form_html .="<div class='section'>
		</table>
		
		<table class='small'>
		  <tr>
			<td class='noborder'><label class='required'>Permanent Home Address</label>
				<label class='error'>" .$aster[empty($home_address1)]."</label>
			</td>
			<td class='noborder'><label>Apt / Room/ Suite</label>
			</td>
		  </tr>
		  <tr>
			<td class='noborder'><input type='address' name='home_address1' maxlength='150'  
				value='";
			if (isset($home_address1)){
				$add_form_html .= $home_address1;
			}
$add_form_html .="' > 
			</td>
			<td class='noborder'><input type='text' name='home_address2' maxlength='50'  
				value='";
			if (isset($home_address2)){
				$add_form_html .= $home_address2;
			}
$add_form_html .="' > 
			</td>
		  </tr>
		</table>
		<table class='small'>
			<tr>
				<td class='noborder'><label class='required'>City</label>
				<label class='error'>". $aster[empty($home_city)]." </label>
				</td>
				<td class='noborder'><label class='required'>State</label>
				<label class='error'>". $aster[empty($home_state)]."</label>
				</td>
				<td class='noborder'><label class='required'>Zip Code</label>
				<label class='error'>". $aster[empty($home_zip)]." </label>
				</td>
			</tr>
			<tr>
				<td class='noborder'><input type='medium' name='home_city' maxlength='30'  
					value='";
			if (isset($home_city)){
				$add_form_html .= $home_city;
			}
$add_form_html .="' > 
				</td>
				<td class='noborder'>". simple_drop( 'home_state', $states_arr, $home_state ).
				"</td>
				<td class='noborder'><input type='small' name='home_zip' maxlength='30'  
					value='";
			if (isset($home_zip)){
				$add_form_html .= $home_zip;
			}
$add_form_html .="' > 
				</td>
			</tr>
		</table>
		
		<div class='alignright'>
		<input type='radio' name='default_address' value='HOME' "; 
		if ($default_address == 'HOME') {
			$add_form_html .= $checked;} 
		
$add_form_html .="> Set Home as preferred address
		
		</div>
		</div>";
			
/*
THEMES & TYPE
*/
		
$add_form_html .="<div class = 'left sixty'>
		<table>
		
			<tr>
			  <td class='xs'><label class='required'>Themes </label>
				<label class='error'>". $aster[empty($theme_drop)]." </label>
			  </td>          
			  <td class='xs'></td>          
			  <td class='noborder'><label class='required'>Type </label>
				<label class='error'>". $aster[empty($type_drop)]." </label>
			  </td>  
			</tr>
			<tr>
			  <td class='xs'>
					". dropdown( 'theme_drop', $theme_list, $theme_drop )." 
			  </td>
			  <td class='xs'>
					". dropdown( 'other_theme_drop', $theme_list, $other_theme_drop )."
			  </td>    
			  <td class='noborder'>
					". dropdown( "type_drop", $type_list, $type_drop )."
			  </td>  
			</tr>
		</table>
		
		<table>
			<tr>     
			  <td  class='noborder'><label class='required'>Start Date</label> <label class='optional'>(YYYY-MM-DD)</label>
				<label class='error'>". $aster[empty($start_date)]." </label>
			  </td>
			  <td class='noborder'><label class='required'>Supervisor (NetID) </label>
				<label class='error'>". $aster[empty($supervisor)]." </label>
			  </td>   
			</tr>
			<tr>  
			  <td class='noborder'><input type='date' name='start_date'   
					value='";
			if (isset($start_date)){
				$add_form_html .= $start_date;
			}
$add_form_html .="' >     
			  </td>    
		
			  <td class='noborder' >
					<input type='small' name='supervisor' maxlength='8'  
					value='";
			if (isset($supervisor)){
				$add_form_html .= $supervisor;
			}
			
$add_form_html .="' > 
			<a class='search' id='search' href='#'>search</a>
			  </td>   
			</tr>
		
		</table>
		<table class='small'>
			<tr> 
			  <td class='xs' ><label>Expected Grad Date (if student) </label>
				
			  </td> 
			</tr>
			<tr> 
			  <td class='xs'>
					". drop( "grad_drop", $semester_arr, $grad_drop )."
			 
					".drop( "year_drop", $year_arr, $year_drop )."
			  </td>
			</tr>
		
		</table>
		
		</div>";
			
$add_form_html .="<div class = 'right forty'>
		<label class='required'>Gender </label>
		<label class='error'>". $aster[empty($gender)]." </label> 
		
		<input type='radio' name='gender' value='M' ";
		   if ($gender == 'M') {$add_form_html .= $checked;} 
		   $add_form_html .=">M
		<input type='radio' name='gender' value='F' ";
		   if ($gender == 'F') {$add_form_html .= $checked;} 
		   $add_form_html .=">F
		<br />
			<input type='checkbox' name='key_deposit' value='checked' ". $key_deposit .">
			<label class='required'>Key Deposit </label> 
		<br />
			<input type='checkbox' name='prox_card' value='checked' ". $prox_card .">
			<label class='required'>Prox Card Payment </label>
			
		<br />
			<input type='checkbox' name='safety_training' value='checked' ".  $safety_training .">
			<label class='required'>Safety Training </label>
		<br />
			<input type='checkbox' name='admin' value='checked' ".$admin .">
			<label class='required'>Admin</label>
		
		<br />
		<br /><br />
		</div>
		<div class='alignright'>
			<input type='submit' name='add' value='Create' class='btn'>
			<input type='reset' name='clear' value='Clear' class='btn'>
		</div>
		
		</div> 
	</form>";





$search = '
SEARCH<BR>
<form method="post" action="search.php" name="search">



<div class="section">

    	<input type="address" name="search_value" maxlength="50"
    	value="" >

    <input type="submit" name="search" value="Search" class="btn"> 
	<input type="reset" name="clear" value="Clear" class="btn">

<br>

<br>
</form>
'
?> 


<script>
$(document).ready(function(){

	$("input.search").click(function(){
	//$("div.search").show();
	});

	//$(".example8").colorbox({width:"60%", inline:true, href:"#inline_example1"});
	$('a#search').colorbox({width:"80%", inline:true, height:"90%", href:"#display_search"});



});
</script>
	


<?php 

echo $add_header;	
if ($success){
	echo $success_html;	
}
else{
	echo $add_form_html; 
}
include ("includes/footer.inc.php"); 

?> 


<?php


echo "<body onLoad=\"document.search.search_value.focus()\">"; 


$table_html = "";

$filters = NULL;
	
$user = new user($db);
$html = "";
$htmltest = "";
/*
	for($i=0; $i<26; $i++){
		
		$letter = $alphabet[$i];
		$search_results = $user->alpha_search($letter, '1');
		
		if (count($search_results) == 0) { 
		  	$html .= "<input type='button' name='".$letter."' id='".$letter."' value='".$letter."' 
					class='alphabutton disabled' > ";
			$htmltest .=  "<a href='javascript:TINY.box.fill(\"ajax.html\",1,0,1)'> ".$letter." </a>";
	  	}
		
		else {
			$html .= "<input type='button' name='".$letter."' id='".$letter."' value='".$letter."' 
					class='alphabutton' > ";
					
			$htmltest .=  "<a href='javascript:TINY.box.load(boxid:".$letter.",1,0,1)'> ".$letter." </a>";
	
			//$letter_html .= "<div class='box' id='".$letter."' style='display:none;'>";
			//$letter_html .= result_list($search_results);
			//$letter_html .= "</div>";
			
		
		}
		


	}
*/


?> 
	<div style='display:none'>
		<div id='inline_example1' >
        <?php
			echo $html; 
			echo $letter_html;
		?>
		</div>
        
        <div id="display_search">
        <?php
		   echo $search;
		   //echo "SEARCH GOES HERE";
		?>
        </div>
	</div>
