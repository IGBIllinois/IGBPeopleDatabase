<?php #search people
//error_reporting(E_ALL);

$page_title = "IGB People Database Search"; 

include 'includes/header.inc.php';

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

$filters = array();

$filters["users.dept_id"] = array($dept_drop, "AND");
$filters["address.address2"] = array($igb_room, "AND");
$filters["phone.igb"] = array($igb_phone, "AND");


$current_user_id = $user->get_current_user_id();


	//$search_results = $user->adv_search($user_enabled, $search_value, $current_user_id, $filters, $theme_drop, $type_drop, $start_date, $end_date, $supervisor);

	//$table_html = result_table( "search_results", $search_results );

$other_filters = array();
$other_filters["phone"] = $igb_phone;
$other_filters["dept"] = $dept_drop;
$other_filters["room"] =  $igb_room;
        
        $userlist = user::search_2($db, $user_enabled, $search_value, $current_user_id, $other_filters, $theme_drop, $type_drop, $start_date, $end_date, $supervisor);
        
        $table_html .= html::write_user_table("search_results", $userlist);


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
echo("</form>");
echo $table_html; 
if($table_html != null) {

    
?>
<BR><BR>
<form class='form-inline' action='report.php' method='post'>

 <input  type='submit' 
                name='create_excel' value='Create Excel'>
  <select
                name='report_type' class='input-medium'>
                <option value='xls'>Excel 2003</option>
                <option value='xlsx'>Excel 2007</option>
                <option value='csv'>CSV</option>
        </select>

        <?php 

        echo("<input type='hidden' name='user_enabled' value='$user_enabled'>"); 
        echo("<input type='hidden' name='search_value' value='$search_value'>"); 
        echo("<input type='hidden' name='current_user_id' value='$current_user_id'>"); 
        echo("<input type='hidden' name='theme_drop' value='$theme_drop'>"); 
        echo("<input type='hidden' name='type_drop' value='$type_drop'>"); 
        echo("<input type='hidden' name='start_date' value='$start_date'>"); 
        echo("<input type='hidden' name='end_date' value='$end_date'>"); 
        echo("<input type='hidden' name='supervisor' value='$supervisor'>"); 
        echo("<input type='hidden' name='igb_room' value='$igb_room'>"); 
        echo("<input type='hidden' name='igb_phone' value='$igb_phone'>"); 
        echo("<input type='hidden' name='dept_drop' value='$dept_drop'>"); 
        ?>

</form>

<form class='form-inline' action='report.php' method='post'>

 <input  type='submit' style='width:150px'
                name='create_detailed_excel' value='Create Detailed Excel'>
  <select
                name='report_type' class='input-medium'>
                <option value='xls'>Excel 2003</option>
                <option value='xlsx'>Excel 2007</option>
                <option value='csv'>CSV</option>
        </select>
 <?php
        echo("<input type='hidden' name='user_enabled' value='$user_enabled'>"); 
        echo("<input type='hidden' name='search_value' value='$search_value'>"); 
        echo("<input type='hidden' name='current_user_id' value='$current_user_id'>"); 
        echo("<input type='hidden' name='theme_drop' value='$theme_drop'>"); 
        echo("<input type='hidden' name='type_drop' value='$type_drop'>"); 
        echo("<input type='hidden' name='start_date' value='$start_date'>"); 
        echo("<input type='hidden' name='end_date' value='$end_date'>"); 
        echo("<input type='hidden' name='supervisor' value='$supervisor'>"); 
        echo("<input type='hidden' name='igb_room' value='$igb_room'>"); 
        echo("<input type='hidden' name='igb_phone' value='$igb_phone'>"); 
        echo("<input type='hidden' name='dept_drop' value='$dept_drop'>"); 
?>
</form>


<?php 
}
include ("includes/footer.inc.php"); 

?> 