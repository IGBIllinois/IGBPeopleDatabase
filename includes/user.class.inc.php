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
	private $first_name;	
	private $last_name;
	private $netid;
	private $uin;
	
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
exists
searches to see if exact value already exists in specified field
returns user_id in which it exists
*/		
	public function exists($field, $value) {
		$exists_query = "SELECT user_id FROM users WHERE ".$field." = '".$value."'";
		$result = $this->db->query($exists_query);
		return $result;
    }
	
/*
search_all
simple search query
searches values inputed into generic text input and checks to see if those values exist in following fields

first_name
last_name
netid
uin
email


*/	
	public function search_all($value) {
		$search_arr = explode(" ",$value);
		$search_query = "SELECT user_id, first_name, last_name, netid, uin, email, 
						 themes.short_name as theme_name, type.name as type_name 
						 FROM (users LEFT JOIN themes ON users.theme_id=themes.theme_id)
						 LEFT JOIN type ON users.type_id=type.type_id
						 WHERE ";
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
		$result = $this->db->query($search_query);
		return $result;
    }
			
/*
adv_searvh
advanced search query
searches values inputed into generic text input and checks to see if those values exist in following fields
limits results to include only the matching results to advanced search query

first_name
last_name
netid
uin
email

param $filters = array with theme_id, type_id, dept_id, igb_room, igb_phone

*/	
	public function adv_search($value, $filters = 0) {
		$search_arr = explode(" ",$value);
		$search_query = "SELECT user_id, first_name, last_name, netid, uin, email, 
						 themes.short_name as theme_name, type.name as type_name 
						 FROM (users LEFT JOIN themes ON users.theme_id=themes.theme_id)
						 LEFT JOIN type ON users.type_id=type.type_id
						 WHERE ";
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
		echo $search_query;
		$result = $this->db->query($search_query);
		return $result;
    }
		
/*
search
simple search query
searches values inputed into generic text input and checks to see if those values exist in specified field
*/
	
	public function search($field, $value) {
		$search_arr = explode(" ",$value);
		$search_query = "SELECT user_id FROM users WHERE ".$field." LIKE '%".$search_arr[0]."%'";
		$i=1;
		while( $i < count($search_arr))
    	{
			$search_query.=  " OR WHERE ".$field." LIKE '%".$search_arr[$i]."%'";
			$i ++;
		}
		$result = $this->db->query($exists_query);
		return $result;
    }

/*
load user & values
*/

	public function load($user_id) {
        $this->id = $user_id;
        $this->get_user_id();
    }
	
	public function get_user_id() { return $this->id; }
	public function get_email() { return $this->email; }
	public function get_name() { return $this->name; }
	
	
	
/*
edit()
takes in a string and edits to a format suitable to enter into database
*/
	public function edit($string)
	{
		  $result = trim(rtrim($string));
		  $result = mysql_real_escape_string($result,$this->db->get_link());
		  return $result;
	
	}



	

//add_user()
//$user_info - array with user info to insert
//returns the id number of the new record, 0 if it fails
	public function add_user($first_name, $last_name, $netid, $uin, $email, 
							 $theme_drop, $other_theme_drop, $type_drop, $dept_drop, $default_address,
							 $start_date, $key_deposit, $prox_card, $safety_training) {
		$key = $this->is_checked($key_deposit);
		$prox = $this->is_checked($prox_card);
		$safety = $this->is_checked($safety_training);
		
		$add_user_query = "INSERT INTO users (first_name, last_name, netid, uin, email,
											  theme_id, other_theme_id, type_id, dept_id, default_address, start_date, 
											  key_deposit, prox_card, safety_training)
							VALUES ('".$first_name."','". $last_name."','". $netid."','". $uin."','". $email."',
									'". $theme_drop."','". $other_theme_drop."','". $type_drop."','". $dept_drop."',
									'".$default_address."',	'".$start_date."',	'".$key."',	
									'".$prox."','".$safety."'
									)";
		$result = $this->db->insert_query($add_user_query);
		return $result;


	}

//add_igb_address()
//returns the id number of the new record, 0 if it fails
	public function add_igb_address($user_id, $igb_room) 
	{
		
		$igb_address_query = "INSERT INTO address (user_id, type, address1, address2, city, state, zip)
							VALUES ('".$user_id."','IGB','1206 W. Gregory Dr.','". $igb_room."','Urbana','IL','61801'
									)";
		$result = $this->db->insert_query($igb_address_query);
		return $result;


	}

	//add_address()
	//to add dept or home address
	//returns the id number of the new record, 0 if it fails
	public function add_address($user_id, $address) 
	{
		
		$add_address_query = "INSERT INTO address (user_id, type, address1, address2, city, state, zip)
							VALUES ('".$user_id."','".$address['type']."','".$address['address']."','". $address['address2']."',
									'".$address['city']."','".$address['state']."','".$address['zip']."'
									)";
		$result = $this->db->insert_query($add_address_query);
		return $result;


	}


	//add_phone()
	//add phone numbers
	//returns the id number of the new record, 0 if it fails
	
	public function add_phone($user_id, $igb_phone, $dept_phone, $cell_phone, $fax, $other_phone = 0) 
	{
		
		$add_phone_query = "INSERT INTO phone (user_id, igb, dept, cell, fax, other)
							VALUES ('".$user_id."','".$igb_phone."','".$dept_phone."','". $cell_phone."',
									'".$fax."','".$other_phone."'
									)";
		$result = $this->db->insert_query($add_phone_query);
		return $result;


	}
	



	/////////////////Private Functions///////////////////


	private function is_checked($value)
	{
		if ($value == "checked"){
			return 1;
		}
		else{ return 0; }
	}

















}

?>
