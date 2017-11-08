<?php #alpha

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

/*

$(document).ready(function(){




 $("ul#directory").show();
 
 	 $('.dir').css("z-index","-1");
 	 $('.themetable').hide();

 
 	 $(".themebutton").click(function(){
									  
									 		  
          var theme = $(this).attr("id");
		 
		  	$('#'+theme+'.themetable').dataTable( {
							"bPaginate": true,
							"sPaginationType": "full_numbers",
							"bLengthChange": false,
							"bFilter": false,
							"bSort": false,
							"bInfo": false,
							"bRetrieve": true,
							"bAutoWidth": true } );
		  
		
		$('.dir').css("z-index","-1");
                $('.dir').hide();
		$('#'+theme+'.dir').css("z-index","1");
                $('#'+theme+'.dir').show();
                
		$('.themetable').hide();
		$('.dataTables_wrapper').hide();
		$('#'+theme+'.themetable').show();
                $('#'+theme+'_wrapper').show();      
        }); 
 
});



*/




</script>



<?php


//$curr_theme = $_GET['theme'];
$supervisor_id = $_GET['supervisor_id'];

//$theme_list = $db->query($select_theme);
//$supervisor_list = $user->supervisor_list($supervisor_id);

$table_html = "";


	
$user = new user($db);
$user_tmp = new user($db);
$user_id = $user->user_exists("netid", $_SESSION['username']);

$user->get_user($user_id);

$html = "";
$html .= "<table><tr><td>".displayUser($user, $supervisor_id)."</td></tr></table>";
$html .= "<BR>Supervisor for:<BR>";
/*
	foreach( $theme_list as $key=>$option )
    {
            //echo("<BR><BR>THEME = ".$option['theme_id']."<BR><BR>");
		if($user->get_admin()==1 || $user->has_permission($user_id, $option['theme_id'])) {
		$filters = array();
		$filters["users.theme_id"] = array($option['theme_id'], "WHERE");
		$filters["users.theme_1_id"] = array($option['theme_id'], "OR");
		$filters["users.theme_2_id"] = array($option['theme_id'], "OR");
		
		//$search_results = $user->search($filters);
                $query = "SELECT users.user_id as user_id  FROM  users where theme_id='".$option['theme_id']. "' OR theme_1_id='".$option['theme_id']."' OR theme_2_id='".$option['theme_id']."'";
		//echo("query = $query <BR>");
                $search_results = $db->query($query);
                
                if (count($search_results) == 0) { 
		  	$html .= "<input type='button' name='".$option['theme_id']."' id='".$option['short_name']."' value='".$option['short_name']."' 
					class='themebutton disabled' > ";
                    
	  	}
		else {	
		//$html .= "<input type='button' name='".$option['theme_id']."' id='".$option['short_name']."' value='".$option['short_name']."' 
		//			class='themebutton' > ";
                    $html .= "<a href='themedir.php?theme=".$option['theme_id']."' class='themelink' >".$option['short_name']."</a> ";
                }
    }
    }
   
    $null_query = "SELECT users.user_id as user_id from users where theme_id='0' or theme_id=NULL'";
    $null_search_results = $db->query($query);
    $html.="<a href='themedir.php?theme=0' class='themelink' >NO THEME</a> ";
 * 
 */
    //if($curr_theme != null && $curr_theme != "") {
	
			//$theme_html .= "<div class='dir' id='".$curr_theme."'>";
                        
			//$theme_html .= result_table( $option['short_name'], $search_results, "themetable" );
                        //foreach($search_results as $userRecord) {
                        //    $theme_html .= displayUser($user, $userRecord['user_id']);
                        //}
                        $theme_html .= displayUsersByType($user_tmp, $supervisor_id);
			//$theme_html .= "</div>";
		
		//}
		
		//print_r($search_results);
                //foreach($search_results as $userRecord) {
                    //print_r($userRecord);
                    //echo("<BR>User id = ".$userRecord['user_id']."<BR>");
//                    /$user = new user($db, $userRecord['user_id']);
                    //echo("User id = ".$user->get_user_id()."<BR>");
                    //$theme_html .= displayUser($user, $userRecord['user_id']);
                    
                //}
                //}
		
    //}


	
	



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
        //echo(($show_theme && $user_info['supervisor_id'] && ($user->get_supervisor_name() != NULL) && ($user->get_supervisor_name() != "") &&($user->get_supervisor_name() != " ")) ? ("supervisor name = ". $user->get_supervisor_name()."<BR>") : "");
        //print_r($user_info);
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
        
    // PI
    /*
    $leader_filters = array();
		$leader_filters["users.theme_id"] = array($theme, "WHERE");
                $leader_filters["type.name"] = array ("Theme Leader", "AND");
		//$leader_filters["users.theme_1_id"] = array($theme, "OR");
		//$leader_filters["users.theme_2_id"] = array($theme, "OR");
                
    $admin_filters = array();
		$admin_filters["users.theme_id"] = array($theme, "WHERE");
                $admin_filters["type.name"] = array ("Admin", "AND");
		//$leader_filters["users.theme_1_id"] = array($theme, "OR");
		//$leader_filters["users.theme_2_id"] = array($theme, "OR");
                //            
    // research specialists/post-docs,             
    $research_staff_filters = array();
		$research_staff_filters["users.theme_id"] = array($theme, "WHERE");
                $research_staff_filters["type.name"] = array ("Post Doc", "AND");
                //$research_staff_filters["type.name"] = array ("Post Doc", "OR");
		//$research_staff_filters["users.theme_1_id"] = array($theme, "OR");
		//$research_staff_filters["users.theme_2_id"] = array($theme, "OR");
     
    // technicians,             
    $tech_filters = array();
		$tech_filters["users.theme_id"] = array($theme, "WHERE");
                $tech_filters["type.name"] = array ("IGB Staff", "AND");
                //$tech_filters["type.name"] = array ("Reseearch Staff", "OR");
		//$tech_filters["users.theme_1_id"] = array($theme, "OR");
		//$tech_filters["users.theme_2_id"] = array($theme, "OR");
                
    // Graduate students         
    $grad_filters = array();
		$grad_filters["users.theme_id"] = array($theme, "WHERE");
                $grad_filters["type.name"] = array ("Grad", "AND");
		//$grad_filters["users.theme_1_id"] = array($theme, "OR");
		//$grad_filters["users.theme_2_id"] = array($theme, "OR"); 
   
    // Undergraduate students            
    $undergrad_filters = array();
		$undergrad_filters["users.theme_id"] = array($theme, "WHERE");
                $undergrad_filters["type.name"] = array ("Undergrad", "AND");
		//$undergrad_filters["users.theme_1_id"] = array($theme, "OR");
		//$undergrad_filters["users.theme_2_id"] = array($theme, "OR");                

    //Alumnus filters
    $alumnus_filters = array();
		$alumnus_filters["users.theme_id"] = array($theme, "WHERE");
                $alumnus_filters["type.name"] = array ("Alumnus", "AND");
                
      $other_filters2 = array();
		$other_filters["users.theme_id"] = array($theme, "WHERE");
                $other_filters["type.name"] = array ("Theme Leader", "AND NOT");   
                $other_filters["type.name"] = array ("Research Staff", "AND NOT");
                $other_filters["type.name"] = array ("IGB Fellows", "AND NOT");   
		$other_filters["type.name"] = array ("Post Doc", "AND NOT");   
                $other_filters["type.name"] = array ("IGB Staff", "AND NOT");   
                $other_filters["type.name"] = array ("Grad", "AND NOT");   
                $other_filters["type.name"] = array ("Undergrad", "AND NOT");   
                $other_filters["type.name"] = array ("Faculty", "AND NOT");
                
      $other_filters = array(
                "users.theme_id" => array($theme, "WHERE"),
                "type.name" => array ("Theme Leader", "AND NOT"),   
                "type.name" => array ("Research Staff", "AND NOT"),
                "type.name" => array ("IGB Fellows", "AND NOT"),  
		"type.name" => array ("Post Doc", "AND NOT"),   
                "type.name" => array ("IGB Staff", "AND NOT"), 
                "type.name" => array("Grad", "AND NOT"),
                "type.name" => array ("Undergrad", "AND NOT"),
                "type.name" => array ("Faculty", "AND NOT")
          );

      
                //$other_filters["users.theme_1_id"] = array($theme, "OR");
		//$other_filters["users.theme_2_id"] = array($theme, "OR");
                 */   
                
/*		
    //$leader_search_results = $user->search($leader_filters); // 11
    $leader_search_results = $user->get_users_for_supervisor($supervisor_id, array(11));
    //$admin_results = $user->search($admin_filters); // 12
    $admin_results = $user->get_users_for_supervisor($supervisor_id, array(12));
    $faculty_results = $user->get_users_for_supervisor($supervisor_id, array(1,2));
    //$research_staff_results = $user->search($research_staff_filters);
    $research_staff_results = $user->get_users_for_supervisor($supervisor_id, array(6,4));
    //$tech_results = $user->search($tech_filters);
    // Research staff = 7
    // Hourly = 14,
    // Extra help = 20
    $tech_results = $user->get_users_for_supervisor($supervisor_id, array(7, 14, 20));
    $admin_personnel_results = $user->get_users_for_supervisor($supervisor_id, array(5));
    //$grad_results = $user->search($grad_filters);
    $grad_results = $user->get_users_for_supervisor($supervisor_id, array(3));
    $undergrad_results = $user->get_users_for_supervisor($supervisor_id, array(10));
    //$alumnus_results = $user->search($alumnus_filters);
    //$alumnus_results = $user->get_users_by_type($theme, array(21));
   */ 
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