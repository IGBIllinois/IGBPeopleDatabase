<?php 

$page_title = "IGB People Database"; 

include 'includes/header.inc.php'; 
include 'includes/functions.inc.php'; 
include_once 'includes/main.inc.php';


if (!$_SESSION['admin']){
header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/login.php"); 	
exit(); 
}


$dept_list = $db->query($select_dept);
$error_msg = "";

/*
DEPT INFO TABLE HTML



*/
$dept_table = "<div class='left sixty'>

			
			<div class='noborder'>
				".
					dept_list_table("dept_list_table",$dept_list)
					."
				
			</div>
			</div>
			<br>
			";
			

/*
Add DEPT
*/

if (isset($_POST['add_dept'])){
	$dept_name = $_POST['dept_name'];
	$dept_code = $_POST['dept_code'];
	$error_msg = "";
	
	$key_exists = NULL;
	
	if (empty($dept_name)){  
		$error_msg .= "Please enter department name<br>";
		$error_count++;
		
	}
	if (empty($dept_code)){  
		$error_msg .= "Please enter department code<br>";
		$error_count++;
		
	}
	/*
	else if (!empty($key_exists)){  
		$error_msg = "Key already exists";
		$error_count++;
	}
	*/
	
	if ($error_count == 0){
		$dept_name = trim(rtrim($dept_name));
		$dept_name = mysql_real_escape_string($dept_name,$db->get_link());
		$dept_code = trim(rtrim($dept_code));
		$dept_code = mysql_real_escape_string($dept_code,$db->get_link());
		
		$add_dept_query = "INSERT INTO department (name, dept_code)
							VALUES ('".$dept_name."','".$dept_code."'	)";
		$result = $db->insert_query($add_dept_query);
		
		
		
		unset($_POST['add_dept']);
		$redirectpage= "/dept_edit.php";
		header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . $redirectpage); 	
		exit(); 
	}

}			
			
			
/*
DEPT ADD FORM HTML



*/
$dept_add_table = "<form method='post' action='dept_edit.php' name='add_dept'>

		<div class='right forty bordered'>
			<div class='profile_header'>
				<p class='alignleft'>[ Add department ]</p>
			</div>
			<div class='noborder'>
			 	<label class='errormsg'>".$error_msg."</label><br>
				
				<table class = 'profile'>
					<tr >
					  <td class='noborder'><label>Name </label><br> </td>
					  <td class='noborder'>
					  	<input type='medium' name='dept_name' maxlength='50'  >
					  </td>
					</tr>
					<tr >
					  <td class='noborder'><label>Code</label><br> </td>
					  <td class='noborder'>
					  	<input type='medium' name='dept_code' maxlength='12'  >
					  </td>
					</tr>
				</table>
				
			</div>
			<div class='alignright'>
					<input type='submit' name='add_dept' id='add_dept' value='Add'  >
					
				</div >
			
			<br></div>
			</form>
			";			

	

?> 
 


<script>
$(document).ready(function(){




	$('#dept_list_table').dataTable( {
		"bPaginate": true,
		"bLengthChange": true,
		"aLengthMenu": [[10, 15, 20], [10, 15, 20]],
		"bFilter": true,
		"bSort": false,
		"bInfo": true,
		"bAutoWidth": false } );


});
</script>


<h1> departments </h1>

<h3>
[ see 
<a href='http://www.fms.uiuc.edu/ClassScheduling/SubjectsAndDepartmentInfo/index.asp?report=Subjects_and_Departments_(by%20Department).xml'>
here</a> for additional department codes ]

</h3>
<?php 
	echo $dept_table;
	echo $dept_add_table;
	
	


	
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
