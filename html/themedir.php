<?php #alpha

$page_title = "IGB People Database Search"; 

require_once 'includes/header.inc.php'; 

?>


<?php

if(isset($_GET['theme'])) {
    $curr_theme = $_GET['theme'];
}


$all_themes = theme::get_themes($db);

$table_html = "";

$user = new user($db);
$user_tmp = new user($db);
$user_id = $user->user_exists("netid", $_SESSION['username']);

$user->get_user($user_id);


$html = "";

foreach( $all_themes as $this_theme ) {
    
    if($user->get_admin()==1 || $user->has_permission($user_id, $this_theme->get_theme_id())) {

        // Only show themes for which this user has permission
        $theme_id = $this_theme->get_theme_id();
        $short_name = $this_theme->get_short_name();
        $query = "SELECT user_theme.user_id as user_id  FROM  user_theme where theme_id=:theme_id and active = 1";
        $params = array("theme_id"=>$theme_id);
        $search_results = $db->get_query_result($query, $params);
        if (count($search_results) == 0) { 
                //$html .= "<input type='button' name='".$theme_id."' id='".$short_name."' value='".$short_name."' 
                //               class='themebutton disabled' > ";

        } else {	
            $html .= "<a href='themedir.php?theme=".$theme_id."' class='themelink' >".$short_name."</a> ";
        }
    }
}

if($user->get_admin() == 1) {
    $html.="<a href='themedir.php?theme=0' class='themelink' >NO THEME</a> ";
}
    if($curr_theme != null && $curr_theme != "") {

        $theme_html .= html::displayUsersByType($db, $user_tmp, $curr_theme);

    }


$theme = new theme($db, $curr_theme);
$theme_name = $theme->get_short_name();


?> 


<h1> <?php echo $theme_name; ?> Directory </h1>
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
    echo $theme_html; 
?> 



 
<?php 

require_once("includes/footer.inc.php"); 

?> 


