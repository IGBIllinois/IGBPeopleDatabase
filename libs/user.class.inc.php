<?php
//////////////////////////////////////////
//
//	user.class.inc.php
//
//
//	By Crystal Ahn
//	April 2011
//
//////////////////////////////////////////



class user{

	////////////////Private Variables//////////

	private $db; //mysql database object
	private $id;
	private $email; 	
	private $name;
	private $first_name;	
	private $last_name;
	private $netid;
	private $uin;
	private $igb_room;
	private $igb_phone;
        private $igb_address;
        private $igb_city;
        private $igb_state;
        private $igb_zip;
	private $dept_phone;
	private $cell_phone;
	private $fax;
	private $department;
	private $dept_address;
	private $dept_address2;
	private $dept_city;
	private $dept_state;
	private $dept_zip;
	private $home_address;
	private $home_address2;
	private $home_city;
	private $home_state;
	private $home_zip;
	private $default_address;
	private $gender;
	private $key_deposit;
	private $prox_card;
	private $training;
	private $supervisor_id;
        private $supervisor_netid;
        private $supervisor_name;
	private $start_date;
	private $end_date;
	private $themes;
	//private $theme_1;
	//private $theme_2;
	private $type;
	private $type_1;
	private $type_2;
	private $admin;
        private $superadmin;
	private $reason_leaving;
        private $image;
        private $pager;
        
	//private $imageDir = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF'] .  "/images/users/";
	//////////////// Variables//////////

	

	////////////////Public Functions///////////

	public function __construct($db, $user_id = 0) {
		$this->db = $db;
		if ($user_id != 0) {
				$this->load($user_id);
		}

	}
	public function __destruct() {

	}
	
	
/*
load user id
*/

	public function load($user_id) {
        $this->id = $user_id;
        $this->get_user_id();
    }
	
/*
load user values
*/
	
	public function get_user($user_id) { 
		$profile_query = "SELECT users.*, themes.short_name as theme_name, type.name as type_name, department.name as dept_name
						 FROM (((users LEFT JOIN themes ON users.theme_id=themes.theme_id)
						 LEFT JOIN type ON users.type_id=type.type_id) 
						 LEFT JOIN department ON users.dept_id=department.dept_id)
						 WHERE user_id = '".$user_id."'";
		$result = $this->db->query($profile_query);
		//$theme_1_query = "SELECT themes.short_name as theme_1_name FROM users 
		//				LEFT JOIN themes ON users.theme_1_id=themes.theme_id
		//				WHERE user_id = '".$user_id."'";
		//$theme_1_result = $this->db->query($theme_1_query);
		//$theme_2_query = "SELECT themes.short_name as theme_2_name FROM users 
		//				LEFT JOIN themes ON users.theme_2_id=themes.theme_id
		//				WHERE user_id = '".$user_id."'";
		//$theme_2_result = $this->db->query($theme_2_query);
		//$type_1_query = "SELECT type.name as type_1_name FROM users 
		//				LEFT JOIN type ON users.type_1_id=type.type_id
		//				WHERE user_id = '".$user_id."'";
		//$type_1_result = $this->db->query($type_1_query);
		//$type_2_query = "SELECT type.name as type_2_name FROM users 
		//				LEFT JOIN type ON users.type_2_id=type.type_id
		//				WHERE user_id = '".$user_id."'";
		//$type_2_result = $this->db->query($type_2_query);
		$address_query = "SELECT * FROM address 
						WHERE user_id = '".$user_id."'";
		$address_list = $this->db->query($address_query);
		$phone_query = "SELECT * FROM phone 
						WHERE user_id = '".$user_id."'";
		$phone_list = $this->db->query($phone_query);
		$supervisor_query = "SELECT first_name, last_name, netid FROM users 
						WHERE user_id = ".$result[0]['supervisor_id']."";
                //echo("supervisor_query = ".$supervisor_query."<BR>");
		$supervisor_result = $this->db->query($supervisor_query);
		
		$this->status = $result[0]['user_enabled'];	
		$this->name = $result[0]['first_name']." ".$result[0]['last_name'];		
		$this->first_name = $result[0]['first_name'];
		$this->last_name = $result[0]['last_name'];
		$this->email = $result[0]['email'];
		$this->uin = $result[0]['uin']; 
		$this->netid = $result[0]['netid']; 
		$this->gender = $result[0]['gender'];
		$this->start_date = $result[0]['start_date'];
		$this->end_date = $result[0]['end_date'];
		$this->supervisor = $result[0]['supervisor_id'];
		$this->supervisor_netid = $supervisor_result[0]['netid'];
		$this->supervisor_name = $supervisor_result[0]['first_name']." ".$supervisor_result[0]['last_name'];
                //echo("supervisor_name = ".$this->supervisor_name."<BR>");
		//$this->theme = $result[0]['theme_name'];
		//$this->theme_1 = $theme_1_result[0]['theme_1_name'];
		//$this->theme_2 = $theme_2_result[0]['theme_2_name'];
		//$this->theme_id = $result[0]['theme_id'];
		//$this->theme_1_id = $result[0]['theme_1_id'];
		//$this->theme_2_id = $result[0]['theme_2_id'];
		//$this->type_id = $result[0]['type_id'];
		//$this->type_1_id = $result[0]['type_1_id'];
		//$this->type_2_id = $result[0]['type_2_id'];
		//$this->other_theme = $other_theme[0]['theme_name'];
		//$this->type = $result[0]['type_name'];
		//$this->type_1 = $type_1_result[0]['type_1_name'];
		//$this->type_2 = $type_2_result[0]['type_2_name'];		
		$this->department = $result[0]['dept_name'];
		$this->dept_id = $result[0]['dept_id'];
		$this->igb_phone = $phone_list[0]['igb'];
		$this->cell_phone = $phone_list[0]['cell'];
		$this->dept_phone = $phone_list[0]['dept'];
		$this->other_phone = $phone_list[0]['other'];
		$this->fax = $phone_list[0]['fax'];
		$this->default_address = $result[0]['default_address'];	
		
		$this->key_deposit = $result[0]['key_deposit'];
		$this->prox_card = $result[0]['prox_card']; 
		$this->training = $result[0]['safety_training']; 
		$this->admin = $result[0]['admin'];
                $this->superadmin = $result[0]['superadmin']; 
                $this->image = $result[0]['image_location'];
		
		if($this->status == 0) {
			$this->reason_leaving = $result[0]['reason_leaving'];
		}
		
		$i=0;
		while( $i < count($address_list))
    	{
			if ($address_list[$i]['type'] == 'IGB'){
                                $this->igb_address = $address_list[$i]['address1'];
				$this->igb_room= $address_list[$i]['address2'];
				$this->igb_acsz= $address_list[$i]['address1']." ".$address_list[$i]['city'].", ".
								 $address_list[$i]['state']." ".$address_list[$i]['zip'];
                                $this->igb_city = $address_list[$i]['city'];
                                $this->igb_state = $address_list[$i]['state'];
                                $this->igb_zip = $address_list[$i]['zip'];
			}
			else if ($address_list[$i]['type'] == 'DEPT'){
				$this->dept_address1= $address_list[$i]['address1'];
				$this->dept_address2= $address_list[$i]['address2'];
				$this->dept_city= $address_list[$i]['city'];
				$this->dept_state= $address_list[$i]['state'];
				$this->dept_zip= $address_list[$i]['zip'];
			}
			else if ($address_list[$i]['type'] == 'HOME' && $address_list[$i]['forward'] == '0' ){
				$this->home_address1= $address_list[$i]['address1'];
				$this->home_address2= $address_list[$i]['address2'];
				$this->home_city= $address_list[$i]['city'];
				$this->home_state= $address_list[$i]['state'];
				$this->home_zip= $address_list[$i]['zip'];
			}
			else if ($address_list[$i]['type'] == 'OTHER'){
				$this->other_address =  $this->address_html($address_list[$i]);
			}
			$i ++;
		}
		return $result;
	}
/*
get functions
returns values of specific user
*/	

	//user info
	public function get_user_id() { return $this->id; }
	public function get_netid() { return $this->netid; }
	public function get_email() { return $this->email; }
	public function get_name() { return $this->name; }
	public function get_first_name() { return $this->first_name; }
	public function get_last_name() { return $this->last_name; }
	public function get_uin() { return $this->uin; }
	public function get_gender() { return $this->gender; }
	public function get_start_date() { return $this->start_date; }
	public function get_end_date() { return $this->end_date; }
	public function get_reason_leaving() {
		if(!$this->get_status()) {
			return $this->reason_leaving;
		}
	}
        
        public function get_image() { 
            //echo("image = ".$this->image);
            if($this->image != null) {
                return $this->image; 
            } else {
                return "default.png";
            }
        }
        public function get_image_location() { 
            if($this->image != null) {
                           return  "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) .  "/images/users/" . $this->image; 
                          } else {
                           return  "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) .  "/images/users/" . "default.png";   
                          }
        }
        
        public function get_large_image() { 
            //echo("image = ".$this->image);
            
            if($this->image != null) {
                $large_image = $this->image;
                $large_image = str_replace(".", "_large.", $large_image);
                return $large_image; 
            } else {
                return "default.png";
            }
        }
        public function get_large_image_location() { 
            
            if($this->image != null) {
                $large_image = $this->image;
                $large_image = str_replace(".", "_large.", $large_image);
                return  "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) .  "/images/users/" . $large_image; 
            } else {
                return  "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) .  "/images/users/" . "default.png";   
            }
        }
	
	//address
	public function get_igb_address() { return $this->igb_address; }
	//public function get_igb_address1() { return $this->igb_address1; }
        public function get_igb_city() { return $this->igb_city; }
        public function get_igb_state() { return $this->igb_state; }
        public function get_igb_zip() { return $this->igb_zip; }
	public function get_igb_room() { return $this->igb_room; }		
	public function get_igb_phone() { return $this->igb_phone; }	
	public function get_igb_acsz() { return $this->igb_acsz; }
	public function get_home_address1() { return $this->home_address1; }
	public function get_home_address2() { return $this->home_address2; }
	public function get_home_city() { return $this->home_city; }
	public function get_home_state() { return $this->home_state; }
	public function get_home_zip() { return $this->home_zip; }
	public function get_other_address() { return $this->other_address; }
	public function get_default_address() { return $this->default_address; }
	
	//department
	public function get_dept_id() { return $this->dept_id; }
	public function get_dept() { return $this->department; }
	public function get_dept_address1() { return $this->dept_address1; }
	public function get_dept_address2() { return $this->dept_address2; }
	public function get_dept_city() { return $this->dept_city; }
	public function get_dept_state() { return $this->dept_state; }
	public function get_dept_zip() { return $this->dept_zip; }
	
	//theme & type
	public function get_theme() { return $this->theme; }
	public function get_theme_1() { return $this->theme_1; }
	public function get_theme_2() { return $this->theme_2; }
	public function get_type() { return $this->type; }
	public function get_type_1() { return $this->type_1; }
	public function get_type_2() { return $this->type_2; }
	public function get_theme_id() { return $this->theme_id; }
	public function get_theme_1_id() { return $this->theme_1_id; }
	public function get_theme_2_id() { return $this->theme_2_id; }
	public function get_type_id() { return $this->type_id; }
	public function get_type_1_id() { return $this->type_1_id; }
	public function get_type_2_id() { return $this->type_2_id; }
	
	//supervisor
	public function get_supervisor() { return $this->supervisor; }
	public function get_supervisor_netid() { return $this->supervisor_netid; }
	public function get_supervisor_name() { return $this->supervisor_name; }
	
	//phones
	public function get_cell_phone() { return $this->cell_phone; }
	public function get_other_phone() { return $this->other_phone; }
	public function get_dept_phone() { return $this->dept_phone; }
	public function get_fax() { return $this->fax; }
	
	//booleans
	public function get_status() { return $this->status; }
	public function get_key_deposit() { return $this->key_deposit; }
	public function get_prox_card() { return $this->prox_card; }
	public function get_training() { return $this->training; }
	public function get_admin() { return $this->admin; }
        public function get_superadmin() { return $this->superadmin; }
        
        public function get_db() { return $this->db; }
	
	
/*
get_keyid
returns result of keys in posession of given user
*/	
public function get_key_id($key_room) { 
		$key_query = "SELECT key_id
						 FROM key_list
						 WHERE key_room = '".$key_room."'";
		$result = $this->db->query($key_query);	
		return $result;
		
	
}
	
	
/*
get_keys
returns result of keys in posession of given user
*/	
public function get_keys($user_id, $active) { 
		$key_query = "SELECT keyinfo_id, key_room, date_issued, date_returned, return_condition,
							 paid, payment_returned
						 FROM key_info LEFT JOIN key_list
						 ON key_info.key_id = key_list.key_id 
						 WHERE user_id = '".$user_id."' 
						 AND key_info.key_active = '".$active."' 
						 ORDER BY date_issued";
		$result = $this->db->query($key_query);	
		return $result;
		
	
}


/*
key_exists
*/	
public function key_exists($user_id, $key_id) { 
		$key_query = "SELECT keyinfo_id
						FROM key_info =
					    WHERE user_id = '".$user_id."' 
					    AND key_active = '1' 
					    AND key_id = '".$key_id."' ";
		$result = $this->db->query($key_query);	
		return $result;
		
	
}
	
	
	
/*
num_rows
returns number of rows where $field = $value in $table
*/		
	public function num_rows($field, $value, $table, $conditional) {
		$exists_query = "SELECT count(*) as count FROM ".$table." WHERE ".$field." = '".$value."'";
		$exists_query .= $conditional;
		$result = $this->db->query($exists_query);
		return $result[0]['count'];
    }
	
	
		
/*
num_rows_adv
returns number of rows in advanced search result
*/		
	public function num_rows_adv($active, $value, $filters = 0) {
		$search_arr = explode(" ",$value);
		$search_query = "SELECT count(1) as count
						 FROM (((users LEFT JOIN themes ON users.theme_id=themes.theme_id)
						 LEFT JOIN type ON users.type_id=type.type_id) 
						 LEFT JOIN address ON users.user_id=address.user_id AND address.type='IGB') 
						 LEFT JOIN phone ON users.user_id=phone.user_id 
						 
						 WHERE";
		$i=0;
		while( $i < count($search_arr))
    	{
			$search_query.=  " ( first_name LIKE '%".$search_arr[$i]."%'
								OR last_name LIKE '%".$search_arr[$i]."%'
								OR netid LIKE '%".$search_arr[$i]."%'								
								OR uin LIKE '%".$search_arr[$i]."%'
								OR email LIKE '%".$search_arr[$i]."%'
								)";
			$i ++;
			if ($i != count($search_arr)){
				$search_query.= " AND ";
			}
		}
		
		$i=0;		
		$array_keys = array_keys($filters);
		
		while( $i < count($filters))
    	{
			if (!empty($filters[$array_keys[$i]])){
				$search_query.=  " AND ".$array_keys[$i]." = '".$filters[$array_keys[$i]]."'";
			}
			$i ++;
			
		}
		$search_query.=  " AND user_enabled = '".$active."'";
		$result = $this->db->query($search_query);
		return $result[0]['count'];

    }
	
	
	
	
	
	
	
/*
user_exists
searches to see if exact value already exists in specified field in user table
returns user_id in which it exists
*/		
	public function user_exists($field, $value, $conditional = NULL) {
		$exists_query = "SELECT user_id FROM users WHERE ".$field." = '".$value."' ";
		$exists_query .= $conditional;
               // echo("<BR>Exists query = $exists_query<BR>");
		$result = $this->db->query($exists_query);
		return $result[0]['user_id'];
    }
	

			
/*
adv_search
advanced search query
searches values inputed into generic text input and checks to see if those values exist in following fields
limits results to include only the matching results to advanced search query

first_name
last_name
netid
uin
email

param $filters = array with theme_id, type_id, dept_id, igb_room, igb_phone, home address

*/	
public function adv_search($active, $value, $user_id=null, $filters=0, $theme_id=0, $type_id=0, $start_date=0, $end_date=0, $supervisor="") {
    $search_arr = explode(" ",$value);
    if($theme_id != 0) {
        $theme_query = " RIGHT JOIN themes on user_theme.theme_id=themes.theme_id and themes.theme_id='".$theme_id."' or user_theme.theme_id=0 and user_theme.active=1";
    } else {
        // Only search themes the user has permissions for.
        if($user_id != null && !$this->is_admin($_SESSION['username'])) {
                   //select users.netid, themes.short_name, type.name from users left join user_theme on (user_theme.user_id = users.user_id and user_theme.theme_id=1) left join themes on (user_theme.theme_id = themes.theme_id) left join type on (user_theme.type_id = type.type_id)
                    $themelist = $this->get_permissions($user_id);
                    $theme_query = " RIGHT JOIN themes on (user_theme.theme_id=themes.theme_id or user_theme.theme_id=0 and user_theme.active=1 and (";
                    for($i=0; $i<count($themelist); $i++) {
                        $theme_id = $themelist[$i]['theme_id'];
                        $theme_query .= " ( themes.theme_id = '". $theme_id. "') ";
                        if($i < count($themelist)-1) {
                            $theme_query .= " OR ";
                        }
                    }
                    $theme_query .= " )) ";
                    if(!$active) {
                        $theme_query .= "or user_theme.theme_id=0";
                    }
                    //$theme_query = " AND ". $theme_query;
                    //echo("theme query = $theme_query<BR>");
		
                } else {
                    $theme_query = " RIGHT JOIN themes on (user_theme.theme_id=themes.theme_id or user_theme.theme_id=0 and user_theme.active=1) ";
                    if(!$active) {
                        $theme_query .= "or user_theme.theme_id=0";
                    }
                }
    }
    
    if($type_id != 0) {
        $type_query = " RIGHT JOIN type on (user_theme.type_id=type.type_id and type.type_id='".$type_id."' ";
         if($theme_id != 0) {
             $type_query .= " and user_theme.theme_id='".$theme_id."' ";
         }
         $type_query .= " and active=1 )";
    } else {
        $type_query = " RIGHT JOIN type on (user_theme.type_id=type.type_id) ";
    }
    $search_query = "SELECT (users.user_id) as user_id, first_name, last_name, netid, uin, email, default_address, ".
			" themes.short_name as theme_name, type.name as type_name, ".
                        " address.address2 as igb_room from user_theme 
                        LEFT JOIN address ON user_theme.user_id=address.user_id AND address.type='IGB' 
			LEFT JOIN phone ON user_theme.user_id=phone.user_id
                        LEFT JOIN users on users.user_id=user_theme.user_id " .
                        $theme_query . " ". $type_query;
                        
    
    $search_query .= " WHERE ";
		$i=0;
		while( $i < count($search_arr))
    	{
			$search_query.=  " ( first_name LIKE '%".$search_arr[$i]."%'
								OR last_name LIKE '%".$search_arr[$i]."%'
								OR netid LIKE '%".$search_arr[$i]."%'								
								OR uin LIKE '%".$search_arr[$i]."%'
								OR email LIKE '%".$search_arr[$i]."%'".
                                                                (($supervisor != "") ? (" AND supervisor_id=(SELECT user_id from users where netid='". $supervisor."') ") : "")."
								) ";
			$i ++;
			if ($i != count($search_arr)){
				$search_query.= " AND ";
			}
		}
		
		$i=0;		
		$array_keys = array_keys($filters);
		
		while( $i < count($filters))
                {
			if (!empty($filters[$array_keys[$i]][0])){
				$search_query.= $filters[$array_keys[$i]][1] ." ".$array_keys[$i]." = '".$filters[$array_keys[$i]][0]."' ";
			}
			$i ++;
			
		}
		if($active < 2) { // 0 = disabled, 1 = abled, 2 = all
			$search_query.=  " AND user_enabled = '".$active."'";
		}
		if($start_date != 0 && $end_date != 0) {
			$search_query .= " AND users.start_date BETWEEN '$start_date' AND '$end_date' ";
		} else if($start_date != 0) {
			$search_query .= " AND users.start_date >= '$start_date' ";
		} else if($end_date != 0) {
			$search_query .= " AND users.start_date <= '$end_date' ";
		}
                
                //$search_query .= $theme_query;
		$search_query.=  " group by user_id ORDER BY users.last_name ";
		//$search_query.=  " LIMIT " .$skip.", 20";
		//echo("my adv search query = $search_query<BR>");
		$result = $this->db->query($search_query);
		return $result;
}
    
    
    public function adv_search_old($active, $value, $user_id=null, $filters = 0, $start_date=0, $end_date=0, $theme_id=0, $type_id=0) { //$page, 
		//$skip = ($page - 1) * 20;
		$search_arr = explode(" ",$value);
		$search_query = "SELECT users.user_id as user_id, first_name, last_name, netid, uin, email, default_address, 
						 themes.short_name as theme_name, type.name as type_name, address.address2 as igb_room
						 
						 FROM (users 
						 
						 LEFT JOIN address ON users.user_id=address.user_id AND address.type='IGB') 
						 LEFT JOIN phone ON users.user_id=phone.user_id";
                
                
		// If they selected a theme, only search that theme. Otherwise search all themes they have permissions for.
                $theme_query = "";
                if($theme_id != 0) {
                   $theme_query = " LEFT JOIN user_theme on ( ( user_theme.user_id = users.theme_id and user_theme.theme_id = '". $theme_id. "') )";
                } else {
                if($user_id != null && !$this->is_admin($_SESSION['username'])) {
                   //select users.netid, themes.short_name, type.name from users left join user_theme on (user_theme.user_id = users.user_id and user_theme.theme_id=1) left join themes on (user_theme.theme_id = themes.theme_id) left join type on (user_theme.type_id = type.type_id)
                    $themelist = $this->get_permissions($user_id);
                    $theme_query = "LEFT JOIN user_theme on ( ";
                    for($i=0; $i<count($themelist); $i++) {
                        $theme_id = $themelist[$i]['theme_id'];
                        $theme_query .= " ( user_theme.theme_id = users.theme_id and user_theme.theme_id = '". $theme_id. "') ";
                        if($i < count($themelist)-1) {
                            $theme_query .= " OR ";
                        }
                    }
                    $theme_query .= " ) ";
                    //$theme_query = " AND ". $theme_query;
                    //echo("theme query = $theme_query<BR>");
		
                }
                }
                
                if($type_id != 0) {
                    $type_query = " LEFT JOIN user_theme on (user_theme.type_id = type.type_id and type.type_id = '". $type_id . "') ";
                    $search_query .= $type_query;
                }
                
                
                $search_query .= $theme_query;
                
		$search_query .= " WHERE";
		$i=0;
		while( $i < count($search_arr))
    	{
			$search_query.=  " ( first_name LIKE '%".$search_arr[$i]."%'
								OR last_name LIKE '%".$search_arr[$i]."%'
								OR netid LIKE '%".$search_arr[$i]."%'								
								OR uin LIKE '%".$search_arr[$i]."%'
								OR email LIKE '%".$search_arr[$i]."%'
								) ";
			$i ++;
			if ($i != count($search_arr)){
				$search_query.= " AND ";
			}
		}
		
		$i=0;		
		$array_keys = array_keys($filters);
		
		while( $i < count($filters))
                {
			if (!empty($filters[$array_keys[$i]][0])){
				$search_query.= $filters[$array_keys[$i]][1] ." ".$array_keys[$i]." = '".$filters[$array_keys[$i]][0]."' ";
			}
			$i ++;
			
		}
		if($active < 2) { // 0 = disabled, 1 = abled, 2 = all
			$search_query.=  " AND user_enabled = '".$active."'";
		}
		if($start_date != 0 && $end_date != 0) {
			$search_query .= " AND users.start_date BETWEEN '$start_date' AND '$end_date' ";
		} else if($start_date != 0) {
			$search_query .= " AND users.start_date >= '$start_date' ";
		} else if($end_date != 0) {
			$search_query .= " AND users.start_date <= '$end_date' ";
		}
                /*
                $theme_query = "";
                if($user_id != null && !$this->is_admin($_SESSION['username'])) {
                   //select users.netid, themes.short_name, type.name from users left join user_theme on (user_theme.user_id = users.user_id and user_theme.theme_id=1) left join themes on (user_theme.theme_id = themes.theme_id) left join type on (user_theme.type_id = type.type_id)
                    $themelist = $this->get_permissions($user_id);
                    $theme_query = "LEFT JOIN user_theme on ( ";
                    for($i=0; $i<count($themelist); $i++) {
                        $theme_id = $themelist[$i]['theme_id'];
                        $theme_query .= " ( user_theme.user_id = users.theme_id and user_theme.theme_id = '". $theme_id. "') ";
                        if($i < count($themelist)-1) {
                            $theme_query .= " OR ";
                        }
                    }
                    $theme_query .= " ) ";
                    //$theme_query = " AND ". $theme_query;
                    echo("theme query = $theme_query<BR>");
		
                }
                 * */
                 
                //$search_query .= $theme_query;
		$search_query.=  " ORDER BY users.last_name ";
		//$search_query.=  " LIMIT " .$skip.", 20";
		//echo("my adv search query = $search_query<BR>");
		$result = $this->db->query($search_query);
		return $result;
    }
	

/*
search
simple search query
searches values inputed into generic text input and checks to see if those values exist in specified field
*/
	
	public function search($filters) {
		$search_query = "SELECT users.user_id as user_id, first_name, last_name, netid, uin, email, default_address, 
						 themes.short_name as theme_name, type.name as type_name, address.address2 as igb_room
						 FROM (((users LEFT JOIN themes ON users.theme_id=themes.theme_id)
						 LEFT JOIN type ON users.type_id=type.type_id) 
						 LEFT JOIN address ON users.user_id=address.user_id AND address.type='IGB') 
						 LEFT JOIN phone ON users.user_id=phone.user_id ";		
		$i=0;	
                
		$array_keys = array_keys($filters);
		
		while( $i < count($filters))
    	{
			if (!empty($filters[$array_keys[$i]][0])){
				$search_query.= $filters[$array_keys[$i]][1] ." ".$array_keys[$i]." = '".$filters[$array_keys[$i]][0]."' ";
			}
			$i ++;
			
		}
		
		$search_query.=  " ORDER BY users.last_name ";				
		
		//echo("search query = $search_query<BR>");
		$result = $this->db->query($search_query);
		return $result;
    }
    
    
       // get all users of a specific type in a group
    //
    // @param $group_id: The id number of the theme
    // @param $types: An array of type ids
    public function get_users_by_type($theme_id, $types, $status=1, $supervisor=-1) {
        if($theme_id==0) {
            // if theme_id == 0, get users who have no theme.
            $type_id = $types[0];
            //SELECT distinct (users.user_id) as user_id, first_name, last_name, netid, uin, email, default_address, address.address2 as igb_room from user_theme LEFT JOIN address ON user_theme.user_id=address.user_id AND address.type='IGB' LEFT JOIN phone ON user_theme.user_id=phone.user_id LEFT JOIN users on users.user_id=user_theme.user_id right join (select * from (select * from user_theme where ((end_date is not null and end_date != 0) or theme_id = 0) and active=1) ut1 where user_id not in (select user_id from user_theme where ((end_date is null or end_date = 0) and theme_id != 0) and active=1) order by user_id) ut2 on ut2.user_id=user_theme.user_id
        /*    $search_query = "SELECT (users.user_id) as user_id, first_name, last_name, netid, uin, email, default_address, ".
			" themes.short_name as theme_name, type.name as type_name, ".
                        " address.address2 as igb_room from user_theme 
                        LEFT JOIN address ON user_theme.user_id=address.user_id AND address.type='IGB' 
			LEFT JOIN phone ON user_theme.user_id=phone.user_id
                        LEFT JOIN users on users.user_id=user_theme.user_id " .
                    " RIGHT JOIN type on user_theme.type_id=type.type_id and type.type_id='".$type_id."' and user_theme.active=1  RIGHT JOIN themes on user_theme.theme_id=themes.theme_id and user_theme.active=1 ".
                    //"  right join (select * from (select * from user_theme where end_date is not null and end_date != 0 and active=1) ut1 where user_id not in (select user_id from user_theme where end_date is null or end_date = 0 and active=1) order by user_id) ut2 on users.user_id=ut2.user_id";
                    " right join user_id on (select * from (select * from user_theme where ((end_date is not null and end_date != 0) or theme_id = 0) and active=1) ut1 where user_id not in (select user_id from user_theme where ((end_date is null or end_date = 0) and theme_id != 0) and active=1) order by user_id)  order by user_id";
        */
            $search_query = "SELECT distinct (users.user_id) as user_id, first_name, last_name, netid, uin, email, default_address, address.address2 as igb_room from user_theme LEFT JOIN address ON user_theme.user_id=address.user_id AND address.type='IGB' LEFT JOIN phone ON user_theme.user_id=phone.user_id LEFT JOIN users on users.user_id=user_theme.user_id right join (select * from (select * from user_theme where ((end_date is not null and end_date != 0) or theme_id = 0) and active=1) ut1 where user_id not in (select user_id from user_theme where ((end_date is null or end_date = 0) and theme_id != 0) and active=1) order by user_id) ut2 on ut2.user_id=user_theme.user_id order by users.last_name";

        } else {
        //select * from users where user_enabled=1 and users.user_id in (select distinct user_id from user_theme where user_theme.end_date != 0 and user_theme.end_date is not null) and users.user_id not in (select distinct user_id from user_theme where (user_theme.end_date is null or user_theme.end_date=0))
         if($theme_id > 0) {
            $theme_query = " RIGHT JOIN themes on user_theme.theme_id=themes.theme_id and themes.theme_id='".$theme_id."' and user_theme.active=1";
        } else {
            // Only search themes the user has permissions for.
            if(supervisor_id > 0 || ($user_id != null && !$this->is_admin($_SESSION['username']))) {
                       //select users.netid, themes.short_name, type.name from users left join user_theme on (user_theme.user_id = users.user_id and user_theme.theme_id=1) left join themes on (user_theme.theme_id = themes.theme_id) left join type on (user_theme.type_id = type.type_id)
                        $themelist = $this->get_permissions($user_id);
                        $theme_query = " RIGHT JOIN themes on user_theme.theme_id=themes.theme_id and user_theme.active=1 and (";
                        for($i=0; $i<count($themelist); $i++) {
                            $theme_id = $themelist[$i]['theme_id'];
                            $theme_query .= " ( themes.theme_id = '". $theme_id. "') ";
                            if($i < count($themelist)-1) {
                                $theme_query .= " OR ";
                            }
                        }
                        $theme_query .= " ) ";
                        //$theme_query = " AND ". $theme_query;
                        //echo("theme query = $theme_query<BR>");

            } else {
                $theme_query = " RIGHT JOIN themes on user_theme.theme_id=themes.theme_id and user_theme.active=1 ";
            }
        }
        if($types != null) {
        $type_query = "RIGHT JOIN type on (";
        for($i=0; $i<count($types); $i++) {
            $type_id = $types[$i];
            if($type_id != 0) {
            $type_query .= " (user_theme.type_id=type.type_id and type.type_id='".$type_id."' ";
             if($theme_id > 0) {
                 $type_query .= " and user_theme.theme_id='".$theme_id."' ";
             }
             $type_query .= " and active=1 )";
            } else {
                $type_query .= " (user_theme.type_id=type.type_id and type.type_id='".$type_id."' and user_theme.theme_id='".$theme_id."' and active=1 )  ";
            }
            if($i < count($types)-1) {
                $type_query .= " OR ";
            }
        }
        $type_query .= ") ";
        } else {
            $type_query = " RIGHT JOIN type on user_theme.type_id=type.type_id and user_theme.active=1 ";
        }
        
        
        $search_query = "SELECT (users.user_id) as user_id, first_name, last_name, netid, uin, email, default_address, ".
			" themes.short_name as theme_name, type.name as type_name, ".
                        " address.address2 as igb_room from user_theme 
                        LEFT JOIN address ON user_theme.user_id=address.user_id AND address.type='IGB' 
			LEFT JOIN phone ON user_theme.user_id=phone.user_id
                        LEFT JOIN users on users.user_id=user_theme.user_id " .
                        $theme_query . " ". $type_query;
        
        //if($theme_id=="0") {
        //    $search_query .= " WHERE (((users.theme_id='0' AND users.theme_1_id='0' AND users.theme_2_id='0') AND ".
        //                        "(users.type_id='".$types[0]."' OR users.type_1_id = '".$types[0]."' OR users.type_2_id='".$types[0]."')) ";
            
            
        //    for($i=1; $i<count($types); $i++) {

        //        $search_query .= " OR ((users.theme_id='0' AND users.theme_1_id='0' AND users.theme_2_id='0') AND ".
        //                        "(users.type_id='".$types[$i]."' OR users.type_1_id = '".$types[$i]."' OR users.type_2_id='".$types[$i]."')) ";
                
                //$search_query .= " OR ((users.theme_id = '".$theme_id."' AND users.type_id='".$types[$i]."') AND"
                //        . "(users.theme_1_id = '".$theme_id."' AND users.type_1_id='".$types[$i]."') AND"
                //        . "(users.theme_2_id = '".$theme_id."' AND users.type_2_id='".$types[$i]."') ";

        //    }
            
        //} else {
            //$search_query .= " WHERE (users.theme_id='".$theme_id."' OR users.theme_1_id = '".$theme_id."' OR users.theme_2_id='".$theme_id."') AND (";
        
        //    $search_query .= " WHERE (((users.theme_id = '".$theme_id."' AND users.type_id='".$types[0]."') OR"
        //            . "(users.theme_1_id = '".$theme_id."' AND users.type_1_id='".$types[0]."') OR"
        //            . "(users.theme_2_id = '".$theme_id."' AND users.type_2_id='".$types[0]."')) ";
        
       //     for($i=1; $i<count($types); $i++) {

       //         $search_query .= " OR ((users.theme_id = '".$theme_id."' AND users.type_id='".$types[$i]."') OR"
       //                 . "(users.theme_1_id = '".$theme_id."' AND users.type_1_id='".$types[$i]."') OR"
       //                 . "(users.theme_2_id = '".$theme_id."' AND users.type_2_id='".$types[$i]."')) ";

        //    }
        //}
        if($status) {
            $search_query .=  " WHERE (users.user_enabled = ".$status.") ";
        } else {
            $search_query .=  ") WHERE (users.user_enabled = ".$status.") ";
        }
        if($supervisor > 0) {
            $search_query .= " AND (users.supervisor_id=$supervisor) ";
        }
        $search_query.=  " ORDER BY users.last_name ";	
        }
        //echo("new get_users_by_type query = $search_query"."<BR>");
        $result = $this->db->query($search_query);
	return $result;
    }

    
    // get all users of a specific type in a group
    //
    // @param $group_id: The id number of the theme
    // @param $types: An array of type ids
    public function get_users_by_type2($theme_id, $types, $status=1) {
        $search_query = "SELECT users.user_id as user_id, first_name, last_name, netid, uin, email, default_address, 
						 themes.short_name as theme_name, type.name as type_name, address.address2 as igb_room
						 FROM (((users LEFT JOIN themes ON users.theme_id=themes.theme_id)						 
                                                 LEFT JOIN type ON users.type_id=type.type_id) 
						 LEFT JOIN address ON users.user_id=address.user_id AND address.type='IGB') 
						 LEFT JOIN phone ON users.user_id=phone.user_id ";
        
        if($theme_id=="0") {
            $search_query .= " WHERE (((users.theme_id='0' AND users.theme_1_id='0' AND users.theme_2_id='0') AND ".
                                "(users.type_id='".$types[0]."' OR users.type_1_id = '".$types[0]."' OR users.type_2_id='".$types[0]."')) ";
            
            
            for($i=1; $i<count($types); $i++) {

                $search_query .= " OR ((users.theme_id='0' AND users.theme_1_id='0' AND users.theme_2_id='0') AND ".
                                "(users.type_id='".$types[$i]."' OR users.type_1_id = '".$types[$i]."' OR users.type_2_id='".$types[$i]."')) ";
                
                //$search_query .= " OR ((users.theme_id = '".$theme_id."' AND users.type_id='".$types[$i]."') AND"
                //        . "(users.theme_1_id = '".$theme_id."' AND users.type_1_id='".$types[$i]."') AND"
                //        . "(users.theme_2_id = '".$theme_id."' AND users.type_2_id='".$types[$i]."') ";

            }
            
        } else {
            //$search_query .= " WHERE (users.theme_id='".$theme_id."' OR users.theme_1_id = '".$theme_id."' OR users.theme_2_id='".$theme_id."') AND (";
        
            $search_query .= " WHERE (((users.theme_id = '".$theme_id."' AND users.type_id='".$types[0]."') OR"
                    . "(users.theme_1_id = '".$theme_id."' AND users.type_1_id='".$types[0]."') OR"
                    . "(users.theme_2_id = '".$theme_id."' AND users.type_2_id='".$types[0]."')) ";
        
            for($i=1; $i<count($types); $i++) {

                $search_query .= " OR ((users.theme_id = '".$theme_id."' AND users.type_id='".$types[$i]."') OR"
                        . "(users.theme_1_id = '".$theme_id."' AND users.type_1_id='".$types[$i]."') OR"
                        . "(users.theme_2_id = '".$theme_id."' AND users.type_2_id='".$types[$i]."')) ";

            }
        }
        if($status) {
            $search_query .=  " AND (users.user_enabled = ".$status.") ";
        } else {
            $search_query .=  ") OR (users.user_enabled = ".$status.") ";
        }
        $search_query.=  ") ORDER BY users.last_name ";	
        //echo("new search_query = $search_query"."<BR>");
        $result = $this->db->query($search_query);
	return $result;
    }
	
		
/*
alpha_search
simple search query
searches values inputed into generic text input and checks to see if those values exist in specified field
*/
	
	public function alpha_search($letter, $user_id=null, $isSupervisor=0) {
		$alpha_query = "SELECT (users.user_id) as user_id, first_name, last_name, netid, uin, email, default_address, ".
			" themes.short_name as theme_name, type.name as type_name, ".
                        " address.address2 as igb_room from user_theme 
                        LEFT JOIN address ON user_theme.user_id=address.user_id AND address.type='IGB' 
			LEFT JOIN phone ON user_theme.user_id=phone.user_id
                        LEFT JOIN users on users.user_id=user_theme.user_id " .
                        "RIGHT JOIN themes on user_theme.theme_id = themes.theme_id  
                         RIGHT JOIN type on user_theme.type_id = type.type_id ".
			" WHERE SUBSTR(last_name,1,1) = '".$letter."'";
		if ($isSupervisor==1){
			//$alpha_query.=  " AND (users.type_id = 1 or users.type_id = 2 or users.type_id = 4 
			//					   or users.type_id = 5 or users.type_id = 11) ";
		}
                
                if($user_id != null && !$this->is_admin($_SESSION['username'])) {
                    $themelist = $this->get_permissions($user_id);
                    $theme_query = " ( ";
                    for($i=0; $i<count($themelist); $i++) {
                        $theme_id = $themelist[$i]['theme_id'];
                        $theme_query .= " user_theme.theme_id = '". $theme_id. "' ";
                        if($i < count($themelist)-1) {
                            $theme_query .= " OR ";
                        }
                    }
                    $theme_query .= " ) ";
                    $alpha_query .= " AND ". $theme_query;
		
                }
		$alpha_query.=  " GROUP BY user_id ORDER BY users.last_name ";
		//echo("alpha query = $alpha_query <BR>");
		$result = $this->db->query($alpha_query);
		return $result;
    }

    
    public function alpha_search2($letter, $user_id=null, $isSupervisor=0) {
		$alpha_query = "SELECT users.user_id as user_id, first_name, last_name, netid, uin, email,  default_address, 
						 themes.short_name as theme_name, type.name as type_name, address.address2 as igb_room
						 FROM (((users LEFT JOIN themes ON users.theme_id=themes.theme_id)
						 LEFT JOIN type ON users.type_id=type.type_id) 
						 LEFT JOIN address ON users.user_id=address.user_id AND address.type='IGB') 
						 LEFT JOIN phone ON users.user_id=phone.user_id 
						WHERE SUBSTR(last_name,1,1) = '".$letter."'";
		if ($isSupervisor==1){
			$alpha_query.=  " AND (users.type_id = 1 or users.type_id = 2 or users.type_id = 4 
								   or users.type_id = 5 or users.type_id = 11) ";
		}
                
                if($user_id != null && !$this->is_admin($_SESSION['username'])) {
                    $themelist = $this->get_permissions($user_id);
                    $theme_query = " ( ";
                    for($i=0; $i<count($themelist); $i++) {
                        $theme_id = $themelist[$i]['theme_id'];
                        $theme_query .= " users.theme_id = '". $theme_id. "' ";
                        if($i < count($themelist)-1) {
                            $theme_query .= " OR ";
                        }
                    }
                    $theme_query .= " ) ";
                    $alpha_query .= " AND ". $theme_query;
		
                }
		$alpha_query.=  " ORDER BY users.last_name ";
		
		$result = $this->db->query($alpha_query);
                //echo "alpha query = $alpha_query";
		return $result;
    }
    
        /* get list of people a user is supervisor for */
    public function get_users_for_supervisor($supervisor_id, $types=null) {
        return $this->get_users_by_type(-1, $types, 1, $supervisor_id);
    
    }
    
    public function count_users_for_supervisor($supervisor_id) {
        $query = "select * from users where supervisor_id=$supervisor_id";
        //echo("count query = $query");
        $result = $this->db->query($query);
        //echo("num results= ".count($result));
        return count($result);
    }
/*
supervisor_list

	
	public function supervisor_list($letter) {
		$query = "SELECT user_id, netid, first_name, last_name, themes.short_name as theme 
						FROM users LEFT JOIN themes ON users.theme_id=themes.theme_id 
						WHERE type_id = 1 or type_id = 2 or type_id = 4 or type_id = 5 or type_id = 11 ";
		
		$result = $this->db->query($query);
		return $result;
    }
*/


/*
update
update query
updates given field (as param) with given value (param)
*/
	
	public function update($user_id, $table, $field, $value, $conditional = NULL) {
		$update_val = $this->edit($value);
		$count = $this->num_rows('user_id', $user_id, $table, $conditional);
		if ($count == 0){
			  $add_query = "INSERT INTO ".$table."  (user_id, ".$field." )
							VALUES ('".$user_id."','". $update_val."')";
			  $result = $this->db->insert_query($add_query);
		}
		else{
			$update_query = "UPDATE ".$table." SET ".$field." = '".$update_val."' WHERE user_id = '".$user_id."' ";
			$update_query .= " " .$conditional;
                        //echo("update_query = $update_query <BR>");
			$result = $this->db->non_select_query($update_query);
		}
		$load = $this->get_user($user_id);
                //$this->lastUpdate($table, $_SESSION['username']);
                $updateQuery = "UPDATE ". $table . " set ". $table ."_lastUpdateUser = '". $_SESSION['username']."' where user_id='".$user_id."'";
            //echo("lastUpdate query = $updateQuery");
            $this->db->non_select_query($updateQuery);
		return $result;
    }







	
/*
add_user()
$user_info - array with user info to insert
returns the id number of the new record, 0 if it fails
*/
	public function add_user($first_name, $last_name, $netid, $uin, $email, 
							 $theme_drop, $theme_1_drop, $theme_2_drop, $type_drop, $type_1_drop, $type_2_drop,
							 $dept_drop, $default_address,
							 $start_date, $key_deposit, $prox_card, $safety_training, $gender, $supervisor_id, $admin) {
		$key = $this->is_checked($key_deposit);
		$prox = $this->is_checked($prox_card);
		$safety = $this->is_checked($safety_training);
		$isAdmin = $this->is_checked($admin);
		
		$add_user_query = "INSERT INTO users (first_name, last_name, netid, uin, email,
											  theme_id, theme_1_id, theme_2_id, type_id, type_1_id, type_2_id, dept_id, default_address, start_date, 
											  key_deposit, prox_card, safety_training, gender, supervisor_id, admin, user_time_created)
							VALUES ('".$this->edit($first_name)."','". $this->edit($last_name)."','". $this->edit($netid)."',
									'". $this->edit($uin)."','". $this->edit($email)."',
									'". $this->edit($theme_drop)."','". $this->edit($theme_1_drop)."','".
									$this->edit($theme_2_drop)."',
									'". $this->edit($type_drop)."','". $this->edit($type_1_drop)."','".
									$this->edit($type_2_drop)."','". $this->edit($dept_drop)."',
									'".$this->edit($default_address)."','".$this->edit($start_date)."',	
									'".$key."',	'".$prox."','".$safety."','".$gender."','".$this->edit($supervisor_id)."','".$isAdmin."', NOW()
									)";
		//echo("add_user_query = $add_user_query<BR>");
		$result = $this->db->insert_query($add_user_query);
		return $result;


	}
        
        //
        public function add_user_2($first_name, $last_name, $netid, $uin, $email, 
							 $theme_drop, $theme_1_drop, $theme_2_drop, $type_drop, $type_1_drop, $type_2_drop,
							 $dept_drop, $default_address,
							 $start_date, $key_deposit, $prox_card, $safety_training, $gender, $supervisor_id, $admin) {
            
            $key = $this->is_checked($key_deposit);
		$prox = $this->is_checked($prox_card);
		$safety = $this->is_checked($safety_training);
		$isAdmin = $this->is_checked($admin);
                
            	$add_user_query = "INSERT INTO users (first_name, last_name, netid, uin, email,
							dept_id, default_address, start_date, 
							key_deposit, prox_card, safety_training, gender, supervisor_id, admin, user_time_created)
							VALUES ('".$this->edit($first_name)."','". $this->edit($last_name)."','". $this->edit($netid)."',
									'". $this->edit($uin)."','". $this->edit($email)."',
									'". $this->edit($dept_drop)."',
									'".$this->edit($default_address)."','".$this->edit($start_date)."',	
									'".$key."',	'".$prox."','".$safety."','".$gender."','".$this->edit($supervisor_id)."','".$isAdmin."', NOW()
									)";
                
                
		//echo("add_user_2 query = $add_user_query<BR>");
		$result = $this->db->insert_query($add_user_query);
                
                // Insert into user_theme
                // 'result' should be the id of the new user
                $user_theme_query = "INSERT INTO user_theme(user_id, theme_id, type_id, active, start_date) VALUES ('".
                                            $result. "', '".$this->edit($theme_drop). "', '". $this->edit($type_drop)."', '1', NOW())";
                                            
                //echo("theme query = $user_theme_query <BR>");
                $theme_result = $this->db->insert_query($user_theme_query);
                
                if($theme_1_drop != NULL) {
                    $user_theme_query = "INSERT INTO user_theme(user_id, theme_id, type_id, active, start_date) VALUES ('".
                                            $result. "', '".$this->edit($theme_1_drop). "', '". $this->edit($type_1_drop)."', '1', NOW())";
                                            
                    $theme_1_result = $this->db->insert_query($user_theme_query);
                }
                
                if($theme_2_drop != NULL) {
                    $user_theme_query = "INSERT INTO user_theme(user_id, theme_id, type_id, active, start_date) VALUES ('".
                                            $result. "', '".$this->edit($theme_2_drop). "', '". $this->edit($type_2_drop)."', '1', NOW())";
                                            
                    $theme_2_result = $this->db->insert_query($user_theme_query);
                }

		return $result;
     
        }
        
        public function add_theme($user_id, $theme_id, $type_id) {
            $user_theme_query = "INSERT INTO user_theme(user_id, theme_id, type_id, active, start_date) VALUES ('".
                                            $user_id. "', '".$this->edit($theme_id). "', '". $this->edit($type_id)."', '1', NOW())";
                 //echo("query = $user_theme_query<BR>");                           
                $theme_result = $this->db->insert_query($user_theme_query);
                
                return $theme_result;
        }
        
        public function remove_theme($user_id, $theme_id) {
            $user_theme_query = "UPDATE user_theme set active='0', end_date=NOW() where user_id='$user_id' AND theme_id=$theme_id AND active='1'";
                $this->db->non_select_query($user_theme_query);
        }
        
        /* Gets details for themes a user is in
         * $user_id - the id of the user to get
         * $active - if true, only select active themes, otherwise get all themes the user has ever been in
         */
        public function get_themes($user_id, $active=1) {
            $query = "SELECT user_theme.*, themes.short_name as theme_name, type.name as type_name from user_theme, themes, type where user_theme.user_id=$user_id and user_theme.active=$active and "
                    . "user_theme.theme_id=themes.theme_id and user_theme.type_id=type.type_id";
            //echo("get themes query = $query<BR>");
            $result = $this->db->query($query);
            return $result;
        }
        
        public function change_type($user_id, $theme_id, $type_id) {
            $query = "UPDATE user_theme set type_id = $type_id where user_id=$user_id and theme_id=$theme_id and active=1";
            $this->db->non_select_query($query);
            
        }

/*
add_igb_address()
returns the id number of the new record, 0 if it fails
*/
	public function add_igb_address($user_id, $igb_room) 
	{
		
		$igb_address_query = "INSERT INTO address (user_id, type, address1, address2, city, state, zip)
							VALUES ('".$user_id."','IGB','1206 W. Gregory Dr.','". $this->edit($igb_room)."','Urbana','IL','61801'
									)";
		$result = $this->db->insert_query($igb_address_query);
		return $result;


	}

/*
add_address()
to add dept or home address
returns the id number of the new record, 0 if it fails
*/
	public function add_address($user_id, $address, $country="United States", $fwd=0 ) 
	{

		$add_address_query = "INSERT INTO address (user_id, type, address1, address2, city, state, zip, country, forward)
					VALUES ('".$user_id."','".$this->edit($address['type'])."','".$this->edit($address['address1'])."',
							'". $this->edit($address['address2'])."', '".$this->edit($address['city'])."',
							'".$this->edit($address['state'])."','".$this->edit($address['zip'])."',
							'".$country."','".$fwd."'
							)";
		$result = $this->db->insert_query($add_address_query);
		return $result;


	}

/*
add_phone()
add phone numbers
returns the id number of the new record, 0 if it fails
*/	
	public function add_phone($user_id, $igb_phone, $dept_phone, $cell_phone, $fax, $other_phone = 0, $pager = 0) 
	{
		
		$add_phone_query = "INSERT INTO phone (user_id, igb, dept, cell, fax, other)
							VALUES ('".$user_id."','".$this->edit($igb_phone)."','".$this->edit($dept_phone)."',
									'". $this->edit($cell_phone)."','".$this->edit($fax)."','".$this->edit($other_phone)."'
									)";
                //echo("phone query = ". $add_phone_query);
		$result = $this->db->insert_query($add_phone_query);
		return $result;


	}
	
/*
add_key()
add key entry
returns the id number of the new record, 0 if it fails
*/	
	public function add_key($user_id, $key_id, $date_issued, $paid) 
	{
		
		$add_key_query = "INSERT INTO key_info (user_id, key_id, date_issued, paid)
							VALUES ('".$user_id."','".$key_id."','".$this->edit($date_issued)."',
									'". $this->edit($paid)."'
									)";
		$result = $this->db->insert_query($add_key_query);
		return $result;


	}	
	
	
	
/**
Validate an email address.
Provide email address (raw input)
Returns true if the email address has the email 
address format and the domain exists.
*/
function is_valid_email($email)
{
   $email = trim(rtrim($email));
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if
(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }

   }
   return $isValid;
}


	

/*
is_admin()
verifies input is valid email address
returns TRUE or FALSE
*/	
	public function is_admin($username, $password= NULL) 
	{
		$admin_query = "SELECT user_id FROM users WHERE netid = '".$username."' AND admin = '1' ";
                //echo("admin_query = $admin_query");
		$result = $this->db->query($admin_query);
                //echo("User = ". $result[0]['user_id']);
		return $result[0]['user_id'];

	}
        
	public function is_superadmin($username, $password= NULL) 
	{
		$admin_query = "SELECT user_id FROM users WHERE netid = '".$username."' AND superadmin = '1' ";
                //echo("admin_query = $admin_query");
		$result = $this->db->query($admin_query);
                //echo("User = ". $result[0]['user_id']);
		return $result[0]['user_id'];

	}
        
	public function is_checked($value)
	{
		if ($value == "checked"){
			return 1;
		}
		else{ return 0; }
	}


/////////////////Private Functions///////////////////





/*
edit()
takes in a string and edits to a format suitable to enter into database
*/
	private function edit($string)
	{
		  $result = trim(rtrim($string));
		  //$result = mysql_real_escape_string($result,$this->db->get_link());
                  $result =  mysqli_real_escape_string($this->get_db()->get_link(), $result);
		  return $result;
	
	}



        public function get_permissions($user_id) {
            
            $query = "SELECT theme_id from permissions where user_id='".$user_id."'";
            //echo("query = $query");
            $result = $this->db->query($query);
            return $result;

        }
        
        public function has_permission($user_id, $theme_id) {
            
            if($this->get_admin()) {
                return true;
            }
            
            $permissions = $this->get_permissions($user_id);
            for($i=0; $i<count($permissions); $i++) {
                
                if($permissions[$i]['theme_id'] == $theme_id) {
                    return true;
                }
            }
            return false;
        }
        
        public function add_permission($user_id, $theme_id) {
            $query = "INSERT INTO permissions (user_id, theme_id) VALUES ('". $user_id. "', '". $theme_id. "')";
            $result = $this->db->insert_query($query);
            $lastUpdateQuery = "UPDATE permissions set permissions_lastUpdateUser='".$_SESSION['username'] ."' where user_id='".$user_id. "' AND theme_id='".$theme_id."'";
        }
        
        public function remove_permission($user_id, $theme_id) {
            $query = "DELETE from permissions where user_id='".$user_id. "' AND theme_id='".$theme_id."'";
            $result = $this->db->insert_query($query);
        }

        public function get_current_user_id() {
            $query = "SELECT user_id from users where netid='".$_SESSION['username']."'";
            //echo("query = $query");
            $result = $this->db->query($query);
            //print_r($result);
            $id = $result[0]['user_id'];
            return $id;
        }








}

?>