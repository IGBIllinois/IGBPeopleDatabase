<?php 
include 'includes/functions.inc.php'; 
include_once 'includes/main.inc.php';

if (!$_SESSION['admin']){
header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/login.php"); 	
exit(); 
}

?>

<script>
$(document).ready(function(){

  
	$(".alphabutton").click(function(){
		 TINY.box.fill({'test'})
  	});

  

});
</script>

<?php


echo "<body onLoad=\"document.search.search_value.focus()\">"; 



$table_html = "";

$filters = NULL;
	
$user = new user($db);
$html = "";
$htmltest = "";

	for($i=0; $i<26; $i++){
		
		$letter = $alphabet[$i];
		$search_results = $user->alpha_search($letter, '1');
		
		if (count($search_results) == 0) { 
		  	$html .= "<input type='button' name='".$letter."' id='".$letter."' value='".$letter."' 
					class='alphabutton disabled' > ";
			$htmltest .=  "<a href='javascript:TINY.box.fill(\"ajax.html\",1,0,1)'> ".$letter." </a>";
	  	}
		else {
			$html .= "<input type='button' name='".$letter."' id='".$letter."' value='".$letter."' 
					class='alphabutton' > ";
					
			$htmltest .=  "<a href='javascript:TINY.box.load(boxid:".$letter.",1,0,1)'> ".$letter." </a>";
	
			$letter_html .= "<div class='box' id='".$letter."' style='display:none;'>";
			$letter_html .= result_list($search_results);
			$letter_html .= "</div>";
		
		}
		


	}



?> 


<?php  

echo $htmltest; 
echo $letter_html;


?> 


