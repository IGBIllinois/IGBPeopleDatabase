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
        private $exp_grad_date;
        
        /** Array of theme ids that this user is in, and their associated
         * type ids.
         * The array key is the theme id, and the value is the type id
         */
	private $themes;
        
	private $admin;
        private $superadmin;
	private $reason_leaving;
        private $image;
        
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
	
	

        /** Loads user data
         * 
         * @param int $user_id The user id to get data for
         */
        public function load($user_id) {
            $this->id = $user_id;
            $this->get_user($user_id);
        }
	

	/** Gets data from the database and loads it into this user object.
         * 
         * @param int  $user_id The user id to get data for
         * @return type
         */
	public function get_user($user_id) { 
		$profile_query = "SELECT users.*, department.name as dept_name
						 FROM (users
						 LEFT JOIN department ON users.dept_id=department.dept_id)
						 WHERE user_id = :user_id";
                $params = array("user_id"=>$user_id);
		$result = $this->db->get_query_result($profile_query, $params);
                
		$address_query = "SELECT * FROM address 
						WHERE user_id = :user_id";
		$address_list = $this->db->get_query_result($address_query, $params);
		$phone_query = "SELECT * FROM phone 
						WHERE user_id = :user_id";
		$phone_list = $this->db->get_query_result($phone_query, $params);
                
		$supervisor_query = "SELECT first_name, last_name, netid FROM users 
						WHERE user_id = :supervisor_id";
                $params = array("supervisor_id"=>$result[0]['supervisor_id']);
		$supervisor_result = $this->db->get_query_result($supervisor_query, $params);
                 
               $theme_query = "SELECT * from user_theme where user_id=:user_id and active=1";

                $theme_params = array("user_id"=>$user_id);
                $theme_result = $this->db->get_query_result($theme_query, $theme_params);
                $themes = array();
                foreach($theme_result as $theme) {
                    $themes[$theme['theme_id']] = $theme['type_id'];
                    
                }
                $this->themes = $themes;
		
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
                if(count($supervisor_result) == 0) {
                    $this->supervisor_netid = "None";
                    $this->supervisor_name = "No supervisor listed";
                } else {
                    $this->supervisor_netid = $supervisor_result[0]['netid'];
                    $this->supervisor_name = $supervisor_result[0]['first_name']." ".$supervisor_result[0]['last_name'];
                }
                $this->exp_grad_date = $result[0]['expected_grad'];
		
		$this->department = $result[0]['dept_name'];
		$this->dept_id = $result[0]['dept_id'];
                if(count($phone_list) > 0) {
                    $this->igb_phone = $phone_list[0]['igb'];
                    $this->cell_phone = $phone_list[0]['cell'];
                    $this->dept_phone = $phone_list[0]['dept'];
                    $this->other_phone = $phone_list[0]['other'];
                    $this->fax = $phone_list[0]['fax'];
                }
                
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
				$this->home_address= $address_list[$i]['address1'];
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
		//if(!$this->get_status()) {
			return $this->reason_leaving;
		//}
	}
        public function get_exp_grad_date() { return $this->exp_grad_date; }
        
        /** Returns the image uploaded for this user, or a default image if
         *  none exists.
         * 
         * @return string Name of the image file for this user.
         */
        public function get_image() { 

            if($this->image != null) {
                return $this->image; 
            } else {
                return "default.png";
            }
        }
        
        /** 
         * Gets the full URL for the thumbnail image for this user
         * 
         * @return string The URL for the thumbnail image of this user, 
         * or the default image if it is null
         */
        public function get_image_location() { 
            if($this->image != null) {
                           return  IMAGE_DIR_URL . $this->image; 
                          } else {
                           return  IMAGE_DIR_URL . "default.png";   
                          }
        }
        
        /** 
         * Gets the name the large image for this user
         * 
         * @return string The name of the large image of this user, 
         * or the default image if it is null
         */
        public function get_large_image() { 
            
            if($this->image != null) {
                $large_image = $this->image;
                $large_image = str_replace(".", "_large.", $large_image);
                return $large_image; 
            } else {
                return "default.png";
            }
        }
        
        /** 
         * Gets the full URL for the large image for this user
         * 
         * @return string The URL for the large image of this user, 
         * or the default image if it is null
         */
        public function get_large_image_location() { 
            
            if($this->image != null) {
                $large_image = $this->image;
                $large_image = str_replace(".", "_large.", $large_image);
                return  IMAGE_DIR_URL . $large_image; 
            } else {
                return  IMAGE_DIR_URL .  "default.png";   
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
	public function get_home_address1() { return $this->home_address; }
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
        
        /**
         * Returns an array of theme ids this user is in, and their associated
         * type ids.
         * @return array An array of key->value pairs representing the 
         * themes this user is in. 
         * For each element in the array, the key value is the theme id 
         * and the value is the associated type for that theme.
         */
        public function get_themes_array() {return $this->themes; }
        
        /** Returns an array of the ids of the types this user holds.
         * 
         * @return array An array of type ids for the themes this user is in.
         */
        public function get_types() { return array_values($this->themes); }
        
        /** Returns an array of the names
         * of the Types this user has in their Themes.
         * 
         * @return array An array of strings that are the names
         * of the Types this user has in their Themes.
         */
        public function get_type_names() {
            $names =array();
            if($this->themes != null && count($this->themes) > 0) {
            foreach(array_values($this->themes) as $type_id) {
                $curr_type = new type($this->db, $type_id);
                $name = $curr_type->get_name();
                $names[] = $name;
            }
            }
            return $names;
        }
        
        /** Returns an array of the names
         * of the Themes this user is in.
         * 
         * @return array An array of strings that are the short names
         * of the Themes this user is in.
         */
        public function get_theme_short_names() {
            $names =array();
            if($this->themes != null && count($this->themes) > 0) {
            foreach(array_keys($this->themes) as $theme_id) {
                $curr_theme = new theme($this->db, $theme_id);
                $name = $curr_theme->get_short_name();
                $names[] = $name;
            }
            }
            return $names;
        }
        
        /** Determines if this user is in a specific theme
         * 
         * @param int $theme_id The id of the Theme to check
         * @return boolean True if this user is in the given Theme, else false.
         */
        public function is_in_theme($theme_id) {

            if(array_key_exists($theme_id, $this->themes)) {
                return true;
            } else {
                return false;
            }
        }
        
        /** Determines if this user has the given Type in one of their Themes
         * 
         * @param int $type_id The id of the Type to check
         * @return boolean True if this user has the given Type in one
         * of their Themes, else false.
         */
        public function is_type($type_id) {
            if(in_array($type_id, $this->themes)) {
                return true;
            } else {
                return false;
            }
        }
	
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
	
		
        
        public function get_keys($active) { 
		return key_info::get_keys($this->db, $this->id, $active);

        }

        /** Determines if this user has a specfic key
         * 
         * @param type $user_id
         * @param type $key_id
         * @return type
         */
        public function key_exists($key_id) { 
            return key_info::key_exists($this->db, $this->id, $key_id);
        }
	
	
	
/*
num_rows
returns number of rows where $field = $value in $table
*/		
	public function num_rows($field, $value, $table, $conditional, $new_params = null) {
		$exists_query = "SELECT count(*) as count FROM ".$table." WHERE ".$field." = :$field ";
                $params = array($field=>$value);
                if($new_params != null ) {
                    $params = array_merge($params, $new_params);
                }
		$exists_query .= $conditional;
		$result = $this->db->get_query_result($exists_query, $params);
		return $result[0]['count'];
    }
	
	
	
    /** Determines if a specified user exists
     * 
     * @param string $field Database field to check
     * @param string $value Value of the field
     * @param string $conditional Additional conditions
     * @param array $new_params Array of parameters, if any. In the form:
     * array("fieldname"=>$value), where "fieldname" corresponds to a 
     * ":fieldname" in the $conditional argument.
     * @return type
     */
	public function user_exists($field, $value, $conditional = NULL, $new_params = null) {
		$exists_query = "SELECT user_id FROM users WHERE ".$field." = :$field ";
                $params = array($field=>$value);
                if($new_params != null) {
                    $params = array_merge($params, $new_params);
                }
		$exists_query .= $conditional;
		$result = $this->db->get_query_result($exists_query, $params);
		return $result[0]['user_id'];
    }
	

			

    /** Gets a list of forwarding addresses and other data for users
     * who have left iGB.
     * @return array of database data for users who have left IGB
     */
    public function get_forwarding_addresses() {

        $query = "select users.first_name, 
            users.last_name, 
            users.email, 
            address.address1, 
            address.address2, 
            address.city, 
            address.state, 
            address.zip, 
            tlist.t, 
            users.end_date, 
            users.reason_leaving 
            from users 
            join address on 
            address.user_id = users.user_id 
            left join

            (select user_theme.user_id as u_id, group_concat(themes.short_name) as t 
            from user_theme, themes  where 
            user_theme.theme_id=themes.theme_id group by user_theme.user_id) as tlist

            on tlist.u_id = users.user_id

            where address.forward=1 and users.first_name != '' order by users.last_name";
        
        $result = $this->db->get_query_result_assoc($query, null);
        $header = array(array("Firat Name",
            "Last Name",
            "Email",
            "Address 1",
            "Address 2",
            "City",
            "State",
            "Zip",
            "Themes",
            "End Date",
            "Reason Leaving"));
        $result = array_merge($header, $result);

        return $result;
    }
    
    /** Gets s list of people with IGB People Database access
     * 
     * @return array Array of database data for users with
     * IGB People Database Access (Admin, Superadmin, and individual
     * Theme access)
     */
    public function get_database_users() {
        $admin_query = "select users.first_name, "
                . "users.last_name, "
                . "users.netid, "
                . "users.email, "
                . "if((users.superadmin = 1), 'SUPERADMIN', 'ADMIN') as theme_list "
                . "from users "
                . "where users.admin=1 "
                . "or users.superadmin=1 "
                . "order by last_name";
        
        $query = "select users.first_name, "
                . "users.last_name, "
                . "users.netid, "
                . "users.email,"
                . " group_concat(themes.short_name) as theme_list "
                . "from permissions "
                . "left join users on permissions.user_id=users.user_id "
                . "left join themes on themes.theme_id=permissions.theme_id "
                . "where netid is not null "
                . "group by netid "
                . "order by last_name";
        
        $admin_result = $this->db->get_query_result($admin_query, null);
        
        $admin_result = array_merge($admin_result, array(array()));
        $result = $this->db->get_query_result($query, null);
        return array_merge($admin_result, $result);
    }

    /** Gets all users of a specific type in a group
     * 
     * @param int $theme_id ID of the Theme to get users from
     * @param array $types Array of Type IDs for users
     * @param boolean $status Active (1) or Inactive (0) users
     * @param int $supervisor Supervisor ID (or -1 if not grouped by supervisor)
     * @return type
     */
    public function get_users_by_type($theme_id, $types, $status=1, $supervisor=-1) {
        $params = array();
        if($theme_id==0) {
            // if theme_id == 0, get users who have no theme.
            $type_id = $types[0];

            $search_query = "SELECT distinct (users.user_id) as user_id
                    from user_theme 
                    LEFT JOIN users on users.user_id=user_theme.user_id 
                    right join (select * from 
                    (select * from user_theme where 
                    ((end_date is not null and end_date != 0) 
                    or theme_id = 0) and active=1) ut1 
                    where user_id not in 
                    (select user_id from user_theme where 
                    ((end_date is null or end_date = 0) and theme_id != 0) and active=:status) 
                    order by user_id) ut2 
                    on ut2.user_id=user_theme.user_id where user_enabled = 1 order by users.last_name";
            $params['status'] = $status;

        } else {
         if($theme_id > 0) {
            $theme_query = " RIGHT JOIN themes on user_theme.theme_id=themes.theme_id and themes.theme_id=:theme_id and user_theme.active=1";
            $params["theme_id"] = $theme_id;
        } else {
            // Only search themes the user has permissions for.
            if(supervisor_id > 0 || ($user_id != null && !$this->is_admin($_SESSION['username']))) {
                        $themelist = $this->get_permissions($user_id);
                        $theme_query = " RIGHT JOIN themes on user_theme.theme_id=themes.theme_id and user_theme.active=1 and (";
                        for($i=0; $i<count($themelist); $i++) {
                            $curr_theme = "theme_id_$i";
                            $theme_id = $themelist[$i]['theme_id'];
                            $params[$curr_theme] = $theme_id;
                            $theme_query .= " ( themes.theme_id = :$curr_theme) ";
                            if($i < count($themelist)-1) {
                                $theme_query .= " OR ";
                            }
                        }
                        $theme_query .= " ) ";


            } else {
                $theme_query = " RIGHT JOIN themes on user_theme.theme_id=themes.theme_id and user_theme.active=1 ";
            }
        }
        if($types != null) {
        $type_query = "RIGHT JOIN type on (";
        for($i=0; $i<count($types); $i++) {
            $type_id = $types[$i];
            $curr_type = "type_id_$i";
            if($type_id != 0) {
            $type_query .= " (user_theme.type_id=type.type_id and type.type_id=:$curr_type ";
            $params[$curr_type] = $type_id;
             if($theme_id > 0) {
                 if(!array_key_exists("theme_id", $params)) {
                     $params["theme_id"] = $theme_id;
                 }
                 $type_query .= " and user_theme.theme_id=:theme_id ";
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
        
        $search_query = "SELECT DISTINCT (users.user_id) as user_id from user_theme 

            LEFT JOIN users on users.user_id=user_theme.user_id ".
            $theme_query . " ". $type_query;
            

               
        if($status) {
            $search_query .=  " WHERE (users.user_enabled = :status) ";
        } else {
            $search_query .=  ") WHERE (users.user_enabled = :status) ";
        }
        $params['status'] = $status;
        if($supervisor > 0) {
            $params['supervisor'] = $supervisor;
            $search_query .= " AND (users.supervisor_id=:supervisor) ";
        }
        $search_query.=  " ORDER BY users.last_name ";	
        }          
        $result = $this->db->get_query_result($search_query, $params);
	return $result;
    }
	
    /** Find users alphabetically by last name
     * 
     * @param string $letter Letter to get users' last names
     * @param int $user_id ID of current user, to only find users they have
     * permission to see
     * @param boolean $isSupervisor 
     * @return type
     */
	public function alpha_search($letter, $user_id=null, $isSupervisor=0) {

            $params = array("letter"=>$letter);

                $alpha_query = "SELECT * from users ".
                " WHERE SUBSTR(last_name,1,1) = :letter  and users.user_enabled=1 ";
                
                if($user_id != null && !$this->is_admin($_SESSION['username'])) {
                    $themelist = $this->get_permissions($user_id);
                    $theme_query = " ( ";
                    for($i=0; $i<count($themelist); $i++) {
                        $theme_key = "theme_id_$i";
                        $theme_id = $themelist[$i]['theme_id'];
                        $params[$theme_key] = $theme_id;
                        $theme_query .= " user_theme.theme_id = :$theme_key and user_theme.active=1 ";
                        if($i < count($themelist)-1) {
                            $theme_query .= " OR ";
                        }
                    }
                    $theme_query .= " ) ";
                    $alpha_query .= " AND ". $theme_query;
		
                }
		$alpha_query.=  " GROUP BY user_id ORDER BY users.last_name ";
		$result = $this->db->get_query_result($alpha_query, $params);
                
                $user_list = array();
                foreach($result as $user) {
                    $user_list[] = new user($this->db, $user['user_id']);
                }
		return $user_list;
    }
   
    /** Get list of people a user is supervisor for
     * 
     * @param int $supervisor_id ID of the supervisor
     * @param array $types Array of integers that are the type ids to get
     * @return array Array of users for the given supervisor
     */
    
    public function get_users_for_supervisor($supervisor_id, $types=null) {
        return $this->get_users_by_type(-1, $types, 1, $supervisor_id);
    
    }
    
    /** Gets the number of people the given user is supervisor for.
     * 
     * @param type $supervisor_id Id of the supervisor to check
     * @return int The number of people who have the given user
     * listed as their supervisor
     */
    public function count_users_for_supervisor($supervisor_id) {
        $query = "select * from users where supervisor_id=:supervisor_id and user_enabled=1";
        $params = array("supervisor_id"=>$supervisor_id);
        $result = $this->db->get_query_result($query, $params);
        return count($result);
    }


    /** Updates a field for a user
     * 
     * @param int $user_id ID of the user to update
     * @param string $table Database table name to update
     * @param string $field Field in the database to update
     * @param string $value New value for the field
     * @param string $conditional Additional conditions
     * @param array $new_params Array of parameters, if any. In the form:
     * array("fieldname"=>$value), where "fieldname" corresponds to a 
     * ":fieldname" in the $conditional argument.
     * @return type
     */
    public function update($user_id, $table, $field, $value, $conditional = NULL, $new_params = null) {
            $params = array("user_id"=>$user_id, "value"=>$value);
            if($new_params != null ) {
                $params = array_merge($params, $new_params);
            }
            $count = $this->num_rows('user_id', $user_id, $table, $conditional, $new_params);
            if ($count == 0){
                      $add_query = "INSERT INTO $table (user_id, $field)
                                                    VALUES (:user_id, :value)";
                      $result = $this->db->get_insert_result($add_query, $params);
            }
            else{
                    $update_query = "UPDATE $table SET $field = :value WHERE user_id = :user_id ";
                    $update_query .= " " .$conditional;
                    $result = $this->db->get_update_result($update_query, $params);
            }
            $load = $this->get_user($user_id);
            $updateQuery = "UPDATE $table set ". $table ."_lastUpdateUser = '". $_SESSION['username']."' where user_id=:user_id";
            $update_params = array("user_id"=>$user_id);
            $this->db->get_update_result($updateQuery, $update_params);
            return $result;
    }

    
    /** Adds a new User
     * 
     * @param string $first_name User's first name
     * @param string $last_name User's last name
     * @param string $netid Users university netid
     * @param int $uin User's university id number
     * @param string $email User's email address
     * @param int $theme_drop Theme ID from the first drop-down menu
     * @param int $theme_1_drop Theme ID from the second drop-down menu
     * @param int $theme_2_drop Theme ID from the third drop-down menu
     * @param int $type_drop Type ID from the first drop-down menu
     * @param int $type_1_drop Type ID from the second drop-down menu
     * @param int $type_2_drop Type ID from the third drop-down menu
     * @param int $dept_drop Department ID
     * @param string $default_address Default address type
     * @param string $start_date User's starting date
     * @param boolean $key_deposit True if the User has given a key deposit
     * @param boolean $prox_card True if the User has a prox card
     * @param boolean $safety_training True if the User has taken saftey training
     * @param string $gender "M" or "F"
     * @param int $supervisor_id ID of the user's supervisor
     * @param type $admin True if the user is a database admin
     * @return type ID of the newly created user
     */
        public function add_user($first_name, 
                                    $last_name, 
                                    $netid, 
                                    $uin, 
                                    $email, 				
                                    $theme_drop, 
                                    $theme_1_drop, 
                                    $theme_2_drop, 
                                    $type_drop, 
                                    $type_1_drop, 
                                    $type_2_drop,				 
                                    $dept_drop, 
                                    $default_address,
                                    $start_date, 
                                    $key_deposit, 
                                    $prox_card, 
                                    $safety_training, 
                                    $gender, 
                                    $supervisor_id, 
                                    $admin) {
            
                $key = $this->is_checked($key_deposit);
		$prox = $this->is_checked($prox_card);
		$safety = $this->is_checked($safety_training);
		$isAdmin = $this->is_checked($admin);
                
                $params = array(
                    "first_name"=>$first_name,
                    "last_name"=>$last_name,
                    "netid"=>$netid,
                    "uin"=>$uin,
                    "email"=>$email,
                    "dept"=>$dept_drop,
                    "default_address"=>$default_address,
                    "start_date"=>$start_date,
                    "key"=>$key,
                    "prox"=>$prox,
                    "safety"=>$safety,
                    "gender"=>$gender,
                    "supervisor_id"=>$supervisor_id,
                    "admin"=>$isAdmin);
                
                
            	$add_user_query = "INSERT INTO users 
                    (first_name, 
                    last_name, 
                    netid, 
                    uin, 
                    email,
                    dept_id, 
                    default_address, 
                    start_date, 
                    key_deposit, 
                    prox_card, 
                    safety_training, 
                    gender, 
                    supervisor_id, 
                    admin, 
                    user_time_created)
                    VALUES (
                        :first_name,
                        :last_name,
                        :netid,
                        :uin,
                        :email,
                        :dept,
                        :default_address,
                        :start_date,
                        :key,
                        :prox,
                        :safety,
                        :gender,
                        :supervisor_id,
                        :admin,
                        NOW())";
   
		$result = $this->db->get_insert_result($add_user_query, $params);
                

                // Insert into user_theme
                // 'result' should be the id of the new user
                $params = array("result"=>$result,
                    "theme_id"=>$theme_drop,
                    "type_id"=>$type_drop);
                $user_theme_query = "INSERT INTO user_theme "
                        . "(user_id, "
                        . "theme_id, "
                        . "type_id, "
                        . "active, "
                        . "start_date) "
                        . "VALUES (".
                        ":result,"
                        . ":theme_id,"
                        . ":type_id,"
                        . "1,"
                        . "NOW())";
                        
                                            
                $theme_result = $this->db->get_insert_result($user_theme_query, $params);
                
                if($theme_1_drop != NULL && $theme_1_drop != 0) {
                    $params = array("result"=>$result,
                    "theme_id"=>$theme_1_drop,
                    "type_id"=>$type_1_drop);
                                            
                    $theme_1_result = $this->db->get_insert_result($user_theme_query, $params);
                }

                if($theme_2_drop != NULL && $theme_2_drop != 0) {
                    $params = array("result"=>$result,
                    "theme_id"=>$theme_2_drop,
                    "type_id"=>$type_2_drop);

                    $theme_2_result = $this->db->get_insert_result($user_theme_query, $params);
                }

		return $result;
     
        }
        
        /** Add a user to a theme
         * 
         * @param int $user_id ID of the user to add
         * @param int $theme_id ID of the theme to add the user to
         * @param int $type_id ID of the Type the user will be within the Theme
         * @return int ID of the entry into the theme_result table
         */
        public function add_theme($user_id, $theme_id, $type_id) {
            $params = array("user_id"=>$user_id, "theme_id"=>$theme_id, "type_id"=>$type_id);
            $user_theme_query = "INSERT INTO user_theme"
                    . "(user_id, theme_id, type_id, active, start_date) "
                    . "VALUES "
                    . "(:user_id, :theme_id, :type_id, '1', NOW())";
                $theme_result = $this->db->get_insert_result($user_theme_query, $params);
                
                return $theme_result;
        }
        
        /** Removes a user from a theme
         * 
         * @param type $user_id ID of the user to remove
         * @param type $theme_id ID of the theme to remove the user from
         */
        public function remove_theme($user_id, $theme_id) {
            $params = array("user_id"=>$user_id, "theme_id"=>$theme_id);
            $user_theme_query = "UPDATE user_theme set active='0', end_date=NOW() "
                    . "where user_id=:user_id AND theme_id=:theme_id AND active='1'";
            $this->db->get_update_result($user_theme_query, $params);
        }
        
        /* Gets details for themes a user is in
         * $user_id - the id of the user to get
         * $active - if true, only select active themes, otherwise get all themes the user has ever been in
         */
        public function get_themes($user_id, $active=1) {
            $query = "SELECT user_theme.*, "
                    . "themes.short_name as theme_name, "
                    . "type.name as type_name from user_theme, themes, type "
                    . "where user_theme.user_id=:user_id "
                    . "and user_theme.active=:active and "
                    . "user_theme.theme_id=themes.theme_id "
                    . "and user_theme.type_id=type.type_id";

            $params = array("user_id"=>$user_id, "active"=>$active);
            $result = $this->db->get_query_result($query, $params);
            return $result;
        }
        
        /** Change the Type of a user in a theme
         * 
         * @param type $user_id ID of the User 
         * @param type $theme_id ID of the Theme
         * @param type $type_id ID of the new Type 
         */
        public function change_type($user_id, $theme_id, $type_id) {
            $params = array("user_id"=>$user_id, "theme_id"=>$theme_id, "type_id"=>$type_id);
            $query = "UPDATE user_theme set type_id = :type_id where user_id=:user_id "
                    . "and theme_id=:theme_id and active=1";
            $this->db->get_update_result($query, $params);
            
        }


        /** Adds an IGB room number to a user
         * 
         * @param int $user_id The user to add the room of
         * @param string $igb_room The Room number
         * @return int ID of the newly created database entry
         */
	public function add_igb_address($user_id, $igb_room) 
	{
		$params = array("user_id"=>$user_id, "igb_room"=>$igb_room);
		$igb_address_query = "INSERT INTO address 
                    (user_id,
                    type, 
                    address1, 
                    address2, 
                    city, 
                    state, 
                    zip)				
                    VALUES (:user_id,'IGB','1206 W. Gregory Dr.',:igb_room,'Urbana','IL','61801'
                    )";
		$result = $this->db->get_insert_result($igb_address_query, $params);
		return $result;


	}

        /**
         * Adds an address for this user.
         * @param int $user_id ID of the User
         * @param array $address Address array in the form:
         *      [type=>address_type ("IGB", "Home", Dept."),
         *       address1=>address line 1
         *       address2=>address line 2)]
         * @param type $country
         * @param type $fwd
         * @return type
         */
	public function add_address($user_id, $address, $country="United States", $fwd=0 ) 
	{

            $params = array("user_id"=>$user_id, 
                "address_type"=>$address['type'],
                "address1"=>$address['address1'],
                "address2"=>$address['address2'],
                "city"=>$address['city'],
                "state"=>$address['state'],
                "zip"=>$address['zip'],
                "country"=>$country, 
                "fwd"=>$fwd);
            
		$add_address_query = 
                        "INSERT INTO address 
                            (user_id, 
                            type, 
                            address1, 
                            address2, 
                            city, 
                            state, 
                            zip, 
                            country, 
                            forward)
				VALUES 
                                (:user_id,
                                :address_type,
                                :address1,
                                :address2,
                                :city,
                                :state,
                                :zip,
                                :country,
                                :fwd)";
		$result = $this->db->get_insert_result($add_address_query, $params);
		return $result;


	}


        /** Add a user's phone data to the database
         * 
         * @param int $user_id ID of user to add phone data to
         * @param string $igb_phone IGB Phone number
         * @param string $dept_phone Department phone number
         * @param string $cell_phone Cell phone number
         * @param string $fax Fax number
         * @param string $other_phone Other phone number
         * @return int ID of newly created database entry
         */
	public function add_phone($user_id, $igb_phone, $dept_phone, $cell_phone, $fax, $other_phone = 0) 
	{

		$params = array("user_id"=>$user_id, 
                    "igb_phone"=>$igb_phone, 
                    "dept_phone"=>$dept_phone,
                    "cell_phone"=>$cell_phone,
                    "fax"=>$fax,
                    "other_phone"=>$other_phone,
                    "update_user"=>$this->get_netid());

		$add_phone_query = "INSERT INTO phone 
                    (user_id, 
                    igb, 
                    dept, 
                    cell, 
                    fax, 
                    other,
                    phone_lastUpdateUser,
                    phone_lastUpdateTime)				
                        VALUES (
                        :user_id,
                        :igb_phone,
                        :dept_phone,
                        :cell_phone,
                        :fax,
                        :other_phone,
                        :update_user,
                        NOW())";

                try {
		$result = $this->db->get_insert_result($add_phone_query, $params);
                

                } catch(Exception $e) {
                    echo($e->getTraceAsString());
                }
                
		return $result;

	}
	
        
        /** Adds user to the key to the user
         * 
         * @param int $user_id The ID of the user to add a key for
         * @param int $key_id The ID of the key
         * @param string $date_issued Date the key was issued
         * @param boolean $paid True if the user paid deposit, else false
         * @return int The ID of the newly created entry
         */
	public function add_key($user_id, $key_id, $date_issued, $paid) 
	{
		$params = array("user_id"=>$user_id, 
                    "key_id"=>$key_id, 
                    "date_issued"=>$date_issued, 
                    "paid"=>$paid);
                
		$add_key_query = "INSERT INTO key_info 
                    (user_id, 
                    key_id, 
                    date_issued, 
                    paid)
                        VALUES (
                        :user_id,
                        :key_id,
                        :date_issued,
                        :paid)";

		$result = $this->db->get_insert_result($add_key_query, $params);
		return $result;


	}	
	
	
	
/**
Validate an email address.
Provide email address (raw input)
Returns true if the email address has the email 
address format and the domain exists.
*/
public function is_valid_email($email)
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


	

        /** Determines if a User is a database administrator
         * 
         * @param string $username The username of the user to check
         * 
         * @return Id of the user if it exists and is an admin, else false
         */
	public function is_admin($username) 
	{
                $params = array("netid"=>$username);
		$admin_query = "SELECT user_id FROM users WHERE netid = :netid AND admin = '1' ";
		$result = $this->db->get_query_result($admin_query, $params);
                if(count($result) > 0) {
                    return $result[0]['user_id'];
                } else {
                    return false;
                }

	}
        
        /** Determines if a User is a database superadministrator
        * 
        * @param string $username The username of the user to check
        * 
        * @return boolean True if this user is a database superadmin, else false
        */
	public function is_superadmin($username) 
	{
		$admin_query = "SELECT user_id FROM users WHERE netid = :username AND superadmin = '1' ";
                $params = array("username"=>$username);
		$result = $this->db->get_query_result($admin_query, $params);

		return $result[0]['user_id'];

	}
        
	public function is_checked($value)
	{
		if ($value == "checked"){
			return 1;
		}
		else{ return 0; }
	}


        /** Gets a list of theme ids a user has permissions for
         * 
         * @param int $user_id The user to get permissions for
         * @return array An array of theme ids this user has permsssions to edit
         */
        public function get_permissions($user_id) {
            if($user_id == "") {
                return null;
            }
            $query = "SELECT theme_id from permissions where user_id=:user_id";
            $params = array("user_id"=>$user_id);
            $result = $this->db->get_query_result($query, $params);
            return $result;

        }
        
        /** Determines if a user has permission to edit a theme
         * 
         * @param int $user_id The ID of the user to check
         * @param int $theme_id The ID of the theme
         * @return boolean True if the user has permissions to edit the theme
         * (or is admin or superadmin)
         */
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
        
        /** Adds permission for a user to edit a theme
         * 
         * @param int $user_id The user ID to add permissions to
         * @param int $theme_id The theme ID to add permissions for
         */
        public function add_permission($user_id, $theme_id) {
            $params = array("user_id"=>$user_id, "theme_id"=>$theme_id);
            $query = "INSERT INTO permissions (user_id, theme_id) VALUES (:user_id, :theme_id)";
            $result = $this->db->get_insert_result($query, $params);
            $update_params = array("user_id"=>$user_id, 
                                    "theme_id"=>$theme_id, 
                                    "lastUpdateUser"=>$_SESSION['username'] );
            $lastUpdateQuery = "UPDATE permissions set permissions_lastUpdateUser=:lastUpdateUser "
                    . "where user_id=:user_id AND theme_id=:theme_id";
            $update_result = $this->db->get_update_result($lastUpdateQuery, $update_params);
        }
        
        /** Removes permission for a user to edit a theme
         * 
         * @param int $user_id The user ID to add permissions to
         * @param int $theme_id The theme ID to add permissions for
         */
        public function remove_permission($user_id, $theme_id) {
            $params = array("user_id"=>$user_id, "theme_id"=>$theme_id);
            $query = "DELETE from permissions where user_id=:user_id AND theme_id=:theme_id";
            $result = $this->db->get_insert_result($query, $params);
        }

        /** Gets the ID of the currently logged-in user
         * 
         * @return int The ID of the currently logged-in user
         */
        public function get_current_user_id() {
            $query = "SELECT user_id from users where netid=:netid";
            $params = array("netid"=>$_SESSION['username']);
            $result = $this->db->get_query_result($query, $params);

            $id = $result[0]['user_id'];
            return $id;
        }

        /** Deletes a user's image file
         * 
         */
        public function delete_current_image() {
            if($this->get_image() != DEFAULT_IMAGE ) {
                $current_image_file = LOCAL_IMAGE_DIR . $this->get_image();
                $current_large_image_file = LOCAL_IMAGE_DIR . $this->get_large_image();
                $orig_image_filename = $filename_orig= str_replace(".", "_orig.", $this->get_image());
                $current_orig_image_file = LOCAL_IMAGE_DIR . $orig_image_filename;
                if(file_exists($current_image_file)) {
                    unlink($current_image_file);
                }
                if(file_exists($current_large_image_file)) {
                    unlink($current_large_image_file);
                }
                if(file_exists($current_orig_image_file)) {
                    unlink($current_orig_image_file);
                }
                $result = $this->update($this->get_user_id(), 'users', 'image_location', NULL);
            }
        }
        
        public function get_theme_history($all_users = false) {
            $query = "SELECT users.first_name as first_name, 
                users.last_name as last_name, 
                themes.name as theme_name,
                user_theme.start_date as start_date, 
                user_theme.end_date as end_date 
                from       
                (users LEFT JOIN 
                    user_theme on users.user_id = user_theme.user_id)
                LEFT JOIN themes on 
                user_theme.theme_id=themes.theme_id 
                where (user_theme.theme_id is not null and user_theme.theme_id != 0) 
                and ". 
                    (($all_users == true) ? " " : " users.user_id=:user_id and") . 
                    " (user_theme.start_date is not null "
                    . "or user_theme.end_date is not null) "
                    . "order by active, user_theme.start_date";
            
            $params = array("user_id"=>$this->id);
            
            return $this->db->get_query_result($query, $params);

        }
        
        // Static functions
        
        /**
         * 
         * @param db $db Database object
         * @param int $active 0 = Inactive, 1 = Active, 2 = Both
         * @param string $value Value in the search field
         * @param int $user_id User ID of the currently logged in user
         * @param array $filters Array of fields to check ("phone", "department", etc.)
         * @param int $theme_id Theme ID to limit search to
         * @param int $type_id User Type ID to limit search to
         * @param string $start_date Start date to limit search to
         * @param string $end_date End date to limit search to
         * @param string $supervisor Search for users under this supervisor
         * @return \user Array of users that fit the search criteria
         */
        public static function search(  $db, 
                                        $active, 
                                        $value, 
                                        $user_id=null, 
                                        $filters, 
                                        $theme_id=0, 
                                        $type_id=0, 
                                        $start_date=0, 
                                        $end_date=0, 
                                        $supervisor="") {
            

            $query = "";
            $start_query = "SELECT DISTINCT users.* from users ";
            $theme_query = "";
            
            $search_arr = explode(" ", $value);
            $params = array();

            $search_query = "";
            
            for($i=0; $i<count($search_arr); $i++) {
                
                $var_name = "search_value_".$i;
                $var = $search_arr[$i];
            

                $params[$var_name] = "%".$var."%";
            
                $search_query.=  " ( first_name LIKE :$var_name 
                                        OR last_name LIKE :$var_name
                                        OR netid LIKE :$var_name							
                                        OR uin LIKE :$var_name
                                        OR email LIKE :$var_name 
                                        ) ";
                
                
                 if (($i+1) != count($search_arr)){
                         $search_query.= " AND ";
                 }
            }
            
            if($supervisor != null && $supervisor != "") {
                $params["supervisor"] = $supervisor;
                
                if($search_query != "") {
                    $search_query .= " AND ";
                }
                $search_query .= " supervisor_id=(SELECT user_id from users where netid=:supervisor) ";
            }
                
            if($active != 2) {
                if($search_query != "") {
                    $search_query .= " AND ";
                }
                $search_query .= ( " user_enabled = :active");
                $params['active'] = $active;
            }
            
            $this_user = new user($db, $user_id);
            if($user_id == null) {
                // User not in database, return nothing
                return array();
            }
            if($user_id != null && !$this_user->get_admin()) {
                // only get themes this user has permission for.
                    $themelist = $this_user->get_permissions($user_id);
  
                    $theme_query = " RIGHT JOIN user_theme on ((user_theme.user_id = users.user_id) AND (";
                    
                    for($i=0; $i<count($themelist); $i++) {
                        $theme_key = "theme_key_".$i;
                        $my_theme_id = $themelist[$i]['theme_id'];
                        $params[$theme_key] = $my_theme_id;
                        $theme_query .= " ( user_theme.theme_id = :$theme_key and user_theme.active=1) ";
                        if($i < count($themelist)-1) {
                            $theme_query .= " OR ";
                        }
                    }
                    $theme_query .= "))";

		
                }
            
            $query = $start_query . $theme_query . " WHERE " . $search_query;

                 $query .= " ORDER BY last_name";
                 
                 $result = $db->get_query_result($query, $params);

                 $users = array();
                 foreach($result as $curr_user) {
                    $users[] = new user($db, $curr_user['user_id']);
                }
                $num_users = count($users);
                for($i=0; $i<$num_users; $i++) {
                    $u = $users[$i];

                    if($theme_id != null && $theme_id != 0 && !$u->is_in_theme($theme_id)) {

                        unset($users[$i]);
                        continue;
                    }
                    
                    if(($type_id != null) && ($type_id != 0) && !$u->is_type($type_id)) {

                        unset($users[$i]);
                        continue;
                    }
                    
                    if(($start_date != 0) && 
                            ($u->get_start_date() < $start_date ||
                            $u->get_start_date() > $end_date)) {

                        unset($users[$i]);
                        continue;
                    }

                if(array_key_exists("phone", $filters) && $filters["phone"] != null) {
                    
                    $user_phone = $u->get_igb_phone();

                    if((stristr("$user_phone", $filters["phone"]) === FALSE)) {
                        unset($users[$i]);
                        continue;
                    }
                
                }
                if(array_key_exists("dept", $filters) && $filters["dept"] != 0) {
                    
                    $user_dept = $u->get_dept_id();

                    if((stristr("$user_dept", $filters["dept"]) === FALSE)) {
                        unset($users[$i]);
                        continue;
                    }
                
                }
                
                if(array_key_exists("room", $filters) && $filters["room"] != null) {
                    
                    $user_room = $u->get_igb_room();

                    if((stristr("$user_room", $filters["room"]) === FALSE)) {
                        unset($users[$i]);
                        continue;
                    }
                
                }
            
                }
                return $users;

        }
        
        /** Returns a list of Theme Leaders
         * 
         * @param db $db Database object
         * @return \user An array of users who are Theme Leaders
         */
        public static function get_theme_leaders($db) {
            $query = "SELECT DISTINCT users.user_id as user_id
                FROM users 
                LEFT JOIN user_theme on user_theme.user_id = users.user_id 
                LEFT JOIN type on user_theme.type_id = type.type_id 
                WHERE type.name='Theme Leader' order by users.last_name";
            
            $result = $db->get_query_result($query);

            $leaders = array();
            
            foreach($result as $leader) {
                
                $new_leader = new user($db, $leader['user_id']);
                $leaders[] = $new_leader;
            }
            
            return $leaders;
        }

}


