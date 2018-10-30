<?php 

$page_title = "IGB People Database Search"; 

require_once 'includes/header.inc.php'; 

if (!$_SESSION['admin']){
header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/login.php"); 	
exit(); 
}

?>




<?php


$curr_letter = $_GET['letter'];

//variables
$user_enabled = 1;




$table_html = "";

$filters = NULL;
	
$user = new user($db);
$user_tmp = new user($db);
$user_id = $user->user_exists("netid", $_SESSION['username']);


$html = "";
//$time_start = microtime_float();
	for($i=0; $i<26; $i++){
		
		$letter = $alphabet[$i];

                    
                    $html .= "<a href='alphadir.php?letter=".$letter."' class='alphalink' >".$letter."</a> ";
                    
		


	}


if($curr_letter != null && $curr_letter != "") {
    $search_results = $user->alpha_search($curr_letter, $user_id);
    $letter_html .= "<div class='dir' id='".$curr_letter."'>". result_table( $curr_letter, $search_results, "alphatable" ) . "</div>";
}

?> 



<h1> IGB Alpha Directory </h1>
<br>
<h3></h3>

<div class="section">


	<?php 
	
	echo $html;
    ?> 
</div>




	<?php 
    echo $letter_html; 
	?> 



 
<?php 

require_once ("includes/footer.inc.php"); 

?> 
