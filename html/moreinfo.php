<?php #search people

$page_title = "IGB People Database Search"; 

require_once 'includes/header.inc.php'; 

if(isset($_GET['user_id'])){$user_id = $_GET['user_id'];}


			
$user = new user($db, $user_id);

$active_key = $user->get_keys('1');
$inactive_key = $user->get_keys('0');

$checked = "checked";

//booleans
$payment_status = '1';
$key_deposit = $user->get_key_deposit();
$prox_card = $user->get_prox_card();
$bool = array(0=>"No", 1=>"Yes");
$checked_bool = array(0=>"", 1=>"checked");

$key_list = $db->query($select_active_key);

/*
KEY INFO TABLE HTML
*/

$key_info = "<div class='left forty'>
    <div class='profile_header'>
        <p class='alignleft'>[ key info ]</p>
        </div>
        <div class='noborder'>
            ".
            html::active_key_table("active_key_data",$active_key)
            ."

        </div>

        <br></div>
    ";
		

/*
KEY HISTORY INFO TABLE HTML
*/
$key_history_info = "<div class='right sixty'>
    <div class='profile_header'>
    <p class='alignleft'>[ key history ]</p>
    </div>
    <div class='noborder'>
        ".
        html::inactive_key_table("inactive_key_data",$inactive_key)
        ."

    </div>

    <br></div>
    ";
/*
KEY BUTTONS
*/
$key_button = "<div class='alignright'>
            <input type='button' name='add_key' id='add_key' value='Add Key'  >
            <input type='button' name='return_key' id='return_key' value='Return Keys'  >

    </div >
";			
			
		

/*
THEME HISTORY INFO TABLE HTML
*/
$theme_info = "<div class='noborder'>
    <div class='profile_header' id='test'>
                    <p class='alignleft'>[ theme history ]</p>
    </div>
        <div class='noborder'>
                <br>
        </div>
    </div>
    <br>
    ";


/*
Add Key
*/

if (isset($_POST['submit_add_key'])){
	$key_drop = $_POST['key_drop'];
	$payment_status = $_POST['payment_status'];
	$date_issued = $_POST['date_issued'];
	$error_count = 0;
	$error_msg = "";
	$key_exists = $user->key_exists($key_drop);
	
	if (empty($key_drop)){  
		$error_msg = "Please select a Room #";
		$error_count++;
		
	}
	
	else if ($key_exists !== false){  
		$error_msg = "Key is already assigned to this member";
		$error_count++;
	}
	
	
	if ($error_count == 0){
		$result = $user->add_key($user_id, $key_drop, $date_issued, $payment_status);
	
		unset($_POST['submit_add_key']);
		$redirectpage= "/moreinfo.php?user_id=".$user_id;
		header ("Location: https://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . $redirectpage); 	
		exit(); 
	}

}


/*
ADD KEY HTML
*/
		
$add_key_html = " <div id='add_key_html'>
    <div>
        <form method='post' action='moreinfo.php?user_id=".$user_id."' name='submit_add_key'>

        <label class='required'>Add New Key for ".$user->get_name()."</label>	
        <br>
        <br>

        <table class = 'profile'>
                <tr >
                  <td class='small'><label>Room # / Key Name </label><br> </td>
                  <td class='noborder'>"
                        .html::dropdown( "key_drop", $key_list, $key_drop ) ."

                        </td>
                </tr>
                <tr >
                  <td class='small'><label>Key Deposit</label><br> </td>
                  <td class='noborder'>
                        <input type='radio' name='payment_status' value='1' checked><label class='note'>Paid</label>
                        <input type='radio' name='payment_status' value='0'><label class='note'>Unpaid</label>
                  </td>
                </tr>
                <tr >
                  <td class='small'><label>Date Issued</label><br> </td>
                  <td class='noborder'>
                        <input type='date' name='date_issued' value='".date('Y-m-d')."' >					  
                  </td>
                </tr>
        </table>

        <br>
        </div>
        <div class='alignright'>
                <input type='submit' name='submit_add_key' id='submit_add_key' value='Add Key'>
        </div>
        </form>
        </div>
        ";	

/*
return Key
*/

if (isset($_POST['submit_return_key'])){
    
    $keyinfo_id = $_POST['key_room'];
    $payment_status = $_POST['payment_status'];
    $date_returned = $_POST['date_returned'];	
    $key_condition = $_POST['key_condition'];

    if (!empty($keyinfo_id)){  


        $key_info = new key_info($db, $keyinfo_id);

        $result = $key_info->return_key($payment_status,
                    $date_returned,
                    $key_condition
        );
    }

    unset($_POST['submit_return_key']);
    $redirectpage= "/moreinfo.php?user_id=".$user_id;
    header ("Location: https://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . $redirectpage); 	
    exit(); 
}

/*
RETURN KEY HTML
*/

$active_key_data = array();
foreach($active_key as $key_data) {
    $key = $key_data->get_key();
    $active_key_data[] = array($user->key_exists($key->get_key_id()), $key->get_key_room());
}

$return_key_html = " <div id='return_key_html'>
    <div>
    <form method='post' action='moreinfo.php?user_id=".$user_id."' name='submit_return_key'>

        <label class='required'>Return Key for ".$user->get_name()."</label>	
        <br>
        <br>

        <table class = 'profile'>
            <tr >
              <td class='small'><label>Key to be Returned </label><br> </td>
              <td class='noborder'>".
                    html::dropdown( "key_room", $active_key_data, null)."</td>
            </tr>
            <tr >
              <td class='small'><label>Deposit</label><br> </td>
              <td class='noborder'>
                    <input type='radio' name='payment_status' value='1' checked><label class='note'>Refunded</label>
                    <input type='radio' name='payment_status' value='0'><label class='note'>Not Refunded</label>
              </td>
            </tr>
            <tr >
              <td class='small'><label>Date Returned</label><br> </td>
              <td class='noborder'>
                    <input type='date' name='date_returned' value='".date('Y-m-d')."' >					  
              </td>
            </tr>
            <tr >
              <td class='small'><label>Condition of Key</label><br> </td>
              <td class='noborder'>".
                    html::drop( "key_condition", $key_condition_arr)."</td>
            </tr>
        </table>

    <br>
    </div>
    <div class='alignright'>
            <input type='submit' name='submit_return_key' id='submit_return_key' value='Return Key'>
    </div>
    </form>
			
        </div>
        ";	
		
?> 

	
<?php 

	$profile_link = "<a href='profile.php?user_id=".$user_id."'>".$user->get_name()."</a>"; 
?>


<h1> <?php 
	echo $profile_link ; 
?> </h1>

<h3>[ additional info ]</h3>
<label class='errormsg'><?php echo $error_msg; ?></label>
<br>
<?php 

	echo($key_info);	
	echo $key_history_info;	
	echo $key_button;	
	echo $theme_info; 

?>

<div style='display:none'>
		
<?php

    echo $add_key_html;
    echo $return_key_html;

?>

</div>
<br>
<?php 

require_once ("includes/footer.inc.php"); 

?> 
