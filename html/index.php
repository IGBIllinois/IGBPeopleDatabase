<?php #search people

$page_title = "IGB People Database Search"; 

require_once 'includes/header.inc.php'; 


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