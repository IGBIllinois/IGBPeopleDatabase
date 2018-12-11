<?php 

$page_title = "IGB People Database"; 

require_once 'includes/header.inc.php';

$type_list = type::get_all_types($db);
$error_msg = "";

/*
TYPE INFO TABLE HTML
*/

$type_table = "<div class='left sixty'>
    <div class='noborder'>
        ".
        html::type_list_table("type_list_table",$type_list)
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

/*
 *  Add type
 */
if (!empty($_POST['add_type']) && !empty($_POST['type_name'])){

    $type_name = $_POST['type_name'];
    $error_msg = "";
    echo("type name = $type_name<BR>");

    if (empty($type_name)){  
            $error_msg .= "Please enter type name<br>";
            $error_count++;

    }

    $tmp_type = new type($db);
    if($tmp_type->type_name_exists($type_name) !== false) {
        $error_msg .= "A type with the name $type_name already exists.<br>";
        $error_count++;

    }

    if ($error_count == 0){

        type::add_type($db, $type_name, 1);

        unset($_POST['add_type']);
        $redirectpage= "/type_edit.php?success=true&type_name=".  htmlentities($type_name);
        header ("Location: https://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . $redirectpage); 	
        exit(); 
    }

}

/*
CHANGE TYPE STATUS
*/

if (isset($_POST['edit_type'])){
	$type_id = $_POST['type_id'];
	$type_status = $_POST['type_status'];
	echo("type_status = $type_status");
        $type = new type($db, $type_id);
        $type->edit_type($type->get_name(), $type_status);

        unset($_POST['edit_type']);
        $redirectpage= "/type_edit.php";
        header ("Location: https://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . $redirectpage); 	
        exit(); 
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
TYPE STATUS FORM HTML
*/

$dropdown_list = array();
foreach($type_list as $type) {
    $dropdown_list[] = array($type->get_id(), $type->get_name());
}
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
                        ".html::dropdown('type_id', $dropdown_list )."
                  </td>
                </tr>
                <tr >
                  <td class='xs'><label>Set to:  </label><br> </td>
                  <td class='xs'>
                        ".html::simple_drop('type_status', $status_arr )."
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


<h1> Type management </h1>

<h3>


</h3>
<?php 
	echo $type_table;
	
	echo $type_add_table;
	echo $type_edit_table;

?>

<br>
<?php 

require_once("includes/footer.inc.php"); 

?> 
