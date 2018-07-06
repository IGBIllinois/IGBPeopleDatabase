<?php 

$page_title = "IGB People Database"; 

include 'includes/header.inc.php';


if (!$_SESSION['admin']){
header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/login.php"); 	
exit(); 
}

if(isset($_GET['dept_id'])){$user_id = $_GET['dept_id'];}

$dept_list = $db->query($select_dept);
$error_msg = "";

echo "<script>";

echo "var deptArr = [];\n"; 
foreach($dept_list as $id=>$t)
{
        echo "deptArr[\"".$t['dept_id']."\"] = new Array(\"".$t['name']."\",\"".$t['dept_code']."\");\n";
}
echo "</script>"; 

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
		$dept_name = mysqli_real_escape_string($db->get_link(),$dept_name);
		$dept_code = trim(rtrim($dept_code));
		$dept_code = mysqli_real_escape_string($db->get_link(),$dept_code);
		
		$add_dept_query = "INSERT INTO department (name, dept_code)
							VALUES ('".$dept_name."','".$dept_code."'	)";
		$result = $db->insert_query($add_dept_query);
		
		
		
		unset($_POST['add_dept']);
                
                $dept_list = $db->query($select_dept);
		//$redirectpage= "/dept_edit.php";
		//header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . $redirectpage); 	
		//exit(); 
	}

}			
			
if (isset($_POST['submit_edit_dept'])){
        $dept_id = $_POST['edit_dept_drop'];
	$dept_name = $_POST['name'];
	$dept_code = $_POST['dept_code'];
	//echo("id = $dept_id");
	if (!empty($dept_id)){
            $query = "UPDATE department set name='". mysqli_real_escape_string($db->get_link(),$dept_name) . 
                    "', dept_code='".mysqli_real_escape_string($db->get_link(),$dept_code) .
                    "' where dept_id = '". $dept_id."'";
            //echo("query = $query");
            
            $db->non_select_query($query);
                    
		//$result = $theme->update($theme_id, 'themes', 'name', $theme_name);
		//$result = $theme->update($theme_id, 'themes', 'short_name', $theme_short_name);
	}
	unset($_POST['submit_edit_dept']);
	
        echo("<h3>Department information updated.</h3><BR>");
        $dept_list = $db->query($select_dept);
	//$redirectpage= "/dept_edit.php";
	//header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . $redirectpage); 	
	//exit();
	
}			

/*
DEPT INFO TABLE HTML



*/
$dept_table = "<div class='left sixty'>

			
			<div class='noborder'>
				".
					dept_list_table("dept_list_table ",$dept_list)
					."
				
			</div>
			</div>
			<br>
			";
			


	

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
/*
$dept_edit_table = "<form method='post' action='dept_edit.php' name='edit_dept'>

		<div class='right forty bordered'>
			<div class='profile_header'>
				<p class='alignleft'>[ Edit department ]</p>
			</div>
			<div class='noborder'>
			 	<label class='errormsg'>".$error_msg."</label><br>
				
				<table class = 'profile'>
					
					
				</table>
				
			</div>
			<div class='alignright'>
					<input type='submit' name='edit_dept' id='edit_dept' value='Edit'  >
					
				</div >
			
			<br></div>
			</form>
			";			
*/
	
$dept_edit_table = "<form method='post' action='dept_edit.php' name='submit_edit_dept' id='submit_edit_dept'>

		<div class='right forty bordered'>
			<div class='profile_header'>
				<p class='alignleft'>[ Edit department ]</p>
			</div>
			<table class = 'profile'>
					<tr >
                                        <tr >
					  <td class='noborder'><label>Department Name </label><br> </td>
					  <td class='noborder'>"
					  	. dropdown("edit_dept_drop", $dept_list)
					  ."</td>
					</tr>
					  <td class='small'><label>Department Name</label><br> </td>
					  <td class = 'noborder'>
                                            <input name='name' id='name'>
                                          </td>
					</tr>
                                        <tr >
					  <td class='small'><label>Code</label><br> </td>
					  <td class = 'noborder'>
                                            <input name='dept_code' id='dept_code'>
                                          </td>
					</tr>
					
				</table>
				
			<br>
			
			<div class='alignright'>
				<input type='submit' name='submit_edit_dept' id='submit_edit_dept' value='Edit Department'>
			</div>
                        </div>
			</form>
			";

$dept_edit_html2 = "<div id='dept_edit_html'>
		<div>
			<form method='post' action='dept_edit.php' name='dept_edit' id='dept_edit'>
                        <label class='required'>Edit Department</label>	
				<br>
				<br>
			
				<table class = 'profile'>
					<tr >
                                        <tr >
					  <td class='noborder'><label>Department Name </label><br> </td>
					  <td class='noborder'>"
					  	. dropdown("edit_dept_drop", $dept_list)
					  ."</td>
					</tr>
					  <td class='small'><label>Department Name</label><br> </td>
					  <td class = 'noborder'>
                                            <input name='name' id='name'>
                                          </td>
					</tr>
                                        <tr >
					  <td class='small'><label>Code</label><br> </td>
					  <td class = 'noborder'>
                                            <input name='dept_code' id='dept_code'>
                                          </td>
					</tr>
					
				</table>
				
			<br>
			</div>
			<div class='alignright'>
				<input type='submit' name='submit_edit_dept' id='submit_edit_dept' value='Edit Department'>
			</div>
			</form>
			</div>";
        
        
?> 


 


<script>
$(document).ready(function(){

$('input#edit_dept').colorbox({width:"40%", height:"35%", inline:true, href:"#dept_edit_html"});	

$("#edit_dept_drop").change(function() {	
			var selectedId = $(this).val();
			$('#name').val(deptArr[selectedId][0]);
			$('#dept_code').val(deptArr[selectedId][1]);
			

	});
        
 $("ul#admin").show();
 $("ul#directory").hide();

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
        echo $dept_edit_table;
        echo "<div style='display:none'>";
        echo $dept_edit_html;
        echo("</div>");
	
	


	
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
