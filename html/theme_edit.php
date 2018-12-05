
 
 <?php 

$page_title = "IGB People Database"; 

require_once 'includes/header.inc.php'; 


$theme = new theme($db);
$theme_leader_list  = $db->query($select_theme_leaders);
$theme_list = $db->query($select_theme);
$all_themes = $theme->get_all_themes();
$error_msg = "";

$theme_array = "<script>";

$theme_array .= "var themeArr = [];\n"; 
foreach($all_themes as $theme)
{
    $theme_array .= "themeArr[\"".$theme->get_theme_id()."\"] = new Array(\"".$theme->get_name()."\",\"".$theme->get_short_name()."\",\"".
            $theme->get_leader_id()."\",\"".$theme->get_status()."\");\n";
}
$theme_array .= "</script>"; 



/*
THEME INFO TABLE HTML
*/
$theme_table = "<div class='noborder'>
    ".html::theme_list_table("theme_list_table",$all_themes)."
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

    if ($error_count == 0){
            $result = $theme->add_theme($theme_name, $theme_short_name, $theme_leader_id, $theme_status); 

            unset($_POST['add_theme']);
            $redirectpage= "/theme_edit.php?add_theme_result=".$result;
            header ("Location: https://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . $redirectpage); 	
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
                            ".html::dropdown( 'theme_leader_id', $theme_leader_list)."
                      </td>
                    </tr>
                    <tr >
                      <td class='small'><label>Status</label><br> </td>
                      <td class='noborder'>
                            ".html::simple_drop( 'theme_status', $status_arr, '1')."
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
	header ("Location: https://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . $redirectpage); 	
	exit(); 

}			
			
			
/*
THEME REMOVE FORM HTML



*/
$theme_remove = "<form method='post' action='theme_edit.php' name='remove_theme' id='submit_remove_theme'>
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
                            ".html::dropdown( 'theme_drop', $theme->get_theme_names())."
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

        $result = $theme->update_theme(
                $theme_id, 
                $theme_name, 
                $theme_short_name, 
                $theme_leader_id, 
                $theme_status);
    }
    unset($_POST['select_edit']);

    $redirectpage= "/theme_edit.php?edit_theme_result=".$result;
    header ("Location: https://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . $redirectpage); 	
    exit();
	
}			
			
			
/*
THEME SELECT EDIT HTML
*/

$theme_data = array();
foreach($all_themes as $theme) {
    $theme_data[$theme->get_theme_id()] = array("theme_id"=>$theme->get_theme_id(), "theme_name"=>$theme->get_name());
}
$theme_edit = "	<form method='post' action='theme_edit.php' name='select_edit' id='select_edit'>
            <table class = 'profile'>
                    <tr >
                      <td class='noborder'><label>Select Theme to Edit:</label><br> </td>
                      <td class='noborder'>
                            ".html::dropdown( 'edit_theme_drop', $theme_data)."
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
                            ".html::dropdown( 'theme_leader_id', $theme_leader_list)."
                      </td>
                    </tr>
                    <tr >
                      <td class='small'><label>Status</label><br> </td>
                      <td class='noborder'>
                            ".html::simple_drop( 'theme_status', $status_arr, '1')."
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


<h1> theme management </h1>

<?php
if(isset($_GET['add_theme_result'])) {
    if($_GET['add_theme_result'] != 0) {
        $error_msg .= (html::success_message("Theme added successfully.<BR>"));
    }
}

if(isset($_GET['edit_theme_result'])) {
    if($_GET['edit_theme_result'] != 0) {
        $error_msg .= (html::success_message("Theme edited successfully.<BR>"));
    }
}
?>

<h3>
<a id='add_theme' href='#'>[ add new theme ]  </a>
<a id='edit_theme' href='#'>  [ edit theme ]  </a>
<a id='remove_theme' href='#'>  [ remove theme ]  </a>


</h3>
<?php 
    echo $theme_array;
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

		?>
		</div>
 
</div>
<br>
<?php 

require_once("includes/footer.inc.php"); 

?> 
