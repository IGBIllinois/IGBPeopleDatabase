<?php #alpha

$page_title = "IGB People Database Search"; 

include 'includes/header.inc.php'; 


if (!$_SESSION['admin']){
header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/login.php"); 	
exit(); 
}

?>


<?php

$supervisor_id = $_GET['supervisor_id'];


$table_html = "";


	
$user = new user($db);
$user_tmp = new user($db);
$user_id = $user->user_exists("netid", $_SESSION['username']);

$user->get_user($user_id);

$html = "";
$html .= "<table><tr><td>".html::displayUser($user, $supervisor_id)."</td></tr></table>";
$html .= "<BR>Supervisor for:<BR>";


$theme_html .= html::displayUsersBySupervisor($db, $user, $supervisor_id);

?> 



<h1> IGB Theme Directory </h1>
<br>
<h3></h3>

<div class="section">


	<?php 
	
	echo $html;
    ?> 
</div>




	<?php 
    echo $theme_html; 
	?> 



 
<?php 

include ("includes/footer.inc.php"); 

?> 
