<?php 

$page_title = "IGB People Database"; 

include 'includes/header.inc.php'; 
include 'includes/functions.inc.php'; 
include_once 'includes/main.inc.php';


if (!$_SESSION['admin']){
header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/login.php"); 	
exit(); 
}


$type_list = $db->query($select_all_types);
$error_msg = "";

/*
TYPE INFO TABLE HTML



*/
$type_table = "<div class='left sixty'>
			<div class='noborder'>
				".
					type_list_table("type_list_table",$type_list)
					."
				
			</div>
			</div>
			<br>
			";
	

/*
Add TYPE
*/
global $db;


if(!empty($_GET['success'])) {
    echo("<h3>Type ". $_GET['type_name'] . ' successfully added.<BR></h3>');
}
if (!empty($_POST['add_type']) && !empty($_POST['type_name'])){

	$type_name = $_POST['type_name'];
	$error_msg = "";
	echo("type name = $type_name<BR>");
	
	if (empty($type_name)){  
		$error_msg .= "Please enter type name<br>";
		$error_count++;
		
	}
	/*
	else if (!empty($key_exists)){  
		$error_msg = "Key already exists";
		$error_count++;
	}
	*/
	
	if ($error_count == 0){
            echo("1<BR>");
		$type_name = trim(rtrim($type_name));
		$type_name = mysqli_real_escape_string($db->get_link(), $type_name);
		
		$add_query = "INSERT INTO type (name) VALUES ('".$type_name."')";
                echo($add_query);
		$result = $db->insert_query($add_query);
		
		
		
		unset($_POST['add_type']);
		$redirectpage= "/type_edit.php?success=true&type_name=".  htmlentities($type_name);
		header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . $redirectpage); 	
		exit(); 
	}

}	
			
/*
TYPE ADD FORM HTML



*/
$type_add_table = "<form method='POST' action='type_edit.php' name='add_type'>

		<div class='right forty bordered'>
			<div class='profile_header'>
				<p class='alignleft'>[ Add type ]</p>
			</div>
			<div class='noborder'>
			 	<label class='errormsg'>".$error_msg."</label><br>
				
				<table class = 'profile'>
					<tr >
					  <td class='noborder'><label>Name </label><br> </td>
					  <td class='noborder'>
					  	<input type='medium' name='type_name' maxlength='50'  >
					  </td>
					</tr>
				</table>
				
			</div>
			<div class='alignright'>
					<input type='submit' name='add_type' id='add_type' value='Add'  >
					
				</div >
			
			<br></div>
			</form>
			";		


/*
CHANGE TYPE STATUS
*/

if (isset($_POST['edit_type'])){
	$type_id = $_POST['type_id'];
	$type_status = $_POST['type_status'];
	
		
		$type_query = "UPDATE type SET type_active ='".$type_status."' WHERE type_id = '".$type_id."'";
		$result = $db->query($type_query);
		
		
		
		unset($_POST['edit_type']);
		$redirectpage= "/type_edit.php";
		header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . $redirectpage); 	
		exit(); 
	

}			
			
/*
TYPE STATUS FORM HTML



*/
$type_edit_table = "



<form method='post' action='type_edit.php' name='edit_type' id='edit_type'>
<br>
		<div class='right forty bordered'>
			<div class='profile_header'>
				<p class='alignleft'>[ Change type status ]</p>
			</div>
			<div class='noborder'>
			 	<label class='errormsg'></label><br>
				
				<table class = 'profile'>
					<tr >
					  <td class='xs'><label>Type:  </label><br> </td>
					  <td class='xs'>
					  	".dropdown('type_id', $type_list )."
					  </td>
					</tr>
					<tr >
					  <td class='xs'><label>Set to:  </label><br> </td>
					  <td class='xs'>
					  	".simple_drop('type_status', $status_arr )."
					  </td>
					</tr>
				</table>
			</div>
			<div class='alignright'>
					<input type='submit' name='edit_type' id='edit_type' value='Update'  >
			</div >
			</div><br></form>
			";
		


?> 
 


<script>
$(document).ready(function(){
 $("ul#admin").show();
 $("ul#directory").hide();
	$('#type_list_table').dataTable( {
		"bPaginate": false,
		"bLengthChange": false,
		"bFilter": false,
		"bSort": false,
		"bInfo": false,
		"bAutoWidth": false } );

	var r;
	function confirm_change()
	{
		r=confirm("Are you sure you want to change this status?");
	}
				
	$('#edit_type').submit(function(){ 
			confirm_change();
			if(r==false){
				return false;
			}	 
		});

});
</script>


<h1> Type management </h1>

<h3>


</h3>
<?php 
	echo $type_table;
	
	echo $type_add_table;
	echo $type_edit_table;
	


	
?>


<div style='display:none'>
		<div id='theme_add_table'>
        <?php
			//echo $theme_add_table;
		?>
		</div>
        

        
        
        
</div>
<br>
<?php 

include ("includes/footer.inc.php"); 

?> 
