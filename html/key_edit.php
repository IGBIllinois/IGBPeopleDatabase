<?php #search people

$page_title = "IGB People Database Search"; 

require_once 'includes/header.inc.php';


//$key_list = $db->query($select_key);
$error_msg = "";
$active_key = array();


/*
KEY INFO TABLE HTML
*/
$key_table = "
    <div class='left sixty'>
        <div class='noborder'>
        ".
            html::key_list_table($db)
        ."
        </div>
        <br></div>
";
					

/*
Add Key
*/
global $db;
if (isset($_POST['add_key_assign'])){
	$key_room = $_POST['key_room'];
	$key_name = $_POST['key_name'];
	$error_msg = "";
        $key_active = 1;
	
	$key_exists = NULL;
	
	if (empty($key_room)){  
		$error_msg .= "Please enter room #<br>";
		$error_count++;
		
	}
	if (empty($key_name)){  
		$error_msg .= "Please enter key name<br>";
		$error_count++;
		
	}
        
	if ($error_count == 0){

                $result = key::add_key($db, $key_room, $key_name, $key_active);

		unset($_POST['add_key_assign']);
		$redirectpage= "/key_edit.php";
		header ("Location: https://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . $redirectpage); 	
		exit(); 
	}

}			
			
			
/*
KEY ADD FORM HTML



*/
$key_add_table = "<form method='post' action='key_edit.php' name='add_key_assign'>
<br>
    <div class='right forty bordered'>
        <div class='profile_header'>
                <p class='alignleft'>[ Add key assignment ]</p>
        </div>
        <div class='noborder'>
            <label class='errormsg'>".$error_msg."</label><br>

            <table class = 'profile'>
                <tr >
                  <td class='noborder'><label>Key Name </label><br> </td>
                  <td class='noborder'>
                        <input type='small' name='key_name' maxlength='12'  >
                  </td>
                </tr>
                <tr >
                  <td class='noborder'><label>Room #</label><br> </td>
                  <td class='noborder'>
                        <input type='small' name='key_room' maxlength='12'  >
                  </td>
                </tr>
            </table>

        </div>
        <div class='alignright'>
            <input type='submit' name='add_key_assign' id='add_key_assign' value='Submit'  >

        </div >

        <br></div>
        </form>
        ";
			


/*
Change Key Status
*/

if (isset($_POST['key_change'])){
    
    $key_id = $_POST['key_id'];
    $key_status = $_POST['key_status'];

    $key = new key($db, $key_id);
    $key->edit_key($key->get_key_room(), $key_status);

    unset($_POST['key_drop']);
    $redirectpage= "/key_edit.php";
    header ("Location: https://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . $redirectpage); 	
    exit(); 

}			
			
/*
KEY DELETE FORM HTML
*/
$all_keys = key::get_keys($db);
$key_dropdown = array();
foreach($all_keys as $key) {
    $key_dropdown[] = array($key->get_key_id(), $key->get_key_name());
}

$key_delete_table = "

<form method='post' action='key_edit.php' name='key_change' id='key_change'>
<br>
    <div class='right forty bordered'>
        <div class='profile_header'>
                <p class='alignleft'>[ Change Key status ]</p>
        </div>
        <div class='noborder'>
            <label class='errormsg'></label><br>

            <table class = 'profile'>
                <tr >
                  <td class='noborder'><label>Select Key:  </label><br> </td>
                  <td class='noborder'>
                        ".html::dropdown('key_id', $key_dropdown )."
                  </td>
                </tr>
                <tr >
                  <td class='noborder'><label>Set Status to:  </label><br> </td>
                  <td class='noborder'>
                        ".html::simple_drop('key_status', $status_arr )."
                  </td>
                </tr>

            </table>

        </div>
        <div class='alignright'>
            <input type='submit' name='key_change' id='key_change' value='Update'  >

        </div >

        <br></div>
        </form>
        ";
		



/*
EDIT KEY HTML
*/
		
$edit_key_html = "";
for ($i = 0; $i < count($active_key); $i++) {

    $edit_key_html .= "<div id='edit_".$active_key[$i]["keyinfo_id"]."'>
    <div>
    <form method='post' action='key_edit.php' name='edit_key'>

    <label class='required'>Edit Key </label>	
    <br>
    <br>

    <table class = 'profile'>
        <tr >
          <td class='small'><label>Room # / Key Name </label><br> </td>
          <td class='noborder'>
                <input type='text' name='key_room' maxlength='12' value='".$active_key[$i]["room"]."' >
          </td>
        </tr>
        <tr >
          <td class='small'><label>Deposit</label><br> </td>
          <td class='noborder'>
                <input type='radio' name='payment_status' value='1' "; 
                if ($search_results[$i]["paid"] == '1') 
                        {$edit_key_html .= $checked;} 
                $edit_key_html .=">
                <label class='note'>Paid</label>
                
                <input type='radio' name='payment_status' value='0' "; 
                if ($search_results[$i]["paid"] == '0') 
                        {$edit_key_html .= $checked;} 
                $edit_key_html .="><label class='note'>Not Paid</label>
          </td>
        </tr>
        <tr >
          <td class='small'><label>Date Issued</label><br> </td>
          <td class='noborder'>
                <input type='date' name='date_issued' value='".$active_key[$i]["date_issued"]."' >					  
          </td>
        </tr>
    </table>
    <br>
    </div>
    <div class='alignright'>
            <input type='submit' name='edit_key_".$active_key[$i]["keyinfo_id"]."' 
                    id='edit_key_".$active_key[$i]["keyinfo_id"]."' value='Edit Key'>
    </div>
    </form>

    </div>

    ";


}
	

?> 
<script>
$(document).ready(function(){

 $("ul#admin").show();
 $("ul#directory").hide();

});
</script>

<h1> Key management </h1>

<h3></h3>
<label class='errormsg'></label>
<?php 
	echo $key_table;
	echo "<div class='right forty'>";
	echo $key_add_table;
	echo $key_delete_table;
	echo "</div>";
?>
<div class='clear'>
				
</div >

</div>
<br>
<?php 

require_once ("includes/footer.inc.php"); 

?> 
