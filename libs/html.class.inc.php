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
        
      $html = "<a href='profile.php?user_id=".$user_info['user_id']."'>".
              "<img src='"."http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) .  "/images/users/". $image_location ."'><BR>".
              $user_info['first_name'] . " ".$user_info['last_name']. "<BR>".
              // If this user is a supervisor, add link to show page of users for whom they are a supervisor
              ((( $user->count_users_for_supervisor($user_info['user_id']) > 0)) 
                      ? "<a href=supervisordir.php?supervisor_id=".$user_id.">[Show Group]</a>" 
                      : "").
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
       $type_result = $db->get_query_result($type_query, null);

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
        $type_result = $db->get_query_result($type_query);

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

    /** Write a table of users
     * 
     * @param string $id Name of the table
     * @param array $user_list Array of user objects
     * @param string $class css class for table
     * @return string
     */
    public static function write_user_table( $id, $user_list, $class = 0)
    {
    $tr = array(0=>"rowodd", 1=>"roweven");
    $table_html = "<table name='".$id."' id='".$id."' class='".$class." display table-striped' >
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
         print "<select id='{$formName}' name='{$formName}' ". 
                 ($onChange != "" ? " onChange='$onChange' " : "") . 
                 (($id != "") ? " id='{$name}_{$id}' ": "") .">";
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
    $table_html = "<table name='".$id."' id='".$id."' class='display table-striped'>
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
                        <a href='profile.php?user_id=" . 
                        $theme->get_leader_id() . "'>
                        " . $theme->get_leader_name() . "</a></td>";
            $table_html .= "<td>" . $status_arr[$theme->get_status()] . "</td>";
            $table_html .= "<td>view details</td>";
            $table_html .= "</tr>";

                }
    }
    $table_html .= "</table>"; 
    return $table_html;
}


 /* Used in Department dropdown (add.php), Theme type (type_edit.php) */
 /** 
 *
 * @param $name: The field name of the element
 * @param $options: A list of options, taken from a database query
 * @param $selected: the default selected option. Defaults to null
 *
 * @return: The HTML text for the drop down menu
 */
public static function dropdown( $name, $options, $selected=null, $name_id=-1)
{
    /*** begin the select ***/
    $dropdown = '<select name="'.$name.'" id="'.$name.'">'."\n";
	$dropdown .= '<option value="0"'.$selected.'>'.' ... '.'</option>'."\n";

    $selected = $selected;
    /*** loop over the options ***/
    foreach( $options as $key=>$option )
    {
        $array_keys = array_keys($option);
        $id = $array_keys[0]; 
        if($name_id == -1) {
            $param = $array_keys[1];
        } else {
            $param = $array_keys[$name_id];
        }
                
        /*** assign a selected value ***/
        $select = $selected==$option[$id] ? ' selected' : null;

        /*** add each option to the dropdown ***/
        $dropdown .= '<option value="'.$option[$id].'"'.$select.'>'.$option[$param].'</option>'."\n";
    }

    /*** close the select ***/
    $dropdown .= '</select>'."\n";

    /*** and return the completed dropdown ***/
    return $dropdown;
}

/** Used in states (add.php), theme type status (type_edit.php)*/
 /** 
 *
 * @param $name: The field name of the element
 * @param $options: An array in the form of 'key = >value' pairs, for example: 'array(0=>"Inactive", 1=>"Active")'
 * @param $selected: the default selected option. Defaults to null
 *
 * @return: The HTML text for the drop down menu
 */
public static function simple_drop( $name, $options, $selected=null )
{
    /*** begin the select ***/
    $dropdown = '<select name="'.$name.'" id="'.$name.'">'."\n";
	$dropdown .= '<option value =""'.$selected.'>'.' ... '.'</option>'."\n";
	
    $selected = $selected;
    /*** loop over the options ***/
 
    foreach( $options as $key=>$option )
    {
        /*** assign a selected value ***/
        $select = $selected==$key ? ' selected' : null;

        /*** add each option to the dropdown ***/
        $dropdown .= '<option value ="'.$key.'"'.$select.'>'.$option.'</option>'."\n";
    }

    /*** close the select ***/
    $dropdown .= '</select>'."\n";

    /*** and return the completed dropdown ***/
    return $dropdown;
}


/** Used in grad_drop, year_drop (add.php) */
 /** 
 *
 * @param $name: The field name of the element
 * @param $options: An array of single, unkeyed options, for example: 'array("FALL", "SPRING", "SUMMER");'
 * @param $selected: the default selected option. Defaults to null
 *
 * @return: The HTML text for the drop down menu
 */
public static function drop( $name, $options, $selected=null )
{
    /*** begin the select ***/
    $dropdown = '<select name="'.$name.'" id="'.$name.'">'."\n";
	$dropdown .= '<option value =""'.$selected.'>'.' ... '.'</option>'."\n";
	
    $selected = $selected;
    /*** loop over the options ***/
 
    foreach( $options as $key=>$option )
    {
        /*** assign a selected value ***/
        $select = $selected==$option ? ' selected' : null;

        /*** add each option to the dropdown ***/
        $dropdown .= '<option value ="'.$option.'"'.$select.'>'.$option.'</option>'."\n";
    }

    /*** close the select ***/
    $dropdown .= '</select>'."\n";

    /*** and return the completed dropdown ***/
    return $dropdown;
}


/** Writes a table of active keys
 * 
 * @param string $id Name of table
 * @param array $search_results Array of key_info objects
 * @return string HTML for the table
 */
public static function active_key_table( $id, $search_results)
{
	$paid_arr = array(0=>"Unpaid", 1=>"Paid");
	$table_html = "<table name='".$id."' id='".$id."' class='display table-striped'>
                <thead>
                <tr>

                    <th >Room #</th>
                    <th >Deposit</th>	
                    <th >Date Issued</th>	

                </tr>
                </thead>";
	if (!count($search_results) == 0) { 
            $table_html .= "";
            for ($i = 0; $i < count($search_results); $i++) {
                $key_info = $search_results[$i];
                
                $key = $key_info->get_key();
                $x = $i % 2;

                $table_html .= "<tr >"; 			
                $table_html .= "<td>" . $key->get_key_room()."</td>";
                $table_html .= "<td>" . $paid_arr[$key_info->get_paid()] . "</td>";
                $table_html .= "<td>" . $key_info->get_date_issued() . "</td>";	
                $table_html .= "</tr>";

            }
	}
	$table_html .= "</table>"; 
	return $table_html;
}



/** Writes a table of inactive keys
 * 
 * @param string $id Nmae of table
 * @param array $search_results Array of key_info objects
 * @return string HTML for the table
 */
public function inactive_key_table( $id, $search_results)
{
	
	$dep_arr = array(0=>"Not Refunded", 1=>"Refunded");
	$table_html = 
            "<table name='".$id."' id='".$id."' class='display table-striped' >
            <thead>
            <tr>
                <th >Room #</th>
                <th >Deposit</th>
                <th >Condition</th>	
                <th >Date Issued</th>	
                <th >Date Returned</th>	
            </tr>
            </thead>";
	if (!count($search_results) == 0) { 
            
            $table_html .= "";
            for ($i = 0; $i < count($search_results); $i++) {
                $key_info = $search_results[$i];
                $key = $key_info->get_key();
                $x = $i % 2;

                $table_html .= "<tr >"; 
                $table_html .= "<td>" . $key->get_key_room()."</td>";
                $table_html .= "<td>" . $dep_arr[$key_info->get_payment_returned()] . "</td>";
                $table_html .= "<td>" . $key_info->get_return_condition() . "</td>";
                $table_html .= "<td>" . $key_info->get_date_issued() . "</td>";	
                $table_html .= "<td>" . $key_info->get_date_returned() . "</td>";	
                $table_html .= "</tr>";

            }
	}
	$table_html .= "</table>"; 
	return $table_html;
}


/** Writes a table of all keys
 * 
 * @param type $db Database object
 * @return string HTML of key table
 */
public static function key_list_table($db)
{
	$key_list = key::get_keys($db);
        $id = "key_list_table";

	$status_arr = array(0=>"Inactive", 1=>"Active");
	$table_html = 
                "<div><table name='".$id."' id='".$id."' class='display table-striped' >
                <thead>
                <tr>
                    <th >Key Name</th>	
                    <th >Room #</th>	
                    <th >Status</th>
                    <th >(coming soon)</th>	
                </tr>
                </thead>";
	if (count($key_list) == 0) { 

        }
	else {
            $table_html .= "";
            for ($i = 0; $i < count($key_list); $i++) {
                $x = $i % 2;
                $key = $key_list[$i];
                $table_html .= "<tr >"; 
                $table_html .= "<td>" . $key->get_key_name() . "</td>";
                $table_html .= "<td>" . $key->get_key_room() . "</td>";
                $table_html .= "<td>" . $status_arr[$key->get_key_active()] . "</td>";
                $table_html .= "<td>view details</td>";
                $table_html .= "</tr>";

            }
	}
	$table_html .= "</table></div>"; 
	return $table_html;
}

/** Writes a table of Types
 * 
 * @param type $id
 * @param type $search_results
 * @return string
 */
public static function type_list_table( $id, $type_list)
{
    $status_arr = array(0=>"Inactive", 1=>"Active");
    $table_html = "<table name='".$id."' id='".$id."' class='display table-striped'>
        <thead>
        <tr>
            <th >Type Name</th>
            <th >Status</th>
        </tr>
        </thead>";
    if (count($type_list) == 0) { 

    }
    else {
        $table_html .= "";
        for ($i = 0; $i < count($type_list); $i++) {
            $type = $type_list[$i];
            $x = $i % 2;

            $table_html .= "<tr >"; 
            $table_html .= "<td>" . $type->get_name() . "</td>";
            $table_html .= "<td>" . $status_arr[$type->get_active()] . "</td>";
            $table_html .= "</tr>";

        }
    }
    $table_html .= "</table>"; 
    return $table_html;
}

/** 
 * Returns a table of departments
 * @param db $db Dababase objcect
 * @return string HTML of a table of departments
 */
public static function dept_list_table($db)
{
    $dept_list = department::get_all_departments($db);
    $id = "dept_list_table";
    
	$status_arr = array(0=>"Inactive", 1=>"Active");
	$table_html = "<div><table name='".$id."' id='".$id."' >
                <thead>
                <tr>
                    <th >Department Name</th>
                    <th >Code</th>
                </tr>
                </thead>";
	if (count($dept_list) == 0) { 
			  
	}
	else {
		$table_html .= "";
		for ($i = 0; $i < count($dept_list); $i++) {
                    $x = $i % 2;
                    $dept = $dept_list[$i];
                    $table_html .= "<tr >"; 
                    $table_html .= "<td>" . $dept->get_name() . "</td>";
                    $table_html .= "<td>" . $dept->get_code() . "</td>";
                    $table_html .= "</tr>";

            }
	}
	$table_html .= "</table></div>"; 
	return $table_html;
}

/**
 * Returns a dropdown of country names
 * @param sstring $name Dropdown box name & id
 * @return string HTML for a dropdown list of country names
*/
public function country_dropdown( $name )
{
	$result = "<select name='".$name."' id='".$name."'>
		
		<option value='United States' SELECTED>United States</option>
		<option value='Afghanistan'>Afghanistan</option>
		<option value='Albania'>Albania</option>
		<option value='Algeria'>Algeria</option>
		<option value='Andorra'>Andorra</option>
		<option value='Angola'>Angola</option>
		<option value='Antigua & Deps'>Antigua & Deps</option>
		<option value='Argentina'>Argentina</option>
		<option value='Armenia'>Armenia</option>
		<option value='Australia'>Australia</option>
		<option value='Austria'>Austria</option>
		<option value='Azerbaijan'>Azerbaijan</option>
		<option value='Bahamas'>Bahamas</option>
		<option value='Bahrain'>Bahrain</option>
		<option value='Bangladesh'>Bangladesh</option>
		<option value='Barbados'>Barbados</option>
		<option value='Belarus'>Belarus</option>
		<option value='Belgium'>Belgium</option>
		<option value='Belize'>Belize</option>
		<option value='Benin'>Benin</option>
		<option value='Bhutan'>Bhutan</option>
		<option value='Bolivia'>Bolivia</option>
		<option value='Bosnia Herzegovina'>Bosnia Herzegovina</option>
		<option value='Botswana'>Botswana</option>
		<option value='Brazil'>Brazil</option>
		<option value='Brunei'>Brunei</option>
		<option value='Bulgaria'>Bulgaria</option>
		<option value='Burkina'>Burkina</option>
		<option value='Burundi'>Burundi</option>
		<option value='Cambodia'>Cambodia</option>
		<option value='Cameroon'>Cameroon</option>
		<option value='Canada'>Canada</option>
		<option value='Cape Verdi'>Cape Verdi</option>
		<option value='Central African Rep'>Central African Rep</option>
		<option value='Chad'>Chad</option>
		<option value='Chile'>Chile</option>
		<option value='China'>China</option>
		<option value='Colombia'>Colombia</option>
		<option value='Comoros'>Comoros</option>
		<option value='Congo'>Congo</option>
		<option value='Congo {Democratic Rep}'>Congo {Democratic Rep}</option>
		<option value='Costa Rica'>Costa Rica</option>
		<option value='Croatia'>Croatia</option>
		<option value='Cuba'>Cuba</option>
		<option value='Cyprus'>Cyprus</option>
		<option value='Czech Republic'>Czech Republic</option>
		<option value='Denmark'>Denmark</option>
		<option value='Djibouti'>Djibouti</option>
		<option value='Dominica'>Dominica</option>
		<option value='Dominican Republic'>Dominican Republic</option>
		<option value='East Timor'>East Timor</option>
		<option value='Ecuador'>Ecuador</option>
		<option value='Egypt'>Egypt</option>
		<option value='El Salvador'>El Salvador</option>
		<option value='Equatorial Guinea'>Equatorial Guinea</option>
		<option value='Eritrea'>Eritrea</option>
		<option value='Estonia'>Estonia</option>
		<option value='Ethiopia'>Ethiopia</option>
		<option value='Fiji'>Fiji</option>
		<option value='Finland'>Finland</option>
		<option value='France'>France</option>
		<option value='Gabon'>Gabon</option>
		<option value='Gambia'>Gambia</option>
		<option value='Georgia'>Georgia</option>
		<option value='Germany'>Germany</option>
		<option value='Ghana'>Ghana</option>
		<option value='Greece'>Greece</option>
		<option value='Grenada'>Grenada</option>
		<option value='Guatemala'>Guatemala</option>
		<option value='Guinea'>Guinea</option>
		<option value='Guinea-Bissau'>Guinea-Bissau</option>
		<option value='Guyana'>Guyana</option>
		<option value='Haiti'>Haiti</option>
		<option value='Honduras'>Honduras</option>
		<option value='Hungary'>Hungary</option>
		<option value='Iceland'>Iceland</option>
		<option value='India'>India</option>
		<option value='Indonesia'>Indonesia</option>
		<option value='Iran'>Iran</option>
		<option value='Iraq'>Iraq</option>
		<option value='Ireland {Republic}'>Ireland {Republic}</option>
		<option value='Israel'>Israel</option>
		<option value='Italy'>Italy</option>
		<option value='Ivory Coast'>Ivory Coast</option>
		<option value='Jamaica'>Jamaica</option>
		<option value='Japan'>Japan</option>
		<option value='Jordan'>Jordan</option>
		<option value='Kazakhstan'>Kazakhstan</option>
		<option value='Kenya'>Kenya</option>
		<option value='Kiribati'>Kiribati</option>
		<option value='Korea North'>Korea North</option>
		<option value='Korea South'>Korea South</option>
		<option value='Kuwait'>Kuwait</option>
		<option value='Kyrgyzstan'>Kyrgyzstan</option>
		<option value='Laos'>Laos</option>
		<option value='Latvia'>Latvia</option>
		<option value='Lebanon'>Lebanon</option>
		<option value='Lesotho'>Lesotho</option>
		<option value='Liberia'>Liberia</option>
		<option value='Libya'>Libya</option>
		<option value='Liechtenstein'>Liechtenstein</option>
		<option value='Lithuania'>Lithuania</option>
		<option value='Luxembourg'>Luxembourg</option>
		<option value='Macedonia'>Macedonia</option>
		<option value='Madagascar'>Madagascar</option>
		<option value='Malawi'>Malawi</option>
		<option value='Malaysia'>Malaysia</option>
		<option value='Maldives'>Maldives</option>
		<option value='Mali'>Mali</option>
		<option value='Malta'>Malta</option>
		<option value='Marshall Islands'>Marshall Islands</option>
		<option value='Mauritania'>Mauritania</option>
		<option value='Mauritius'>Mauritius</option>
		<option value='Mexico'>Mexico</option>
		<option value='Micronesia'>Micronesia</option>
		<option value='Moldova'>Moldova</option>
		<option value='Monaco'>Monaco</option>
		<option value='Mongolia'>Mongolia</option>
		<option value='Montenegro'>Montenegro</option>
		<option value='Morocco'>Morocco</option>
		<option value='Mozambique'>Mozambique</option>
		<option value='Myanmar {Burma}'>Myanmar {Burma}</option>
		<option value='Namibia'>Namibia</option>
		<option value='Nauru'>Nauru</option>
		<option value='Nepal'>Nepal</option>
		<option value='Netherlands'>Netherlands</option>
		<option value='New Zealand'>New Zealand</option>
		<option value='Nicaragua'>Nicaragua</option>
		<option value='Niger'>Niger</option>
		<option value='Nigeria'>Nigeria</option>
		<option value='Norway'>Norway</option>
		<option value='Oman'>Oman</option>
		<option value='Pakistan'>Pakistan</option>
		<option value='Palau'>Palau</option>
		<option value='Panama'>Panama</option>
		<option value='Papua New Guinea'>Papua New Guinea</option>
		<option value='Paraguay'>Paraguay</option>
		<option value='Peru'>Peru</option>
		<option value='Philippines'>Philippines</option>
		<option value='Poland'>Poland</option>
		<option value='Portugal'>Portugal</option>
		<option value='Qatar'>Qatar</option>
		<option value='Romania'>Romania</option>
		<option value='Russian Federation'>Russian Federation</option>
		<option value='Rwanda'>Rwanda</option>
		<option value='St Kitts & Nevis'>St Kitts & Nevis</option>
		<option value='St Lucia'>St Lucia</option>
		<option value='St Vincent & Gr/dines'>St Vincent & Gr/dines</option>
		<option value='Samoa'>Samoa</option>
		<option value='San Marino'>San Marino</option>
		<option value='Sao Tome & Principe'>Sao Tome & Principe</option>
		<option value='Saudi Arabia'>Saudi Arabia</option>
		<option value='Senegal'>Senegal</option>
		<option value='Serbia'>Serbia</option>
		<option value='Seychelles'>Seychelles</option>
		<option value='Sierra Leone'>Sierra Leone</option>
		<option value='Singapore'>Singapore</option>
		<option value='Slovakia'>Slovakia</option>
		<option value='Slovenia'>Slovenia</option>
		<option value='Solomon Islands'>Solomon Islands</option>
		<option value='Somalia'>Somalia</option>
		<option value='South Africa'>South Africa</option>
		<option value='Spain'>Spain</option>
		<option value='Sri Lanka'>Sri Lanka</option>
		<option value='Sudan'>Sudan</option>
		<option value='Suriname'>Suriname</option>
		<option value='Swaziland'>Swaziland</option>
		<option value='Sweden'>Sweden</option>
		<option value='Switzerland'>Switzerland</option>
		<option value='Syria'>Syria</option>
		<option value='Taiwan'>Taiwan</option>
		<option value='Tajikistan'>Tajikistan</option>
		<option value='Tanzania'>Tanzania</option>
		<option value='Thailand'>Thailand</option>
		<option value='Togo'>Togo</option>
		<option value='Tonga'>Tonga</option>
		<option value='Trinidad & Tobago'>Trinidad & Tobago</option>
		<option value='Tunisia'>Tunisia</option>
		<option value='Turkey'>Turkey</option>
		<option value='Turkmenistan'>Turkmenistan</option>
		<option value='Tuvalu'>Tuvalu</option>
		<option value='Uganda'>Uganda</option>
		<option value='Ukraine'>Ukraine</option>
		<option value='United Arab Emirates'>United Arab Emirates</option>
		<option value='United Kingdom'>United Kingdom</option>
		<option value='Uruguay'>Uruguay</option>
		<option value='Uzbekistan'>Uzbekistan</option>
		<option value='Vanuatu'>Vanuatu</option>
		<option value='Vatican City'>Vatican City</option>
		<option value='Venezuela'>Venezuela</option>
		<option value='Vietnam'>Vietnam</option>
		<option value='Yemen'>Yemen</option>
		<option value='Zambia'>Zambia</option>
		<option value='Zimbabwe'>Zimbabwe</option> 
		</select>";
		
return $result;
		
}

}