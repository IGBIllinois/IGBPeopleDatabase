<?php #search people

$page_title = "IGB People Database Search"; 

include 'includes/header.inc.php'; 
include 'includes/functions.inc.php'; 
include_once 'includes/main.inc.php';

if (!$_SESSION['admin']){
header ("Location: https://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/login.php"); 	
exit(); 
}


$admin_id = $_SESSION['admin_id'];


$success_html = "<br><h4>
					
					<br>
					
					</h4>
					
					"	;






?>
<h1> Welcome 
<?php

echo $_SESSION['username'];

?>
</h1>
<br>
<h3></h3>
<?php
echo $success_html;

?>

<?php

include_once 'includes/footer.inc.php';
?>