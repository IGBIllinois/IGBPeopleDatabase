<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class html {
    
    // Static functions
    
    /** Display a user in a table
     * 
     * @param type $user
     * @param type $user_id
     * @param type $show_theme
     * @return string
     */
    public static function displayUser($user, $user_id, $show_theme=0) {
    
        $user_info = $user->get_user($user_id);
        $user_info = $user_info[0];

        $image_location = "default.png";
        if($user_info['image_location'] != null &&  $user_info['image_location'] != "") {
            $image_location = $user_info['image_location'];
        }
        // do not show supervisor for admin (type id=12)
        
      $html = "<a href='profile.php?user_id=".$user_info['user_id']."'><img src='"."http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) .  "/images/users/". $image_location ."'><BR>".
              $user_info['first_name'] . " ".$user_info['last_name']. "<BR>".
              ((( $user->count_users_for_supervisor($user_info['user_id']) > 0)) ? "<a href=supervisordir.php?supervisor_id=".$user_id.">[Show Group]</a>" : "").
              "</a>";

      return $html;
      
}
    
    /** Displays users in a theme grouped by their type
    * 
    * @param type $db The Database object
    * @param type $user User object
    * @param type $theme Theme ID
    */
    public static function displayUsersByType($db, $user, $theme) {
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
       if($theme == 0) {
           $type_query = "Select 0";
       }
       $type_result = $db->query($type_query);

       foreach($type_result as $type) {
           
           $i=0;

           $user_results = $user->get_users_by_type($theme, array($type['type_id']));

           if(count($user_results[0]) > 0) {
               $html .= "<table><th colspan='5'>".$type['name'] ."</th></tr><tr>";
               foreach($user_results as $userRecord) {
                   if($i >= $max) {
                       $html .="</tr><tr>";
                       $i=0;
                   }
                   $user_id = $userRecord['user_id'];
                   $html .= "<td>";
                   $html .= self::displayUser($user, $user_id, 1 , $theme);
                   $html .= "</td>";    
                   $i++;
               }
               $html.= "</tr></table><BR>";
               }
       }
       return $html;
   }
   
   public static function displayUsersBySupervisor($db, $user, $supervisor_id) {
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
                $html .= self::displayUser($user, $user_id);
                $html .= "</td>";    
                $i++;
            }
            $html.= "</tr></table><BR>";
            }
    }
    
    return $html;
}


public static function write_user_table( $id, $user_list, $class = 0)
{
$tr = array(0=>"rowodd", 1=>"roweven");
$table_html = "<table name='".$id."' id='".$id."' class='".$class."' >
		<thead>
        <tr>				
                <th ></th>
                <th >Name</th>
                <th >Email</th>
                <th >Theme</th>
                <th >Type</th>
                <th >Room#</th>
        </tr>
		</thead>";

	$table_html .= "";
        $i=0;
	//for ($i = 0; $i < count($user_list); $i++) {
        foreach($user_list as $u) {

            $x = $i % 2;

            $table_html .= "<tr >"; 			
            $table_html .= "<td><a id='profile' href='profile.php?user_id=" . $u->get_user_id() . "'>>></a></td>";
            $table_html .= "<td>" . $u->get_first_name() ." ". $u->get_last_name()."</td>";
            $table_html .= "<td>" . $u->get_email() . "</td>";
            $table_html .= "<td>" . implode(",",$u->get_theme_short_names()) . "</td>";
            $table_html .= "<td>" . implode(",",$u->get_type_names()) . "</td>";
            $table_html .= "<td>" . $u->get_igb_room() . "</td>";	
            $table_html .= "</tr>";
            
            $i++;
        }

$table_html .= "</table>"; 
return $table_html;
}

public static function success_message($message){
		return "<div class='success'>".$message."</div>";
	}
	public static function error_message($message){
		return "<div class='error'>".$message."</div>";
	}
	public static function warning_message($message){
		return "<div class='alert alert-warning'>".$message."</div>";
	}
        
        /** 
         * Writes a message based on a result array
         *
         * @param array $result An array of the format: 
         *  ("RESULT"=>TRUE | FALSE,
         *   "MESSAGE"=>[string])
         *       If "RESULT" is FALSE it will display "MESSAGE" as an error message, 
         *      else it will display it as a success message.
         */
        public static function write_message($result) {
            if($result['RESULT']) {
                echo(self::success_message($result['MESSAGE']));
            } else {
                echo(self::error_message($result['MESSAGE']));
            }
        }
        
        /**
        * Creates an HTML input based on the parameters given
        * 
        * @param string $type Type of input to create. They include:
        *      "select": a drop-down selection box
        *      "date": a date selection input
        *      "begin": Text input for the start of a range of values
        *      "end": Text input for the end for a range of values
        *      "default": Text input
        * @param string $name Name of the input
        * @param string $default Default value, if any
        * @param array $array Array of values, used for options in the "select" input
        * @param int $id optional ID number for this input
        * @param string $onChange javascript for "onChange" method (optional)
        * @param type $id_name 
        */
       public static function createInput($type, $name, $default, $array=array(), $id="", $onChange="", $id_name="id") {
         $formName = $name;
           if($id != "") {
               $formName = $name . "_" . $id;
           }

           switch ($type) {

           case "select":
             print "<select id='{$formName}' name='{$formName}' ". ($onChange != "" ? " onChange='$onChange' " : "") . (($id != "") ? " id='{$name}_{$id}' ": "") .">";
             print "<option value=''>None</option>";
             $i=0;
             foreach ($array as $value) {
               print "<option value={$value[$id_name]}";
               if ($value['id'] == $default)
                 print " selected";

               print ">{$value['name']}</option>";
             }
             print "</select>";
             break;
           case "date":
             print "<input type=text id=datepicker class={$name} name={$formName} value={$default}>";
             break;
           case "begin":
             print "<input type=text id=from class={$name} name={$formName} value={$default}>";
             break;
           case "end":
             print "<input type=text id=to class={$name} name={$formName} value={$default}>";
             break;
           default:
               print "<input class='{$name}' name='{$formName}' ".(($id != "") ? " id='{$formName}' " : "" ) . " value=\"{$default}\">";
         }
       }


    /**
     * Redirects to a new web page
     * 
     * @param string $url URL to redirect to
     */
    public static function redirect($url) {
        ob_start();
        header('Location: '.$url);
        ob_end_flush();
        die();
    }
    
    
    /** Writes an HTML table of Themes
     * 
     * @param string $id Table name
     * @param array $theme_array Array of Theme objects
     * @return string
     */
    public static function theme_list_table( $id, $theme_array)
{
	$status_arr = array(0=>"Inactive", 1=>"Active");
	$table_html = "<table name='".$id."' id='".$id."' >
			<thead>
			<tr>
					<th >Theme Name</th>
					<th >Abbrev.</th>	
					<th >Leader</th>
					<th >Status</th>
					<th ></th>	
			</tr>
			</thead>";
	if (count($theme_array) == 0) { 
			  
		  }
	else {
		$table_html .= "";
		for ($i = 0; $i < count($theme_array); $i++) {
                    $theme= $theme_array[$i];
				$x = $i % 2;
				
					$table_html .= "<tr >"; 
					$table_html .= "<td>" . $theme->get_name() . "</td>";
					$table_html .= "<td>" . $theme->get_short_name() . "</td>";
					$table_html .= "<td>
							<a href='profile.php?user_id=" . $theme->get_leader_id() . "'>
							" . $theme->get_leader_name() . "</a></td>";
					$table_html .= "<td>" . $status_arr[$theme->get_status()] . "</td>";
					$table_html .= "<td>view details</td>";
					$table_html .= "</tr>";
			
			}
	}
	$table_html .= "</table>"; 
	return $table_html;
}

}