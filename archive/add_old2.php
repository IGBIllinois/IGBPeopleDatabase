<?php #add new ticket 

$page_title = "IGB Facilities Add New Record"; 
include_once 'includes/main.inc.php';
include 'includes/header.inc.php'; 
include 'includes/functions.inc.php'; 

/*if ($_SESSION['group'] != "admin"){
header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/search.php"); 	
exit(); 
}



echo "<body onLoad=\"document.add.first_name.focus()\">"; */


if (isset($_POST['add'])){
	
$user_id = "";	
$last_name = $_POST['last_name']; 
$first_name = $_POST['first_name']; 
$netid = $_POST['netid']; 
$uid = $_POST['uid']; 
$email = $_POST['email']; 

$igb_room = $_POST['igb_room']; 
$igb_phone = $_POST['igb_phone']; 
$dept_phone = $_POST['dept_phone']; 
$cell_phone = $_POST['cell_phone']; 
$fax = $_POST['fax']; 
$dept_address = $_POST['dept_address']; 
$dept_address2 = $_POST['dept_address2']; 
$dept_city = $_POST['dept_city']; 
$dept_state = $_POST['dept_state']; 
$dept_zip = $_POST['dept_zip']; 
$home_address = $_POST['home_address']; 
$home_address2 = $_POST['home_address2']; 
$home_city = $_POST['home_city']; 
$home_state = $_POST['home_state']; 
$home_zip = $_POST['home_zip']; 

$default_address = $_POST['default_address']; 

$theme_drop = $_POST['theme_drop'];
$other_theme_drop = $_POST['other_theme_drop'];
$type_drop = $_POST['type_drop'];
$dept_drop = $_POST['dept_drop'];
$supervisor = $_POST['supervisor'];

$start_date = $_POST['start_date']; 
$gender = $_POST['gender']; 
$key_deposit = $_POST['key_deposit']; 
$training = $_POST['training']; 
$prox_card = $_POST['prox_card']; 

$error="";
$aster= array(1 => " * ");
	
	
	

if (empty($first_name) || empty($last_name) || empty($netid) || empty($email) ||  empty($uid) || 
	empty($igb_room) || empty($igb_phone) || empty($start_date) || 
	empty($home_address) || empty($home_city) || empty($home_state) || empty($home_zip) ||
	empty($theme_drop) || empty($type_drop) || empty($gender) || empty($supervisor) 
	)
{
	$error="* Please enter missing values ";
	/*$first_name = NULL; 
	$last_name = NULL; 
	$netid = NULL; 
	$email = NULL; 
	$uid = NULL; 
	$igb_room = NULL; 
	$igb_phone = NULL; 
	$start_date = NULL; 
	$home_address = NULL; 
	$home_city = NULL; 
	$home_state = NULL; 
	$home_zip = NULL; 
	$theme_drop = NULL; 
	$type_drop = NULL; 
	$gender = NULL; 
	$supervisor = NULL; */
}



else{// && $pri_phone && $igb_address && $theme && $department && $type && $univ_id && $supervisor && $start_date){

		
		//IN PRODUCTION NOW:11/15 -- check to see if netid exists already -- Not in production phase I since they don't have all the information that they need and they wanted to get started
		/*$querya = "select * from people where net_id = '$net_id'"; 
		$resulta = mysql_query($querya); 
		$numa = mysql_num_rows($resulta); 
		$new_user = TRUE; 
		if ($numa){
		$new_user = FALSE; 
		echo "<b class=b>There is already a user named $net_id<br /></b>"; 
		}
		
		if (isset($new_user)){
			if (is_numeric($igb_address)){
			if ($igb_address < 10){
				$igb_address= "00$igb_address"; 
			}	
				
				if ($igb_address >= 10 && $igb_address < 100){
					$igb_address = "0$igb_address"; 
				}
		}
		

		
		$query = "insert into people(last_name, first_name, netid,  igb_address, igb_city, igb_state, igb_zip_code, dept_address, dept_city, dept_state, dept_zip_code, home_address, home_city, home_state, home_zip_code, pri_phone, cel_phone, fax, pager, email, theme, other_theme, department, type, univ_id, supervisor, preferred_address, start_date, gender, key_deposit, training, other_phone, prox_card_payment) values ('$last_name', '$first_name', '$netid',  '$igb_address', '$igb_city', '$igb_state', '$igb_zip_code', '$dept_address', '$dept_city', '$dept_state', '$dept_zip_code', '$home_address','$home_city', '$home_state', '$home_zip_code', '$pri_phone', '$cel_phone', '$fax', '$pager', '$email', '$theme', '$other_theme', '$department', '$type', '$univ_id', '$supervisor', '$preferred_address', '$start_date', '$gender', '$key_deposit', '$training', '$other_phone', '$prox_card_payment')";  
		$result = mysql_query($query); 
			if ($result){
				echo "<b class=b>A NEW RECORD HAS BEEN CREATED</b><br />"; 
				$query = "select * from people order by id desc limit 1"; 
				$result = mysql_query($query); 
				$row = mysql_fetch_array($result);
				echo "<b class=b><u>Click <a href=\"update.php?id=$row[0]\">here</a> to view now</b></u><p>"; 
				echo "<b class=b9><a href=add.php>ADD ANOTHER NEW USER? </a></b>"; 
				
			}
		//}*/
	}
}
?> 


<h1> Add New IGB Member</h1>
<h3>[ All fields in bold are required ]</h3>
<label class="errormsg"><?php echo $error; ?></label>
<form method="post" action="add.php" name="add">

<div class="section">
<table class="large">
  <tr>
    <td class="medium">
    	<label class="required">First Name </label>
		<label class="error"><?php echo $aster[empty($first_name)];?> </label>
    </td>
    <td class="medium">    
    	<label class="required">Last Name </label> 
		<label class="error"><?php echo $aster[empty($last_name)];?> </label>
    </td>
  </tr>
  <tr>
    <td class="medium"><input type="name" name="first_name" class="space" maxlength="30"  
    	value="<?php if (isset($first_name)){echo "$first_name";}else{echo "";} ?>" >
        </td>
    <td class="medium"><input type="name" name="last_name" class="space" maxlength="30"  
    	value="<?php if (isset($last_name)){echo "$last_name";}else{echo "";} ?>" > 
        </td>

  </tr>
</table>
<table class="medium">
    <tr>
        <td class="small">    
    		<label class="required">NetID </label> 
		<label class="error"><?php echo $aster[empty($netid)];?> </label>
    	</td>
    	<td class="small">
        	<label class="required">UIN</label> 
		<label class="error"><?php echo $aster[empty($uin)];?> </label>
        </td>
    	<td class="medium">
       		<label class="required">Email</label> 
		<label class="error"><?php echo $aster[empty($email)];?> </label>
        </td>
	

	</tr>
    <tr>
        <td class="small">
        	<input type="small" name="netid" class="space" maxlength="8"  
    		value="<?php if (isset($netid)){echo "$netid";}else{echo "";} ?>" > 
        </td>
   	    <td class="small">
        	<input type="small" name="uid" class="space" maxlength="9"  
    		value="<?php if (isset($uid)){echo "$uid";}else{echo "";} ?>" >
        </td>
    	<td class="medium">
        	<input type="long" name="email" class="space" maxlength="50"  
    		value="<?php if (isset($email)){echo "$email";}else{echo "";} ?>" >
        </td>
     </tr>
</table>

<table class = "small">
    <tr >
      <td><label class="optional">Dept Phone </label></td>
      <td><label class="optional">Cell Phone</label></td>
      <td><label class="optional">Fax</label></td>
    </tr>
    <tr>
      <td>
          <input type="small" name="dept_phone" class="space" maxlength="12"  
          value="<?php if (isset($dept_phone)){echo "$dept_phone";}else{echo "";} ?>" >
      </td>
      <td>
          <input type="small" name="cell_phone" class="space" maxlength="12"  
          value="<?php if (isset($cell_phone)){echo "$cell_phone";}else{echo "";} ?>" >
      </td>
      <td>
          <input type="small" name="fax" class="space" maxlength="12"  
          value="<?php if (isset($fax)){echo "$fax";}else{echo "";} ?>" >
      </td>

   </tr>

</table>

<br />
</div>

<div class="section">
<table class="small">
  <tr>
    <td><label class="required">IGB Office/Lab/Cubicle # </label>
		<label class="error"><?php echo $aster[empty($igb_room)];?> </label>
    </td>
    <td><label class="required">IGB Phone</label>
		<label class="error"><?php echo $aster[empty($igb_phone)];?> </label>
    </td>
  </tr>
  <tr>
    <td><input type="small" name="igb_room" class="space" maxlength="12"  
        value="<?php if (isset($igb_room)){echo "$igb_room";}else{echo "";} ?>" >
    </td>
    <td>
        <input type="small" name="igb_phone" class="space" maxlength="12"  
        value="<?php if (isset($igb_phone)){echo "$igb_phone";}else{echo "";} ?>" >
    </td>
  </tr>
</table>

<div class="alignright">
<input type="radio" name="default_address" value="IGB" /> Set IGB as preferred address

</div>
</div>

<?php 

/*
DEPARTMENT
*/

$dept_list = $db->query($select_dept);



?>	

<div class="section">
 
<table class="small">
  <tr>
    <td><label class="optional"><b /> Department </b>(if other than IGB)</label></td>
  </tr>
  <tr>
    <td><?php echo dropdown( "dept_drop", $dept_list, $default );  /*"dept_id", "name", */?></td>
  </tr>
  <tr>
    <td><label class="optional">Department Address </label></td>
  </tr>
  <tr>
    <td><input type="address" name="dept_address" class="space" maxlength="150"  
    	value="<?php if (isset($dept_add)){echo "$dept_address";}else{echo "";} ?>" ></td>
  </tr>
</table>
<table class="small">
    <tr>
        <td><label class="optional">City </label></td>
        <td class="state"><label class="optional"> State</label></td>
        <td><label class="optional"> Zip Code</label></td>
    </tr>
    <tr>
        <td><input type="medium" name="dept_city" class="space" maxlength="30"  
            value="<?php if (isset($dept_city)){echo "$dept_city";}else{echo "";} ?>" >
        </td>
        <td class="state"><input type="state" name="dept_state" class="space" maxlength="2"  
            value="<?php if (isset($dept_state)){echo "$dept_state";}else{echo "";} ?>" >
        </td>
        <td><input type="small" name="dept_zip" class="space" maxlength="30"  
            value="<?php if (isset($dept_zip)){echo "$dept_zip";}else{echo "";} ?>" >
        </td>
  
    </tr>
</table>

<div class="alignright">
<input type="radio" name="default_address" value="Dept" /> Set Dept as preferred address

</div>
</div>





<div class="section">
</table>

<table class="small">
  <tr>
    <td><label class="required">Permanent Home Address</label>
		<label class="error"><?php echo $aster[empty($home_address)];?> </label>
    </td>
  </tr>
  <tr>
    <td><input type="address" name="home_address" class="space" maxlength="150"  
    	value="<?php if (isset($home_address)){echo "$home_address";}else{echo "";} ?>" ></td>
  </tr>
</table>
<table class="small">
    <tr>
        <td><label class="required">City</label>
		<label class="error"><?php echo $aster[empty($home_city)];?> </label>
        </td>
        <td class="state"><label class="required">State</label>
		<label class="error"><?php echo $aster[empty($home_state)];?> </label>
        </td>
        <td><label class="required">Zip Code</label>
		<label class="error"><?php echo $aster[empty($home_zip)];?> </label>
        </td>
    </tr>
    <tr>
        <td><input type="medium" name="home_city" class="space" maxlength="30"  
            value="<?php if (isset($home_city)){echo "$home_city";}else{echo "";} ?>" >
        </td>
        <td class="state"><input type="state" name="home_state" class="space" maxlength="2"  
            value="<?php if (isset($home_state)){echo "$home_state";}else{echo "";} ?>" >
        </td>
        <td><input type="small" name="home_zip" class="space" maxlength="30"  
            value="<?php if (isset($home_zip)){echo "$home_zip";}else{echo "";} ?>" >
        </td>
    </tr>
</table>

<div class="alignright">
<input type="radio" name="default_address" value="Home" /> Set Home as preferred address

</div>
</div>


<?php 

/*
THEMES & TYPE
*/

$theme_list = $db->query($select_theme);
$type_list = $db->query($select_type);


?>

<div class = "noborder">
<table class="large">

    <tr>
      <td><label class="required">Themes </label>
		<label class="error"><?php echo $aster[empty($theme_drop)];?> </label>
      </td>          
      <td></td>           
      <td class="medium"><label class="required">Type </label>
		<label class="error"><?php echo $aster[empty($type_drop)];?> </label>
      </td>          
      <td class="large">
      	  <label class="required">Gender </label>
		<label class="error"><?php echo $aster[empty($gender)];?> </label>
          <?php 
          if (!isset($gender)){
          ?>
          <input type="radio" name="gender" value="M" >M
          <input type="radio" name="gender" value="F">F
          <?
          }else{
          ?> 
          <input type="radio" name="gender" value="male" <?php echo $gender=="male" ? "checked" : "male"; ?>>Male<br />
          <input type="radio" name="gender"  value="female" <?php echo $gender=="female" ? "checked" : "female"; ?> >Female
          <?
          }
          ?>
      </td>
    </tr>
    <tr>
      <td>
	  		<?php echo dropdown( "theme_drop", $theme_list, $default ); ?> 
      </td>
      <td class="medium">
	  		<?php echo dropdown( "other_theme_drop", $theme_list, $default ); ?>
      </td>          
      <td>
      		<?php echo dropdown( "type_drop", $type_list, $default );?>
      </td>  
      <td class="large">
      		<input type="checkbox" name="key_dep" value="yes" />
            <label class="required">Key Deposit </label> 
            
      </td>
    </tr>
    <tr>
      <td  class="small" colspan="2"><label class="required">Start Date</label>
		<label class="error"><?php echo $aster[empty($start_date)];?> </label>
      </td>
      <td class="medium"><label class="required">Supervisor (NetID) </label>
		<label class="error"><?php echo $aster[empty($supervisor)];?> </label>
      </td>
      <td class="large">
      		<input type="checkbox" name="prox_card" value="yes" />
            <label class="required">Prox Card Payment </label>
            
      </td>
    </tr>
    <tr>
      <td class="small" colspan="2"><input type="text" name="start_date" class="space" maxlength="30"  
            value="<?php if (isset($start_date)){echo "$start_date";}else{echo "";} ?>" >    
      </td>

      <td class="medium">
      		<input type="small" name="supervisor" class="space" maxlength="8"  
            value="<?php if (isset($supervisor)){echo "$supervisor";}else{echo "";} ?>" >
      </td> 
      <td class="large">
      		<input type="checkbox" name="training" value="yes" />
            <label class="required">Training </label>
          
      </td>
    </tr>
        <tr>
      <td><label class="required"></label></td>          
      <td><label class="required"></label></td>        
      <td class="medium"></td>
    </tr>
    <tr>
      <td>
      </td>       
      <td>
      <td></td> 
      </td>       
      <td class="large">
          
      </td>
    </tr>
</table>



</div>



<br />
<br /><br />

<div class="alignright">
	<input type="submit" name="add" value="Create" class="btn">
	<input type="reset" name="clear" value="Clear" class="btn">
</div>


	</form>
 
<?php 

include ("includes/footer.inc.php"); 

?> 
