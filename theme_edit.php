
 
 <?php 

$page_title = "IGB People Database"; 

include 'includes/header.inc.php'; 
include 'includes/functions.inc.php'; 
include_once 'includes/main.inc.php';


if (!$_SESSION['admin']){
header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/login.php"); 	
exit(); 
}


$theme = new theme($db);
$theme_leader_list  = $db->query($select_theme_leaders);
$theme_list = $db->query($select_theme);
$all_themes = $theme->get_all_themes();
$error_msg = "";


echo "<script>";

echo "var themeArr = [];\n"; 
foreach($all_themes as $id=>$t)
{
        echo "themeArr[\"".$t['theme_id']."\"] = new Array(\"".$t['name']."\",\"".$t['short_name']."\",\"".
					   $t['leader_id']."\",\"".$t['theme_active']."\");\n";
}
echo "</script>"; 




/*
THEME INFO TABLE HTML



*/
$theme_table = "<div class='noborder'>
				".theme_list_table("theme_list_table",$all_themes)."
			</div>
			";
			
			

/*
Add theme
*/

if (isset($_POST['add_theme'])){
	$theme_name = $_POST['theme_name'];
	$theme_short_name = $_POST['theme_short_name'];
	$theme_leader_id = $_POST['theme_leader_id'];
	$theme_status = $_POST['theme_status'];
	$error_msg = "";
	$error_count=0;
	
	if (empty($theme_name)){  
		$error_msg .= "<label class='errormsg'>Please enter a Theme name<br></label>";
		$error_count++;
		
	}
	/*
	else if (!empty($)){    // check if theme already exists?
		$error_msg = "Key already exists";
		$error_count++;
	}
	*/
	
	if ($error_count == 0){
		$result = $theme->add_theme($theme_name, $theme_short_name, $theme_leader_id, $theme_status); 
		
		unset($_POST['add_theme']);
		$redirectpage= "/theme_edit.php";
		header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . $redirectpage); 	
		exit(); 
	}

}			
			
			
/*
THEME ADD FORM HTML
*/
$theme_add_table = "<form method='post' action='theme_edit.php' name='add_theme'>
			<label class='required'>Add New Theme</label>	
				<br>
			<div class='noborder'>
			 	<br>
				
				<table class = 'profile'>
					<tr >
					  <td class='small'><label>Theme Name </label><br> </td>
					  <td class='noborder'>
					  	<input type='large' name='theme_name' maxlength='50'  >
					  </td>
					</tr>
					<tr >
					  <td class='small'><label>Abbreviation </label><br> </td>
					  <td class='noborder'>
					  	<input type='large' name='theme_short_name' maxlength='8'  >
					  </td>
					</tr>
					<tr >
					  <td class='small'><label>Theme Leader </label><br> </td>
					  <td class='noborder'>
					  	".dropdown( 'theme_leader_id', $theme_leader_list)."
					  </td>
					</tr>
					<tr >
					  <td class='small'><label>Status</label><br> </td>
					  <td class='noborder'>
					  	".simple_drop( 'theme_status', $status_arr, '1')."
					  </td>
					</tr>
				</table>
				
			</div>
			<div class='alignright'>
					<input type='submit' name='add_theme' id='add_theme' value='Submit'  >
			</div >
			<br>
			</form>
			";
			
/*
remove theme
sets theme status to inactive
*/

if (isset($_POST['remove_theme'])){
	$theme_drop = $_POST['theme_drop'];
	if (!empty($theme_drop)){
		$result = $theme->update($theme_drop, 'themes', 'theme_active', '0'); 
	}
	unset($_POST['remove_theme']);
	$redirectpage= "/theme_edit.php";
	header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . $redirectpage); 	
	exit(); 

}			
			
			
/*
THEME REMOVE FORM HTML



*/
$theme_remove = "<form method='post' action='theme_edit.php' name='remove_theme' id='submit_remove'>
			<label class='required'>Remove Theme </label>	
				<br>
			<div class='noborder'>
			 	<br>
				<table class = 'profile'>
					<tr >
					  <td class='noborder'><label>Select Theme to Inactivate:</label><br> </td>
					</tr>
					<tr >
					  <td class='noborder'>
					  	".dropdown( 'theme_drop', $theme->get_theme_names())."
					  </td>
					  <td class='noborder'>
					  	<input type='submit' name='remove_theme' id='remove_theme' value='Remove'  >
					  </td>
					</tr>
				</table>
			</div>
			<br>
			</form>
			";
			
			
/*
select a theme to edit
*/

if (isset($_POST['select_edit'])){
	$theme_id = $_POST['edit_theme_drop'];
	$theme_name = $_POST['theme_name'];
	$theme_short_name = $_POST['theme_short_name'];
	$theme_leader_id = $_POST['theme_leader_id'];
	$theme_status = $_POST['theme_status'];
	
	if (!empty($theme_id)){
		$result = $theme->update($theme_id, 'themes', 'name', $theme_name);
		$result = $theme->update($theme_id, 'themes', 'short_name', $theme_short_name);
		$result = $theme->update($theme_id, 'themes', 'leader_id', $theme_leader_id);
		$result = $theme->update($theme_id, 'themes', 'theme_active', $theme_status);
	}
	unset($_POST['select_edit']);
	
	$redirectpage= "/theme_edit.php";
	header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . $redirectpage); 	
	exit();
	
}			
			
			
/*
THEME SELECT EDIT HTML



*/
$theme_edit = "	<form method='post' action='theme_edit.php' name='select_edit' id='select_edit'>
				<table class = 'profile'>
					<tr >
					  <td class='noborder'><label>Select Theme to Edit:</label><br> </td>
					  <td class='noborder'>
					  	".dropdown( 'edit_theme_drop', $all_themes)."
					  </td>
					</tr>
				</table>
			<div class='noborder'>
			 	<br>
				<table class = 'profile'>
					<tr >
					  <td class='small'><label>Theme Name </label><br> </td>
					  <td class='noborder'>
					  	<input type='large' name='theme_name' id='theme_name' maxlength='50'  >
					  </td>
					</tr>
					<tr >
					  <td class='small'><label>Abbreviation </label><br> </td>
					  <td class='noborder'>
					  	<input type='large' name='theme_short_name' id='theme_short_name' maxlength='8'  >
					  </td>
					</tr>
					<tr >
					  <td class='small'><label>Theme Leader </label><br> </td>
					  <td class='noborder'>
					  	".dropdown( 'theme_leader_id', $theme_leader_list)."
					  </td>
					</tr>
					<tr >
					  <td class='small'><label>Status</label><br> </td>
					  <td class='noborder'>
					  	".simple_drop( 'theme_status', $status_arr, '1')."
					  </td>
					</tr>
				</table>
			</div>
			<div class='alignright'>
					<input type='submit' name='select_edit' id='select_edit' value='Update'  >
			</div >
			</form>
			";

?> 
 


<script>
$(document).ready(function(){
 $("ul#admin").show();
 $("ul#directory").hide();
	var r;
	function confirm_remove()
	{
		r=confirm("Are you sure you want to remove this theme?");
	}
	
	$('a#add_theme').colorbox({width:"40%", height:"35%", inline:true, href:"#theme_add_table"});
	$('a#remove_theme').colorbox({width:"40%", height:"30%", inline:true, href:"#theme_remove"});
	$('a#edit_theme').colorbox({width:"45%", height:"35%", inline:true, href:"#theme_edit"});
				
	$('#submit_remove').submit(function(){ 
			confirm_remove();
			if(r==false){
				$.colorbox.close();
				return false;
			}	 
		});
	
	
	
	
	$("#edit_theme_drop").change(function() {	
			var selectedId = $(this).val();
			$('#theme_name').val(themeArr[selectedId][0]);
			$('#theme_short_name').val(themeArr[selectedId][1]);
			$('#theme_leader_id').val(themeArr[selectedId][2]);
			$('#theme_status').val(themeArr[selectedId][3]);

	});
	
	$('#theme_list_table').dataTable( {
		"bPaginate": false,
		"bLengthChange": false,
		"bFilter": false,
		"bSort": false,
		"bInfo": false,
		"bAutoWidth": false } );


});
</script>


<h1> theme management </h1>

<h3>
<a id='add_theme' href='#'>[ add new theme ]  </a>
<a id='edit_theme' href='#'>  [ edit theme ]  </a>
<a id='remove_theme' href='#'>  [ remove theme ]  </a>


</h3>
<?php 
	echo $error_msg;
	echo $theme_table;
?>


<div style='display:none'>
		<div id='theme_add_table'>
        <?php
			echo $theme_add_table;
		?>
		</div>
		<div id='theme_remove'>
        <?php
			echo $theme_remove;
		?>
		</div>
		<div id='theme_edit'>
        <?php
			echo $theme_edit;
		?>
        <div id="result"></div>
		</div>
		<div id='theme_edit_form'>
         
        <?php
			//echo $theme_edit_form;

		?>
		</div>
        

        
        
        
</div>
<br>
<?php 

include ("includes/footer.inc.php"); 

?> 
