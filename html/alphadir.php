<?php 

$page_title = "IGB People Database Search"; 

require_once 'includes/header.inc.php'; 

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
for($i=0; $i<26; $i++){

    $letter = $alphabet[$i];
    $html .= "<a href='alphadir.php?letter=".$letter."' class='alphalink' >".$letter."</a> ";

}

if($curr_letter != null && $curr_letter != "") {
    $user_list = $user->alpha_search($curr_letter, $user_id);

    $letter_html .= "<div class='dir' id='".$curr_letter."'>". html::write_user_table( $curr_letter, $user_list, "alphatable" ) . "</div>";
}

?> 

<h1> IGB Alpha Directory </h1>
<br>
<h3></h3>
<script>
$(document).ready(function(){

 $("ul#admin").hide();
 $("ul#directory").show();

});
</script>

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
