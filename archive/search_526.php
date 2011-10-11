<?php #search people

$page_title = "IGB People Database Search"; 

include 'includes/header.inc.php'; 
include 'includes/functions.inc.php'; 
include_once 'includes/main.inc.php';

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
  


});
</script>


<?php


//variables
$dept_list = $db->query($select_dept);
$theme_list = $db->query($select_theme);
$type_list = $db->query($select_type);
$user_enabled = 1;

$toggle1 = "[ advanced search options >> ]";
$toggle2 = "[ advanced search options << ]";
$page = 1;

if (isset($_GET['page'])){
	$page = $_GET['page']; 
}


echo "<body onLoad=\"document.search.search_value.focus()\">"; 


if (isset($_POST['search']) or isset($_GET['page'])){
	
$user_id = "";	

$igb_room = $_POST['igb_room']; 
$igb_phone = $_POST['igb_phone']; 

$theme_drop = $_POST['theme_drop'];
$type_drop = $_POST['type_drop'];
$dept_drop = $_POST['dept_drop'];
$supervisor = $_POST['supervisor'];


$search_value = $_POST['search_value'];

$gender = $_POST['gender']; 

$error="";
$error_count=0;
$aster= array(1 => " * ");
$checked = "checked";
$search_field="any";

$table_html = "";

$filters = NULL;
	
$user = new user($db);
$supervisor_id = $user->user_exists("netid",$supervisor);
	
		$filters = array("users.theme_id"=>$theme_drop,
					 "users.other_theme_id"=>$theme_drop,
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

	//if (!empty($search_value)){
		
		$search_results = $user->adv_search($user_enabled, $page, $search_value, $filters);
	//}

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
<a id="adv_search_text">[ advanced search options >> ]</a>
</h3>

<div id="adv_search" style="display: none">






<table class="medium">
  <tr>
    <td class="noborder"><label class="optional">IGB Room # </label>
    </td>
    <td class="noborder"><label class="optional">IGB Phone</label>
    </td>
  </tr>
  <tr>
    <td  class="noborder"><input type="small" name="igb_room" class="space" maxlength="12"  
    	value="<?php /*if (isset($igb_room)){echo "$igb_room";}else{echo "";}*/ ?>" >
    </td>
    <td  class="noborder">
        <input type="small" name="igb_phone" class="space" maxlength="12"  
        value="<?php /*if (isset($igb_phone)){echo "$igb_phone";}else{echo "";}*/ ?>" >
    </td>
  </tr>
</table>

<table class="medium">
  <tr>
    <td class="noborder"><label class="optional">Department </label></td>
  </tr>
  <tr>
    <td class="noborder"><?php echo dropdown( "dept_drop", $dept_list /*, $dept_drop*/ );  ?></td>
  </tr>
 
</table>

<table class="small">
	<tr>
    	<td class="noborder"><label class="optional">Themes </label>
      	</td> 
    	<td class="noborder"><label class="optional">Type </label>
      	</td> 
    	<td class="noborder"><label class="optional">Supervisor </label>
      	</td> 
    </tr>
    <tr>
    	<td class="noborder">
	  		<?php echo dropdown( "theme_drop", $theme_list /*, $theme_drop*/ ); ?> 
      	</td>            
      	<td class="noborder">
      		<?php echo dropdown( "type_drop", $type_list /*, $type_drop*/ );?>
      	</td>
        <td class="noborder"><input type="small" name="supervisor" class="space" maxlength="8" >
        </td>
    </tr>


</table>


</div>
<br />
</div>


<?php 	

?>


<div>

<?php  echo $page_list; ?>

</div>

<?php  




echo $table_html; ?> 

	</form>
 
<?php 

include ("includes/footer.inc.php"); 

?> 
