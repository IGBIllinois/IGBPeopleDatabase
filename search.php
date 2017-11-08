<?php #search people
//error_reporting(E_ALL);
$page_title = "IGB People Database Search"; 

include 'includes/header.inc.php'; 
include 'includes/functions.inc.php'; 
include_once 'includes/main.inc.php';
include "libs/ExcelWriter.php";
include 'includes/Writer.php';

//include 'includes/user.class.inc.php';

if (!$_SESSION['admin']){
header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/login.php"); 	
exit(); 
}
?>

<script>
$(document).ready(function(){

  $("a#adv_search_text").click(function(){
  $("div#adv_search").toggle();
  });
  

	$('#search_results').dataTable( {
		"bPaginate": true,
		"sPaginationType": "full_numbers",
		"bLengthChange": false,
		"bFilter": false,
		"bSort": false,
		"bInfo": false,
		"bAutoWidth": true } );
  

});

$(window).bind('beforeunload',function(){
    if(1){ //whatever check you wish to do
        //return 'Are you sure you want to leave?';
    }
});
$(window).unload(function(){
    $.ajax({
        type: 'POST',
        url: 'search.php',
        data: 'value=unload',
        async: false // this will not work if set to 'true'
    });
    alert('done');
});

</script>

<?php
//if(isset($_POST['value'])){

    if($_POST['value']=='unload'){
        try {
        //foreach(glob($targetFolder.'*.*') as $file){
            unlink("excel/igb_people.xls"); // remove every file
        //}
        //rmdir($targetFolder); // remove directory
        } catch(Exception $e) {
//            echo($e);
//            echo($e->getMessage());
        }
//    }
}

//variables
$dept_list = $db->query($select_dept);
$theme_list = $db->query($select_theme);
$type_list = $db->query($select_type);
$user_enabled = '1';
$selected = "selected";


$page = 1;

$status_drop = "<select name='user_enabled' id='user_enabled'>
				<option value='0' ";
				if ($user_enabled == '0'){ $status_drop .= $selected; }
				$status_drop .= ">Inactive</option>
				<option value='1' ";
				if ($user_enabled == '1'){ $status_drop .= $selected; }
				$status_drop .= ">Active</option>
				<option value='2' ";
				if($user_enabled == '2') { $status_drop .= $selected; }
				$status_drop .= ">All</option>
				</select>";
/*
if (isset($_GET['page'])){
	$page = $_GET['page']; 
}
*/

echo "<body onLoad=\"document.search.search_value.focus()\">"; 


if (isset($_POST['search']) or isset($_POST['excel']) or isset($_POST['det_excel'])){//or isset($_GET['page'])
	
	$user_id = "";		
	$igb_room = $_POST['igb_room']; 
	$igb_phone = $_POST['igb_phone']; 	
	$theme_drop = $_POST['theme_drop'];
	$type_drop = $_POST['type_drop'];
	$dept_drop = $_POST['dept_drop'];
	$supervisor = $_POST['supervisor'];
	$user_enabled = $_POST['user_enabled'];
	$search_value = $_POST['search_value'];
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	

	
$error="";
$error_count=0;
$aster= array(1 => " * ");
$checked = "checked";
$search_field="any";

$table_html = "";

$filters = NULL;
	
$user = new user($db);
//$supervisor_id = $user->user_exists("netid",$supervisor);
	
$filters = array();
//$filters["users.theme_id"] = array($theme_drop, "AND");
//$filters["users.theme_1_id"] = array($theme_drop, "OR");
//$filters["users.theme_2_id"] = array($theme_drop, "OR");
//$filters["users.type_id"] = array($type_drop, "AND");
//$filters["users.type_1_id"] = array($type_drop, "OR");
//$filters["users.type_2_id"] = array($type_drop, "OR");
$filters["users.dept_id"] = array($dept_drop, "AND");
$filters["address.address2"] = array($igb_room, "AND");
$filters["phone.igb"] = array($igb_phone, "AND");
//$filters["users.supervisor_id"] = array($supervisor_id, "OR");


	
/*	
		$filters = array("users.theme_id"=>$theme_drop,
					 "users.type_id"=>$type_drop,
					 "users.dept_id"=>$dept_drop,
					 "address.address2"=>$igb_room,
					 "phone.igb"=>$igb_phone,
					 "users.supervisor_id"=>$supervisor_id);



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
		*/
//echo("username = ". $_SESSION['username']);

$current_user_id = $user->get_current_user_id();

	$search_results = $user->adv_search($user_enabled, $search_value, $current_user_id, $filters, $theme_drop, $type_drop, $start_date, $end_date, $supervisor);//$page, 
if(isset($_POST['excel'] )) {
	$filename = "igb_people.xls";
        try {
	writeExcel($search_results, $db, $filename);
        } catch(Exception $e){
            echo($e->getTraceAsString());
        }
	while(!file_exists($filename)) {}
	
	//unlink($filename);
}

if(isset($_POST['det_excel'] )) {
	$filename = "igb_people.xls";
        try {
	writeDetailedExcel($search_results, $db, $filename);
        } catch(Exception $e){
            echo($e->getTraceAsString());
        }
	while(!file_exists($filename)) {}
	
	//unlink($filename);
}

	$table_html = result_table( "search_results", $search_results );


}



?> 



<h1> Search IGB Database</h1>
<br>
<h3><a id="simple_search" href="search.php">[ simple search ]</a></h3>




<form method="post" action="search.php" name="search">



<div class="section">

    	<input type="address" name="search_value" maxlength="50"
    	value="<?php if (isset($search_value)){echo "$search_value";}else{echo "";} ?>" >

    <input type="submit" name="search" value="Search" class="btn"> 
	<input type="reset" name="clear" value="Clear" class="btn">

<br>

<br>





<h3>
<a id="adv_search_text">[ advanced search options ]</a>
</h3>

<div id="adv_search" style="display: none">

<table class="medium">
  <tr>
        <td class="noborder"><label class="optional">IGB Room # </label>
        </td>
        <td class="noborder" colspan='3'><label class="optional">Department </label>
        </td>
  </tr>
  <tr>
        <td  class="noborder"><input type="small" name="igb_room" class="space" maxlength="12"  
            value="<?php if (isset($igb_room)){echo "$igb_room";}else{echo "";} ?>" >
        </td>
        <td class="noborder" colspan='3'><?php echo dropdown( "dept_drop", $dept_list , $dept_drop );  ?>
        </td>
  </tr>
  <tr>
    <td class="noborder"><label class="optional">IGB Phone</label>
    </td>
    	<td class="noborder"><label class="optional">Themes </label>
      	</td> 
    	<td class="noborder"><label class="optional">Type </label>
      	</td> 
    	<td class="noborder"><label class="optional">Supervisor </label>
      	</td> 
  </tr>
  <tr>
        <td  class="noborder">
            <input type="small" name="igb_phone" class="PHONE" maxlength="12"  
            value="<?php if (isset($igb_phone)){echo "$igb_phone";}else{echo "";} ?>" >
        </td>
    	<td class="noborder">
	  		<?php echo dropdown( "theme_drop", $theme_list , $theme_drop  ); ?> 
      	</td>            
      	<td class="noborder">
      		<?php echo dropdown( "type_drop", $type_list , $type_drop  );?>
      	</td>
        <td class="noborder"><input type="small" name="supervisor" class="space" maxlength="8" 
        value="<?php if (isset($supervisor)){echo "$supervisor";}else{echo "";} ?>" >
        </td>
  </tr>
  <tr>
    	
    	<td class="noborder"><label class="optional">Status </label>
      	</td> 
        <td class="noborder"><label class="optional">Start Date: Begin (YYYY-MM-DD)</label>
      	</td> 
        <td class="noborder"><label class="optional">Start Date: End (YYYY-MM-DD)</label>
      	</td> 
          </tr>
  <tr>         
      	<td class="noborder">
      		<?php echo $status_drop;?>
      	</td>
        <td class='noborder'><input type='date' name='start_date'   
					value="<?php if (isset($start_date)){echo "$start_date";}else{echo "";} ?>" >     
		</td>
        <td class='noborder'><input type='date' name='end_date'   
					value="<?php if (isset($end_date)){echo "$end_date";}else{echo "";} ?>" >     
		</td>  
              
              
        <td class="noborder">
        </td>  
  </tr>
  <tr>
        <td class="noborder">
        <BR />
        	<input type="submit" name="search" value="Search" class="btn"> 
        </td>
  </tr>
   
</table>







</div>
<br />
</div>
<br />
<div>

<?php  //echo $page_list; ?>

</div>

<?php  

echo $table_html; 
if($table_html != null) {
echo"<BR><input type='submit' name='excel' value='Create Excel' class='btn' />";
echo"<BR><input type='submit' style='width:150px' name='det_excel' value='Create Detailed Excel' class='btn' />";
}

?> 

</form>
 
<?php 

include ("includes/footer.inc.php"); 

?> 

<?php

/* Writes and opens an Excel file
*
* @param search_results: An array from an sql query
*
*/

function writeExcel($search_results, $db, $filename="igb_people.xls") {
	//$filename = "./igb_people.xls";
    try {
	$excel= new ExcelWriter("excel/".$filename);
	$myArr=array("<b>Name</b>","<b>Email</b>","<b>Theme</b>","<b>Type</b>","<b>Room Number</b>","<b>Address</b>");
	$excel->writeLine($myArr);
	for ($i = 0; $i < count($search_results); $i++) {
		//$thisuser = new user($dbase, $search_results[$i]['user_id']);
		$line = array($search_results[$i]['first_name'] . " " . $search_results[$i]['last_name'],
					  $search_results[$i]['email'],
					  $search_results[$i]['theme_name'],
					  $search_results[$i]['type_name'],
					  $search_results[$i]['igb_room'],
					  get_address($db, $search_results[$i]['user_id'], "HOME")

					  );
                //echo($line . "<BR>");
		$excel->writeLine($line);
	}
	$excel->close();
	header("Location: excel/". $filename);
    } catch(Exception $e) {
        echo($e);
        echo($e->getTrace());
    }
	
}

// Updated excel test
function writeExcel2($search_results, $db, $filename="igb_people.xls") {
	//$filename = "./igb_people.xls";
    try {
	//$excel= new ExcelWriter("excel/".$filename);
        $workbook = new Spreadsheet_Excel_Writer("excel/".$filename);

        $format_bold =& $workbook->addFormat();
        $format_bold->setBold();

        $worksheet =& $workbook->addWorksheet('IGB People Database');

	$myArr=array("Name","Email","Theme","Type","Room Number","Address");
        
	//$excel->writeLine($myArr);
        $worksheet->writeRow(0,0,$myArr, $format_bold);
	for ($i = 0; $i < count($search_results); $i++) {
		//$thisuser = new user($dbase, $search_results[$i]['user_id']);
		$line = array($search_results[$i]['first_name'] . " " . $search_results[$i]['last_name'],
					  $search_results[$i]['email'],
					  $search_results[$i]['theme_name'],
					  $search_results[$i]['type_name'],
					  $search_results[$i]['igb_room'],
					  get_address($db, $search_results[$i]['user_id'], "HOME")

					  );
		//$excel->writeLine($line);
                $worksheet->writeRow(($i+1), 0, $line);
	}
	$workbook->close();
	header("Location: excel/". $filename);
    } catch(Exception $e) {
        echo($e);
        echo($e->getTrace());
    }
	
}


/* Writes and opens an Excel file
*
* @param search_results: An array from an sql query
*
*/

function writeDetailedExcel($search_results, $db, $filename="igb_people_detail.xls") {
    //echo("DETAILED EXCEL");
	//$filename = "./igb_people.xls";
    /*Last name
First name
Theme
Status
Room number
Phone number
Email
UIN
Supervisor
Home department
Home college
     * 
     * SELECT users.user_id as user_id, first_name, last_name, netid, uin, email, default_address, 
						 themes.short_name as theme_name, type.name as type_name, address.address2 as igb_room
						 
						 FROM (((users LEFT JOIN themes ON users.theme_id=themes.theme_id)
						 LEFT JOIN type ON users.type_id=type.type_id) 
						 LEFT JOIN address ON users.user_id=address.user_id AND address.type='IGB') 
						 LEFT JOIN phone ON users.user_id=phone.user_id 
     * 
     */
    try {
	$excel= new ExcelWriter("excel/".$filename);
	$myArr=array("<b>Last Name</b>","<b>First Name</b>","<b>Theme</b>","<b>Status</b>","<b>Room Number</b>","<b>Phone Number</b>",
            "<b>Email</b>","<b>UIN</b>","<b>Supervisor</b>","<b>Home Department</b>","<b>Home College</b>");
	$excel->writeLine($myArr);
	for ($i = 0; $i < count($search_results); $i++) {
            $this_user = new user($db, $search_results[$i]['user_id']);
            $this_user->get_user($search_results[$i]['user_id']);
		//$thisuser = new user($dbase, $search_results[$i]['user_id']);
		$line = array( $search_results[$i]['last_name'],
                                $search_results[$i]['first_name'],
                                $search_results[$i]['theme_name'],
                                $search_results[$i]['status'],
                                $search_results[$i]['igb_room'],
                                $search_results[$i]['phone'],
				$search_results[$i]['email'],
                                $this_user->get_uin(),
                                $this_user->get_supervisor_name(),
                                $this_user->get_dept()
                                //$this_user->get_college();
                                
					  

					  );
		$excel->writeLine($line);
	}
	$excel->close();
	header("Location: excel/". $filename);
    } catch(Exception $e) {
        echo($e);
        echo($e->getTrace());
    }
	
}

function writeDetailedExcel2($search_results, $db, $filename="igb_people_detail.xls") {
    //echo("DETAILED EXCEL");
	//$filename = "./igb_people.xls";
    /*Last name
First name
Theme
Status
Room number
Phone number
Email
UIN
Supervisor
Home department
Home college
     * 
     * SELECT users.user_id as user_id, first_name, last_name, netid, uin, email, default_address, 
						 themes.short_name as theme_name, type.name as type_name, address.address2 as igb_room
						 
						 FROM (((users LEFT JOIN themes ON users.theme_id=themes.theme_id)
						 LEFT JOIN type ON users.type_id=type.type_id) 
						 LEFT JOIN address ON users.user_id=address.user_id AND address.type='IGB') 
						 LEFT JOIN phone ON users.user_id=phone.user_id 
     * 
     */
    try {
        
        $workbook = new Spreadsheet_Excel_Writer("excel/".$filename);

        $format_bold =& $workbook->addFormat();
        $format_bold->setBold();

        $worksheet =& $workbook->addWorksheet('IGB People Database');

	$myArr=array("Last Name","First Name","Theme","Status","Room Number","Phone Number",
            "Email","UIN","Supervisor","Home Department","Home College");
        
	//$excel->writeLine($myArr);
        $worksheet->writeRow(0,0,$myArr, $format_bold);

	for ($i = 0; $i < count($search_results); $i++) {
            $this_user = new user($db, $search_results[$i]['user_id']);
            $this_user->get_user($search_results[$i]['user_id']);
		//$thisuser = new user($dbase, $search_results[$i]['user_id']);
		$line = array( $search_results[$i]['last_name'],
                                $search_results[$i]['first_name'],
                                $search_results[$i]['theme_name'],
                                $search_results[$i]['status'],
                                $search_results[$i]['igb_room'],
                                $search_results[$i]['phone'],
				$search_results[$i]['email'],
                                $this_user->get_uin(),
                                $this_user->get_supervisor_name(),
                                $this_user->get_dept()
                                //$this_user->get_college();
                                
					  

					  );
		//$excel->writeLine($line);
                $worksheet->writeRow(($i+1), 0, $line);
	}
	$workbook->close();
	header("Location: excel/". $filename);
        //unlink("excel/".$filename);
            
        
    } catch(Exception $e) {
        echo($e);
        echo($e->getTrace());
    }
	
}

function get_address($db, $user_id, $type="HOME") {

	$query = "SELECT address1, address2, city, state, zip from address where type = '" . $type . "' AND user_id = '" . $user_id ."'";
	$result = $db->query($query);
	$addr = $result[0]['address1'] . " " . ($result[0]['address2'] != "" ? ($result[0]['address2'] . " ") : "") . "\r\n" . $result[0]['city'] .
		", " . $result[0]['state'] . " " . $result[0]['zip'];
	//echo $addr;
	return $addr;
}
	
?>