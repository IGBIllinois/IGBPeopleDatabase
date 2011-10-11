<?php #add new ticket 

$page_title = "IGB Facilities Add New Record"; 

include 'includes/header.inc.php'; 
include 'includes/functions.inc.php'; 
include_once 'includes/main.inc.php';



/*if ($_SESSION['group'] != "admin"){
header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/search.php"); 	
exit(); 
}



echo "<body onLoad=\"document.add.first_name.focus()\">"; */


$dept_list = $db->query($select_dept);
$theme_list = $db->query($select_theme);
$type_list = $db->query($select_type);

$alpha = "";
$user = new user($db);





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
$txtphone = $_POST['txtphone'];

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

$error="";
$error_count=0;
$empty_form=FALSE;
$aster= array(1 => " * ");
$checked = "checked";
	

$search_value = $_POST['search_value'];

if (empty($first_name) || empty($last_name) || empty($netid) || empty($email) ||  empty($uin) || 
	empty($igb_room) || empty($igb_phone) || empty($start_date) || 
	empty($home_address1) || empty($home_city) || empty($home_state) || empty($home_zip) ||
	empty($theme_drop) || empty($type_drop) || empty($gender) || empty($supervisor) 
	)
{
	$error.="* Please enter missing values<br>";
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
									  $safety_training, $gender, $supervisor_id);
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
			
			$insert_phone = $user->add_phone($user_id, $igb_phone, $dept_phone, $cell_phone, $fax);
			
			$error = "* User successfully added into the database"	;
		}
		else {
			$error = "* Error occurred when adding user.  Please try again."	;
		}
			
	  
	  }
  }
}


if (isset($_POST['search'])){



$search_value = $_POST['search_value'];


$table_html = "";



		$user = new user($db);
		$count = $user->num_rows_adv($user_enabled, $search_value, $filters);
		$num_pages = ceil($count / 20);
		$page_list = "";
		if ($num_pages > 1){
			$page_list .= "<h3>";
			if ($page != 1){
				$x = $page-1;
			}
			else {$x = 1;}
			$page_list .= "<a href='search.php?page=".$x."'> << previous </a>";
			$x = 1;
			while ($x <= $num_pages){
				$page_list .= "<a href='search.php?page=".$x."'> ".$x." </a>";
				$x++;
			}
			if ($page != $num_pages){
				$x = $page+1;
			}
			else {$x = $num_pages;}
			$page_list .= "<a href='search.php?page=".$x."'> next >> </a></h3>";
		}			

	//if (!empty($search_value)){
		
		$search_results = $user->adv_search($user_enabled, $page, $search_value);
	//}

	$table_html = result_table( "search_results", $search_results );


	

}
					   




?> 
<script>
$(document).ready(function(){

  $("input.search").click(function(){
  /*$("div.search").show();*/
  });
  
  $("a#C").click(function(){
  $("div.C").show();
  
  });
  


});
</script>

<h1> Add New IGB Member</h1>
<h3>[ All fields in bold are required ]</h3>
<label class="errormsg"><?php echo $error; ?></label>

<form method="post" action="add.php#dialog" name="search">

<div class='search' style="display: none">

	<a href'#' id='A' class'alpha'>A</a>
	<a href'#' id='B' class'alpha'>B</a>
	<a href'#' id='C' class'alpha'>C</a>
	<a href'#' id='D' class'alpha'>D</a>
	<a href'#' id='E' class'alpha'>E</a>
	<a href'#' id='F' class'alpha'>F</a>
	<a href'#' id='G' class'alpha'>G</a>
	<a href'#' id='H' class'alpha'>H</a>
	<a href'#' id='I' class'alpha'>I</a>
    
    <br />
	<input type="large" name="search_value" maxlength="50"
    	value="<?php if (isset($search_value)){echo $search_value;}else{echo "";} ?>" >

    <input type="submit" name="search" value="Search" class="btn"> 
    <?php
	   
	    $test = $user->alpha_search('C');
		$hi = alpha_div('C', $test);	
		print($hi);						   
								   
	?>						   

</div>


</form>


<form method="post" action="add.php" name="add" id="add">


<div class="section">
<table class="medium">
  <tr>
    <td class="noborder">
    	<label class="required">First Name </label>
		<label class="error"><?php echo $aster[empty($first_name)];?> </label>
    </td>
    <td class="noborder">    
    	<label class="required">Last Name </label> 
		<label class="error"><?php echo $aster[empty($last_name)];?> </label>
    </td>
  </tr>
  <tr>
    <td class="noborder"><input type="name" name="first_name" class="space" maxlength="30"  
    	value="<?php if (isset($first_name)){echo "$first_name";}else{echo "";} ?>" >
        </td>
    <td class="noborder"><input type="name" name="last_name" class="space" maxlength="30"  
    	value="<?php if (isset($last_name)){echo "$last_name";}else{echo "";} ?>" > 
        </td>

  </tr>
</table>
<table class="medium">
    <tr>
        <td class="noborder">    
    		<label class="required">NetID </label> 
		<label class="error"><?php echo $aster[empty($netid)];?> </label>
    	</td>
    	<td class="noborder">
        	<label class="required">UIN</label> 
		<label class="error"><?php echo $aster[empty($uin)];?> </label>
        </td>
    	<td class="noborder">
       		<label class="required">Email</label> 
		<label class="error"><?php echo $aster[empty($email)];?> </label>
        </td>
	

	</tr>
    <tr>
        <td class="noborder">
        	<input type="small" name="netid" class="space" maxlength="8"  
    		value="<?php if (isset($netid)){echo "$netid";}else{echo "";} ?>" > 
        </td>
   	    <td class="noborder">
        	<input type="small" name="uin" class="space" maxlength="9"  
    		value="<?php if (isset($uin)){echo "$uin";}else{echo "";} ?>" >
        </td>
    	<td class="noborder">
        	<input type="large" name="email" class="space" maxlength="50"  
    		value="<?php if (isset($email)){echo "$email";}else{echo "";} ?>" >
        </td>
     </tr>
</table>

<table class = "small">
    <tr >
      <td class="noborder"><label class="optional">Dept Phone </label></td>
      <td class="noborder"><label class="optional">Cell Phone</label></td>
      <td class="noborder"><label class="optional">Fax</label></td>
    </tr>
    <tr>
      <td class="noborder">
          <input type="text" name="dept_phone" class="space" maxlength="13"  
          value="<?php if (isset($dept_phone)){echo "$dept_phone";}else{echo "";} ?>" >
      </td>
      <td class="noborder">
          <input type="text" name="cell_phone" class="space" maxlength="12"  
          value="<?php if (isset($cell_phone)){echo "$cell_phone";}else{echo "";} ?>" >
      </td>
      <td class="noborder">      
          <input type="text" name="fax" class="space" maxlength="12"  
          value="<?php if (isset($fax)){echo "$fax";}else{echo "";} ?>">
      </td>

   </tr>

</table>

<br />
</div>

<div class="section">
<table class="small">
  <tr>
    <td class="noborder"><label class="required">IGB Office/Lab/Cubicle # </label>
		<label class="error"><?php echo $aster[empty($igb_room)];?> </label>
    </td>
    <td class="noborder"><label class="required">IGB Phone</label>
		<label class="error"><?php echo $aster[empty($igb_phone)];?> </label>
    </td>
  </tr>
  <tr>
    <td class="noborder"><input type="small" name="igb_room" class="space" maxlength="12"  
        value="<?php if (isset($igb_room)){echo "$igb_room";}else{echo "";} ?>" >
    </td>
    <td class="noborder">
        <input type="text" name="igb_phone" class="space" maxlength="12"  
        value="<?php if (isset($igb_phone)){echo "$igb_phone";}else{echo "";} ?>" >
    </td>
  </tr>
</table>

<div class="alignright">

<input type="radio" name="default_address" value="IGB" 
<?php 
	if ($default_address == 'IGB') 
		{print $checked;} 
?>
/> Set IGB as preferred address

</div>
</div>

<?php 

/*
DEPARTMENT
*/



?>	

<div class="section">
 
<table class="small">
  <tr>
    <td class="noborder"><label class="optional"><b /> Department </b>(if other than IGB)</label></td>
  </tr>
  <tr>
    <td class="noborder"><?php echo dropdown( "dept_drop", $dept_list, $dept_drop );  ?></td>
  </tr>
  <tr>
    <td class="noborder"><label class="optional">Department Address </label></td>
  </tr>
  <tr>
    <td class="noborder"><input type="address" name="dept_address1" class="space" maxlength="150"  
    	value="<?php if (isset($dept_address1)){echo "$dept_address1";}else{echo "";} ?>" ></td>
  </tr>
</table>
<table class="small">
    <tr>
        <td class="noborder"><label class="optional">City </label></td>
        <td class="noborder"><label class="optional"> State</label></td>
        <td class="noborder"><label class="optional"> Zip Code</label></td>
    </tr>
    <tr>
        <td class="noborder"><input type="medium" name="dept_city" class="space" maxlength="30"  
            value="<?php if (isset($dept_city)){echo "$dept_city";}else{echo "";} ?>" >
        </td>
        <td class="noborder"><?php echo simple_drop( "dept_state", $states_arr, $dept_state );  ?>
        </td>
        <td class="noborder"><input type="small" name="dept_zip" class="space" maxlength="30"  
            value="<?php if (isset($dept_zip)){echo "$dept_zip";}else{echo "";} ?>" >
        </td>
  
    </tr>
</table>

<div class="alignright">
<input type="radio" name="default_address" value="DEPT" 
<?PHP 
	if (empty($dept_address1))
		{/*echo "disabled";*/}
	if ($default_address == 'DEPT') {
		print $checked;} 
?>
/> Set Dept as preferred address

</div>
</div>





<div class="section">
</table>

<table class="small">
  <tr>
    <td class="noborder"><label class="required">Permanent Home Address</label>
		<label class="error"><?php echo $aster[empty($home_address1)];?> </label>
    </td>
  </tr>
  <tr>
    <td class="noborder"><input type="address" name="home_address1" class="space" maxlength="150"  
    	value="<?php if (isset($home_address1)){echo "$home_address1";}else{echo "";} ?>" ></td>
  </tr>
</table>
<table class="small">
    <tr>
        <td class="noborder"><label class="required">City</label>
		<label class="error"><?php echo $aster[empty($home_city)];?> </label>
        </td>
        <td class="noborder"><label class="required">State</label>
		<label class="error"><?php echo $aster[empty($home_state)];?> </label>
        </td>
        <td class="noborder"><label class="required">Zip Code</label>
		<label class="error"><?php echo $aster[empty($home_zip)];?> </label>
        </td>
    </tr>
    <tr>
        <td class="noborder"><input type="medium" name="home_city" class="space" maxlength="30"  
            value="<?php if (isset($home_city)){echo "$home_city";}else{echo "";} ?>" >
        </td>
        <td class="noborder"><?php echo simple_drop( "home_state", $states_arr, $home_state );  ?>
        </td>
        <td class="noborder"><input type="small" name="home_zip" class="space" maxlength="30"  
            value="<?php if (isset($home_zip)){echo "$home_zip";}else{echo "";} ?>" >
        </td>
    </tr>
</table>

<div class="alignright">
<input type="radio" name="default_address" value="HOME" 
<?PHP if ($default_address == 'HOME') {
	print $checked;} 
?>
/> Set Home as preferred address

</div>
</div>


<?php 

/*
THEMES & TYPE
*/



?>

<div class = "left">
<table class="large">

    <tr>
      <td class="noborder"><label class="required">Themes </label>
		<label class="error"><?php echo $aster[empty($theme_drop)];?> </label>
      </td>          
      <td class="noborder"></td>           
      <td class="noborder"><label class="required">Type </label>
		<label class="error"><?php echo $aster[empty($type_drop)];?> </label>
      </td> 
    </tr>
    <tr>
      <td class="noborder">
	  		<?php echo dropdown( "theme_drop", $theme_list, $theme_drop ); ?> 
      </td>
      <td class="noborder">
	  		<?php echo dropdown( "other_theme_drop", $theme_list, $other_theme_drop ); ?>
      </td>          
      <td class="noborder">
      		<?php echo dropdown( "type_drop", $type_list, $type_drop );?>
      </td>
    </tr>
    <tr>
      <td  class="noborder" colspan="2"><label class="required">Start Date</label> <label class="optional">(YYYY-MM-DD)</label>
		<label class="error"><?php echo $aster[empty($start_date)];?> </label>
      </td>
      <td class="noborder"><label class="required">Supervisor (NetID) </label>
		<label class="error"><?php echo $aster[empty($supervisor)];?> </label>
      </td>
    </tr>
    <tr>
      <td class="noborder" colspan="2"><input type="date" name="start_date"   
            value="<?php if (isset($start_date)){echo "$start_date";}else{echo "";} ?>" >    
      </td>

      <td class="noborder" >
      		<input type="small" name="supervisor" id="supervisor_netid" class="space" maxlength="8"  
            value="<?php if (isset($supervisor)){echo "$supervisor";}else{echo "";} ?>" >
            
			<input type="button" class= 'search' name="search_super" value=">>" >
      </td> 
    </tr>
    

</table>



</div>


<div class = "right">


<label class="required">Gender </label>
<label class="error"><?php echo $aster[empty($gender)];?> </label>

<input type="radio" name="gender" value="M" 
   <?PHP if ($gender == 'M') {print $checked;} ?>>M
<input type="radio" name="gender" value="F" 
   <?PHP if ($gender == 'F') {print $checked;} ?>>F
<br />
    <input type="checkbox" name="key_deposit" value="checked" <?PHP print $key_deposit; ?>/>
    <label class="required">Key Deposit </label> 
<div class="key_div" style="display: none">
      		<input type="small" name="key_room" class="space" maxlength="8"  
            value="<?php if (isset($key_room)){echo "$key_room";}else{echo "";} ?>" >
</div>    
<br />
    <input type="checkbox" name="prox_card" value="checked" <?PHP print $prox_card; ?>/>
    <label class="required">Prox Card Payment </label>
    
<br />
    <input type="checkbox" name="safety_training" value="checked" <?PHP print $safety_training; ?>/>
    <label class="required">Safety Training </label>
  




<br />
<br /><br />
</div>
<div class="alignright">
	<input type="submit" name="add" value="Create" class="btn">
	<input type="reset" name="clear" value="Clear" class="btn">
</div>

</div> 
	</form>




<?php 

include ("includes/footer.inc.php"); 

?> 
