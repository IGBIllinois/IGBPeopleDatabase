<?php
//////////////////////////////////////////
//
//	functions.inc.php
//
//	Functions to use...
//
//	By Crystal Ahn
//	April 2010
//
//////////////////////////////////////////

//include_once "libs/ExcelWriter.php";
//include_once '/Writer.php';

/*


 *
 * @create a dropdown select
 *
 * @param string $name
 *
 * @param array $options
 *
 * @param string $selected (optional)
 *
 * @return string
 *
 */
 
 /* Used in Department dropdown (add.php), Theme type (type_edit.php) */
 /** 
 *
 * @param $name: The field name of the element
 * @param $options: A list of options, taken from a database query
 * @param $selected: the default selected option. Defaults to null
 *
 * @return: The HTML text for the drop down menu
 */
function dropdown( $name, $options, $selected=null )
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
		$param = $array_keys[1];
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
function simple_drop( $name, $options, $selected=null )
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
function drop( $name, $options, $selected=null )
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



/*
 *
 * @create a table
 *
 * @param string $name
 *
 * @param array $search_results
 *
 * @return string
 *
 */

function result_table( $id, $search_results, $class = 0)
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
if (count($search_results) == 0) { 
		  //$table_html .= "<tbody><tr><td colspan='6'>0 Records Found</td></tr></tbody>"; 
	  }
else {
	$table_html .= "";
	for ($i = 0; $i < count($search_results); $i++) {
			$x = $i % 2;
			
                $table_html .= "<tr >"; 			
                $table_html .= "<td><a id='profile' href='profile.php?user_id=" . $search_results[$i]["user_id"] . "'>>></a></td>";
                $table_html .= "<td>" . $search_results[$i]["first_name"] ." ". $search_results[$i]["last_name"]."</td>";
                $table_html .= "<td>" . $search_results[$i]["email"] . "</td>";
                $table_html .= "<td>" . $search_results[$i]["theme_list"] . "</td>";
                $table_html .= "<td>" . $search_results[$i]["type_list"] . "</td>";
                $table_html .= "<td>" . $search_results[$i]["igb_room"] . "</td>";	
                $table_html .= "</tr>";
        
        }
}
$table_html .= "</table>"; 
return $table_html;
}

//////////////////////////////
//           KEYS           //    
//////////////////////////////
/*
 *
active key table
 *
 */

function active_key_table( $id, $search_results)
{
	$paid_arr = array(0=>"Unpaid", 1=>"Paid");
	$table_html = "<table name='".$id."' id='".$id."' >
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
				$x = $i % 2;
				
					$table_html .= "<tr >"; 			
					/*
					$table_html .= "<td>
										<a id='". $search_results[$i]["keyinfo_id"] ."' class='key_edit' href='#'>edit</a>
									</td>";
									*/
					$table_html .= "<td>" . $search_results[$i]["key_room"]."</td>";
					$table_html .= "<td>" . $paid_arr[$search_results[$i]["paid"]] . "</td>";
					$table_html .= "<td>" . $search_results[$i]["date_issued"] . "</td>";	
					$table_html .= "</tr>";
			
			}
	}
	$table_html .= "</table>"; 
	return $table_html;
}


/*
 *
inactive key table
 *
 */

function inactive_key_table( $id, $search_results)
{
	
	$dep_arr = array(0=>"Not Refunded", 1=>"Refunded");
	$table_html = "<table name='".$id."' id='".$id."' >
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
				$x = $i % 2;
				
					$table_html .= "<tr >"; 
					$table_html .= "<td>" . $search_results[$i]["key_room"]."</td>";
					$table_html .= "<td>" . $dep_arr[$search_results[$i]["payment_returned"]] . "</td>";
					$table_html .= "<td>" . $search_results[$i]["return_condition"] . "</td>";
					$table_html .= "<td>" . $search_results[$i]["date_issued"] . "</td>";	
					$table_html .= "<td>" . $search_results[$i]["date_returned"] . "</td>";	
					$table_html .= "</tr>";
			
			}
	}
	$table_html .= "</table>"; 
	return $table_html;
}


/*
 *
key list table
 *
 */

function key_list_table( $id, $search_results)
{
	
	$status_arr = array(0=>"Inactive", 1=>"Active");
	$table_html = "<div><table name='".$id."' id='".$id."' >
			<thead>
			<tr>
					<th >Key Name</th>	
					<th >Room #</th>	
					<th >Status</th>
					<th >(coming soon)</th>	
			</tr>
			</thead>";
	if (count($search_results) == 0) { 
			  //return "No Inactive Keys"; 
		  }
	else {
		$table_html .= "";
		for ($i = 0; $i < count($search_results); $i++) {
				$x = $i % 2;
				
					$table_html .= "<tr >"; 
					$table_html .= "<td>" . $search_results[$i]["key_name"] . "</td>";
					$table_html .= "<td>" . $search_results[$i]["key_room"] . "</td>";
					$table_html .= "<td>" . $status_arr[$search_results[$i]["key_active"]] . "</td>";
					$table_html .= "<td>view details</td>";
					$table_html .= "</tr>";
			
			}
	}
	$table_html .= "</table></div>"; 
	return $table_html;
}







/*
THEME LIST TABLE

data table of theme info
coming soon: possibly add theme secretary?


*/

function theme_list_table( $id, $search_results)
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
	if (count($search_results) == 0) { 
			  
		  }
	else {
		$table_html .= "";
		for ($i = 0; $i < count($search_results); $i++) {
				$x = $i % 2;
				
					$table_html .= "<tr >"; 
					$table_html .= "<td>" . $search_results[$i]["name"] . "</td>";
					$table_html .= "<td>" . $search_results[$i]["short_name"] . "</td>";
					$table_html .= "<td>
							<a href='profile.php?user_id=" . $search_results[$i]["leader_id"] . "'>
							" . $search_results[$i]["theme_leader_name"] . "</a></td>";
					$table_html .= "<td>" . $status_arr[$search_results[$i]["theme_active"]] . "</td>";
					$table_html .= "<td>view details</td>";
					$table_html .= "</tr>";
			
			}
	}
	$table_html .= "</table>"; 
	return $table_html;
}








/*
DEPARTMENT LIST TABLE

data table of departments


*/

function dept_list_table( $id, $search_results)
{
	$status_arr = array(0=>"Inactive", 1=>"Active");
	$table_html = "<div><table name='".$id."' id='".$id."' >
			<thead>
			<tr>
					<th >Department Name</th>
					<th >Code</th>
			</tr>
			</thead>";
	if (count($search_results) == 0) { 
			  
		  }
	else {
		$table_html .= "";
		for ($i = 0; $i < count($search_results); $i++) {
				$x = $i % 2;
				
					$table_html .= "<tr >"; 
					$table_html .= "<td>" . $search_results[$i]["name"] . "</td>";
					$table_html .= "<td>" . $search_results[$i]["dept_code"] . "</td>";
					$table_html .= "</tr>";
			
			}
	}
	$table_html .= "</table></div>"; 
	return $table_html;
}


/*
TYPE LIST TABLE

data table of types


*/

function type_list_table( $id, $search_results)
{
	$status_arr = array(0=>"Inactive", 1=>"Active");
	$table_html = "<table name='".$id."' id='".$id."' >
			<thead>
			<tr>
					<th >Type Name</th>
					<th >Status</th>
			</tr>
			</thead>";
	if (count($search_results) == 0) { 
			  
		  }
	else {
		$table_html .= "";
		for ($i = 0; $i < count($search_results); $i++) {
				$x = $i % 2;
				
					$table_html .= "<tr >"; 
					$table_html .= "<td>" . $search_results[$i]["name"] . "</td>";
					$table_html .= "<td>" . $status_arr[$search_results[$i]["type_active"]] . "</td>";
					$table_html .= "</tr>";
			
			}
	}
	$table_html .= "</table>"; 
	return $table_html;
}



/*
user_id, first_name, last_name, theme in $search_results
returns link 

onclick='set_supervisor(".$row["netid"].");return false;'

*/
function alpha_div( $letter, $search_results)
{
	$div = "<div class='".$letter."' style='display: none'>";
        foreach ($search_results as $row) {
                $div .= "<a href='#' id='". $row["user_id"]."' class='".$letter."' 
						 onclick='document.forms['add'].supervisor.value = '".$row['netid']."'; return false;'>";
				$div .= $row["first_name"] ." ". $row["last_name"]." (" . $row["theme"] . ") ";
                $div .= "</a><br>";
        
        }
	$div .= "</div>";
			
	return $div;
}


	
	
	
	
	

/*
returns dropdown of country names

*/
function country_dropdown( $name )
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

function writeExcel3($search_results, $db, $filename="igb_people.xls") {
	//$filename = "./igb_people.xls";
   
    try {
	$excel= new ExcelWriter("excel/".$filename);
	$myArr=array("<b>Name</b>","<b>Email</b>","<b>Theme</b>","<b>Type</b>","<b>Room Number</b>","<b>Address</b>");
	$excel->writeLine($myArr);
	for ($i = 0; $i < count($search_results); $i++) {
		//$thisuser = new user($dbase, $search_results[$i]['user_id']);
		$line = array($search_results[$i]['first_name'] . " " . $search_results[$i]['last_name'],
					  $search_results[$i]['email'],
					  $search_results[$i]['theme_name'],
					  $search_results[$i]['type_name'],
					  $search_results[$i]['igb_room'],
					  get_address($db, $search_results[$i]['user_id'], "HOME")

					  );
                //echo($line . "<BR>");
		$excel->writeLine($line);
	}
	$excel->close();
	header("Location: excel/". $filename);
    } catch(Exception $e) {
        echo($e);
        echo($e->getTrace());
    }

}
 
 

function write_forwarding_address_report($search_results, $db, $filename) {
    try {
    $headers = array("First Name", "Last Name","Email","Address1","Address2","City","State","Zip","Theme list","End Date","Reason Leaving");

    $excel= new ExcelWriter("excel/".$filename);

    $excel->writeLine($headers);

    for ($i = 0; $i < count($search_results); $i++) {
		//$thisuser = new user($dbase, $search_results[$i]['user_id']);
		$line = array($search_results[$i]['first_name'],
                                        $search_results[$i]['last_name'],
					  $search_results[$i]['email'],
					  $search_results[$i]['address1'],
					  $search_results[$i]['address2'],
					  $search_results[$i]['city'],
                                          $search_results[$i]['state'],
                                          $search_results[$i]['zip'],
                                          $search_results[$i]['theme_list'],
                                          $search_results[$i]['end_date'],
                                          $search_results[$i]['reason_leaving']

					  );

                //echo("<BR>");
		$excel->writeLine($line);
	}
       
        $excel->close();
	header("Location: excel/". $filename);
    } catch(Exception $e) {
        echo($e);
        echo($e->getTrace());
    }
}

function write_database_users_report($search_results, $db, $filename) {
    try {
    $headers = array("First Name", "Last Name", "Netid", "Email", "Themes");
    $excel= new ExcelWriter("excel/".$filename);
    $excel->writeLine($headers);

    for ($i = 0; $i < count($search_results); $i++) {
        if(count($search_results[$i]) == 0) {
            $line = array();
        } else {
		//$thisuser = new user($dbase, $search_results[$i]['user_id']);
		$line = array($search_results[$i]['first_name'],
                                        $search_results[$i]['last_name'],
					  $search_results[$i]['netid'],
					  $search_results[$i]['email'],
                                          $search_results[$i]['theme_list']

					  );
        }
                //echo("<BR>");
		$excel->writeLine($line);
	}
       
        $excel->close();
	header("Location: excel/". $filename);
    } catch(Exception $e) {
        echo($e);
        echo($e->getTrace());
    }
    
}


function get_address($db, $user_id, $type="HOME") {

	$query = "SELECT address1, address2, city, state, zip from address where type = '" . $type . "' AND user_id = '" . $user_id ."'";
	$result = $db->query($query);
	$addr = $result[0]['address1'] . " " . ($result[0]['address2'] != "" ? ($result[0]['address2'] . " ") : "") . "\r\n" . $result[0]['city'] .
		", " . $result[0]['state'] . " " . $result[0]['zip'];
	//echo $addr;
	return $addr;
}

?>
