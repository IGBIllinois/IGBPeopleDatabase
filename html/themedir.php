<?php #alpha

$page_title = "IGB People Database Search"; 

require_once 'includes/header.inc.php'; 

?>


<?php

if(isset($_GET['theme'])) {
    $curr_theme = $_GET['theme'];
}

$theme_list = $db->query($select_theme);

$table_html = "";

$user = new user($db);
$user_tmp = new user($db);
$user_id = $user->user_exists("netid", $_SESSION['username']);

$user->get_user($user_id);


$html = "";

foreach( $theme_list as $key=>$option ) {
    
    if($user->get_admin()==1 || $user->has_permission($user_id, $option['theme_id'])) {

        $query = "SELECT user_theme.user_id as user_id  FROM  user_theme where theme_id='".$option['theme_id']. "' and active = 1";

        $search_results = $db->query($query);

        if (count($search_results) == 0) { 
                $html .= "<input type='button' name='".$option['theme_id']."' id='".$option['short_name']."' value='".$option['short_name']."' 
                                class='themebutton disabled' > ";

        } else {	
            $html .= "<a href='themedir.php?theme=".$option['theme_id']."' class='themelink' >".$option['short_name']."</a> ";
        }
    }
}


    $html.="<a href='themedir.php?theme=0' class='themelink' >NO THEME</a> ";
    if($curr_theme != null && $curr_theme != "") {

        $theme_html .= html::displayUsersByType($db, $user_tmp, $curr_theme);

    }

$theme = new theme($db);
$theme->get_theme($curr_theme);
$theme_name = $theme->get_short_name();


?> 


<h1> <?php echo $theme_name; ?> Directory </h1>
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

require_once("includes/footer.inc.php"); 

?> 


