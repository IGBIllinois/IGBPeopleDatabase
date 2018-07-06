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
$html .= "<table><tr><td>".displayUser($user, $supervisor_id)."</td></tr></table>";
$html .= "<BR>Supervisor for:<BR>";


                        $theme_html .= displayUsersByType($user_tmp, $supervisor_id);

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

<?php

function displayUser($user, $user_id, $show_theme=0) {
    
        $user_info = $user->get_user($user_id);
        $user_info = $user_info[0];

        $image_location = "default.png";
        if($user_info['image_location'] != null &&  $user_info['image_location'] != "") {
            $image_location = $user_info['image_location'];
        }
        // do not show supervisor for admin (type id=12)
      $html = "<a href='profile.php?user_id=".$user_info['user_id']."'><img src='"."http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) .  "/images/users/". $image_location ."'><BR>".
              $user_info['first_name'] . " ".$user_info['last_name']. "<BR>".
              (($show_theme && $user_info['supervisor_id'] && ($user->get_supervisor_name() != NULL) && ($user->get_supervisor_name() != "") &&($user->get_supervisor_name() != " ")) ? ($user->get_supervisor_name() . " Group" . "<BR>" ) : "") . 
              "</a>";
      
      return $html;
      
}

function displayUsersByType($user, $supervisor_id) {
    global $db;
    //We'd like the 
    //PI first, 
    //then research specialists/post-docs, 
    //technicians, 
    //graduate students, 
    //undergraduate students. 
    //We also want to have the administrative personnel listed after the research groups.  

    $max = 5;
    
    $html = "<BR><table>".
            "<tr>";
    
    $i=0;
    
    $type_query = "SELECT * from type order by field(name, 'Theme Leader') desc, type_id";
    $type_result = $db->query($type_query);
    
    foreach($type_result as $type) {
        $i=0;
        $user_results = $user->get_users_for_supervisor($supervisor_id, array($type['type_id']));
        //print_r($user_results);
        if(count($user_results[0]) > 0) {
            $html .= "<table><th colspan='5'>".$type['name'] ."</th></tr><tr>";
            foreach($user_results as $userRecord) {
                if($i >= $max) {
                    $html .="</tr><tr>";
                    $i=0;
                }
                $user_id = $userRecord['user_id'];
                $html .= "<td>";
                $html .= displayUser($user, $user_id);
                $html .= "</td>";    
                $i++;
            }
            $html.= "</tr></table><BR>";
            }
    }
    /*
    $html .= "<th colspan='5'>Theme Leader</th></tr><tr>";
    foreach($leader_search_results as $userRecord) {
        if($i >= $max) {
            $html .="</tr><tr>";
            $i=0;
        }
        $user_id = $userRecord['user_id'];
        $html .= "<td>";
        $html .= displayUser($user, $user_id);
        $html .= "</td>";    
        $i++;
    }
    $html.= "</tr></table>";
    
    if(count($admin_results) > 0) {
    $html .="<BR><table><tr><th colspan='5'>Administrators</th></tr><tr>";
    $i=0;
    foreach($admin_results as $userRecord) {
        if($i >= $max) {
            $html .="</tr><tr>";
            $i=0;
        }
        $user_id = $userRecord['user_id'];
        $html .= "<td>";
        $html .= displayUser($user, $user_id);
        $html .= "</td>";     
        $i++;
    }
    $html .= "</tr></table>";
    }

    if(count($faculty_results) > 0) {
    $html .="<BR><table><th colspan='5'>Faculty</th><BR><tr>";
    $i=0;
    foreach($faculty_results as $userRecord) {
        if($i >= $max) {
            $html .="</tr><tr>";
            $i=0;
        }
        $user_id = $userRecord['user_id'];
        $html .= "<td>";
        $html .= displayUser($user, $user_id);
        $html .= "</td>";     
        $i++;
    }
    $html .= "</tr></table>";
            
    }
    
    
    if(count($admin_personnel_results) > 0) {
    $html .="<BR><table><tr><th colspan='5'>Administrative Personnel</th></tr><tr>";
    $i=0;
    foreach($admin_personnel_results as $userRecord) {
        if($i >= $max) {
            $html .="</tr><tr>";
            $i=0;
        }
        $user_id = $userRecord['user_id'];
        $html .= "<td>";
        $html .= displayUser($user, $user_id);
        $html .= "</td>";    
        $i++;
    }
        $html .= "</tr></table>";
    }
    
    if(count($research_staff_results) > 0) {
    $html .="<BR><table><tr><th colspan='5'>Post Docs and Research Specialists</th></tr><tr>";
    $i=0;
    foreach($research_staff_results as $userRecord) {
        if($i >= $max) {
            $html .="</tr><tr>";
            $i=0;
        }
        $user_id = $userRecord['user_id'];
        $html .= "<td>";
        $html .= displayUser($user, $user_id, true);
        $html .= "</td>";     
        $i++;
    }
        $html .= "</tr></table>";
    }
    
    if(count($tech_results) > 0) {
    $html .="<BR><table><tr><th colspan='5'>Technicians</th></tr><tr>";
    $i=0;
    foreach($tech_results as $userRecord) {
        if($i >= $max) {
            $html .="</tr><tr>";
            $i=0;
        }
        $user_id = $userRecord['user_id'];
        $html .= "<td>";
        $html .= displayUser($user, $user_id, true);
        $html .= "</td>";    
        $i++;
    }
        $html .= "</tr></table>";
    }
    

    
    if(count($grad_results) > 0) {
        $html .="<BR><table><tr><th colspan='5'>Graduate Students</th></tr><tr>";
    $i=0;
    foreach($grad_results as $userRecord) {
        if($i >= $max) {
            $html .="</tr><tr>";
            $i=0;
        }
        $user_id = $userRecord['user_id'];
        $html .= "<td>";
        $html .= displayUser($user, $user_id, true);
        $html .= "</td>";    
        $i++;
    }
        $html .= "</tr></table>";
    }
    
    if(count($undergrad_results) > 0) {
    
        $html .="<BR><table><tr><th colspan='5'>Undergraduate Students</th></tr><tr>";
    $i=0;
    foreach($undergrad_results as $userRecord) {
        if($i >= $max) {
            $html .="</tr><tr>";
            $i=0;
        }
        $user_id = $userRecord['user_id'];
        $html .= "<td>";
        $html .= displayUser($user, $user_id, true);
        $html .= "</td>";    
        $i++;
    }
        $html .= "</tr></table>";
    }
    
    
    /*
    if(count($alumnus_results) > 0) {
    
        $html .="<BR><table><tr><th colspan='5'>Alumni</th></tr><tr>";
    $i=0;
    foreach($alumnus_results as $userRecord) {
        if($i >= $max) {
            $html .="</tr><tr>";
            $i=0;
        }
        $user_id = $userRecord['user_id'];
        $html .= "<td>";
        $html .= displayUser($user, $user_id);
        $html .= "</td>";    
        $i++;
    }
        $html .= "</tr></table>";
    }
*/
    //$html .= "<BR><a href='alumnus.php?theme=".$theme."'>View Alumni</a><BR>";
    
    //$html .= "</tr></table>";
    
    return $html;
}