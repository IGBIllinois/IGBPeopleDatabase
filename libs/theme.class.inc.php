<?php
//////////////////////////////////////////
//
//	theme.class.inc.php
//
//
//	By Crystal Ahn
//	June 2011
//
//////////////////////////////////////////



class theme{

	////////////////Private Variables//////////

	private $db; //mysql database object
	private $id;
	private $status; 	
	private $name;
	private $short_name;	
	private $leader_id;
	private $leader_name;
	private $num_members;
	
	//////////////// Variables//////////

	

	////////////////Public Functions///////////

	public function __construct($db, $theme_id = 0) {
		$this->db = $db;
		if ($theme_id != 0) {
				$this->load($theme_id);
		}

	}
	public function __destruct() {

	}
	
	
/*
load theme id
*/

	public function load($theme_id) {
        $this->id = $theme_id;
        $this->get_theme_id();
        $this->set_theme($theme_id);
    }

/*
load theme values
*/


//$select_theme_leaders = "SELECT user_id, CONCAT(first_name, ' ', last_name) AS full_name, netid FROM users where type_id = '11' "; 
	
	public function get_theme($theme_id) { 
		$theme_query = "SELECT themes.*, users.user_id, CONCAT(users.first_name, ' ', users.last_name) AS theme_leader_name 
						 FROM themes LEFT JOIN users ON themes.leader_id = users.user_id 
						 WHERE themes.theme_id = '".$theme_id."'";
		$result = $this->db->query($theme_query);
		echo $theme_query;
		
		$stats_query = "SELECT count(1) as count FROM users
						WHERE theme_id = '".$theme_id."' 
						OR other_theme_id =  '".$theme_id."'  ";
		$theme_stats = $this->db->query($stats_query);

			
		$this->status = $result[0]['theme_active'];	
		$this->name = $result[0]['name'];		
		$this->short_name = $result[0]['short_name'];
		$this->leader_id = $result[0]['leader_id'];
		$this->leader_name = $result[0]['theme_leader_name'];
		$this->num_members = $theme_stats[0]['count'];

		
		
		return $result;
	}
/*
get functions
returns values of specific theme
*/	

	//theme info
	public function get_theme_id() { return $this->id; }
	public function get_name() { return $this->name; }
	public function get_short_name() { return $this->short_name; }
	public function get_leader_id() { return $this->leader_id; }
	public function get_leader_name() { return $this->leader_name; }
	public function get_num_members() { return $this->num_members; }

	
	//booleans
	public function get_status() { return $this->status; }
	

	
/*
get all themes
*/
	
	
	public function get_all_themes() { 
		$theme_query = "SELECT themes.*, users.user_id, CONCAT(users.first_name, ' ', users.last_name) AS theme_leader_name 
						 FROM themes LEFT JOIN users ON themes.leader_id = users.user_id ";
		$result = $this->db->query($theme_query);	
		return $result;
	}
	
	
/*
get active theme names
*/
	
	
	public function get_theme_names() { 
		$theme_query = "SELECT theme_id, name
						 FROM themes 
						 WHERE theme_active = '1' ";
		$result = $this->db->query($theme_query);	
		return $result;
	}
		
	
	
	
	
/*
user_exists
searches to see if exact value already exists in specified field in user table
returns user_id in which it exists
	
	public function user_exists($field, $value, $conditional = NULL) {
		$exists_query = "SELECT user_id FROM users WHERE ".$field." = '".$value."' ";
		$exists_query .= $conditional;
		$result = $this->db->query($exists_query);
		return $result[0]['user_id'];
    }
	
*/	
	
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
update
update query
updates given field (as param) with given value (param)
*/
	
	public function update($theme_id, $table, $field, $value, $conditional = NULL) {
		$update_val = $this->edit($value);
		$count = $this->num_rows('theme_id', $theme_id, $table, $conditional);
		if ($count == 0){
			  $add_query = "INSERT INTO ".$table."  (theme_id, ".$field." )
							VALUES ('".$theme_id."','". $update_val."')";
			  $result = $this->db->insert_query($add_query);
		}
		else{
			$update_query = "UPDATE ".$table." SET ".$field." = '".$update_val."' WHERE theme_id = '".$theme_id."' ";
			$update_query .= $conditional;
			$result = $this->db->query($update_query);
		}
		//$load = $this->get_theme($theme_id);
		return $result;
    }







	
/*
add_theme()
returns the id number of the new record, 0 if it fails
*/
	public function add_theme($name, $short_name, $leader_id, $status) {
		
		$add_query = "INSERT INTO themes (name, short_name, leader_id, theme_active)
					  VALUES ('".$this->edit($name)."','". $this->edit($short_name)."',
							  '". $leader_id."','". $status."'
							  )";
		$result = $this->db->insert_query($add_query);
		return $result;


	}



/////////////////Private Functions///////////////////





/*
edit()
takes in a string and edits to a format suitable to enter into database
*/
	private function edit($string)
	{
		  $result = trim(rtrim($string));
		  $result = mysql_real_escape_string($result,$this->db->get_link());
		  return $result;
	
	}














}

?>
