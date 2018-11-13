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
	
	

/** Loads theme data into this Theme object
 * 
 * @param type $theme_id ID of the Theme to load data for.
 */
    public function load($theme_id) {
        $this->id = $theme_id;
        $this->get_theme_id();
        $this->get_theme($theme_id);
    }

/** Loads a Theme from a theme ID
 * 
 * @param int $theme_id ID of the Theme to load into this Theme object
 * @return type
 */
	
	public function get_theme($theme_id) { 
		$theme_query = "SELECT themes.*, users.user_id, CONCAT(users.first_name, ' ', users.last_name) AS theme_leader_name 
						 FROM themes LEFT JOIN users ON themes.leader_id = users.user_id 
						 WHERE themes.theme_id = '".$theme_id."'";
		$result = $this->db->query($theme_query);

		
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
	

	
        /** Returns an array of all Theme data
         * 
         * @return array Array of theme data
         */
	public function get_all_themes() { 
            
                $theme_query = "SELECT theme_id from themes";
                $result = $this->db->get_query_result($theme_query, null);
                $all_themes = array();
                foreach($result as $theme_data) {
                    $theme = new theme($this->db, $theme_data['theme_id']);
                    $all_themes[] = $theme;
                }
                
                return $all_themes;
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
num_rows
returns number of rows where $field = $value in $table
*/		
	public function num_rows($field, $value, $table, $conditional) {
		$exists_query = "SELECT count(*) as count FROM ".$table." WHERE ".$field." = '".$value."'";
		$exists_query .= $conditional;
		$result = $this->db->query($exists_query);
		return $result[0]['count'];
    }
	



	
    /** Updates a field in the Theme
     * 
     * @param int $theme_id ID of the Theme to update
     * @param string $table 
     * @param string $field
     * @param string $value
     * @return type
     */
	public function update($theme_id, $table, $field, $value) {

		$count = $this->num_rows('theme_id', $theme_id, $table, $conditional);
                $params = array("theme_id"=>$theme_id, $field=>$value);
		if ($count == 0){

                    $add_query = "INSERT INTO $table (theme_id, $field) VALUES
                        (:theme_id, :$field)";
                    $result = $this->db->get_insert_result($add_query, $params);
		}
		else{
                    $update_query = "UPDATE $table SET $field = :$field where theme_id=:theme_id ";

                        $result = $this->db->get_update_result($update_query, $params);
		}

		return $result;
    }
    
    
    /** Updates a Theme
     * 
     * @param type $theme_id ID of the Theme to change
     * @param type $theme_name New name for the Theme
     * @param type $theme_short_name New short name for the Theme
     * @param type $theme_leader_id ID of the leader of the Theme
     * @param type $theme_status Theme Status (1 for Active, 0 for Inactive)
     * @return type Number of records changed (should be one)
     */
    public function update_theme($theme_id, $theme_name, $theme_short_name, $theme_leader_id, $theme_status) {
        $query = "UPDATE themes set name=:theme_name, "
                . "short_name=:theme_short_name,"
                . "leader_id=:theme_leader_id,"
                . "theme_active = :theme_status "
                . " WHERE theme_id = :theme_id ";
        $params = array("theme_id"=>$theme_id,
                        "theme_name"=>$theme_name,
                        "theme_short_name"=>$theme_short_name,
                        "theme_leader_id"=>$theme_leader_id,
                        "theme_status"=>$theme_status);
        $result = $this->db->get_update_result($query, $params);
        return $result;
        
                    
    }







	

    /** Adds a new Theme
     * 
     * @param string $name Name of the new Theme
     * @param string $short_name Short name of the new Theme
     * @param int $leader_id ID of the leader of the Tneme
     * @param boolean $status Theme status (1 for Active, 0 for Inactive)
     * @return ID of the newly created Theme
     */
	public function add_theme($name, $short_name, $leader_id, $status) {
		
		$add_query = "INSERT INTO themes (name, short_name, leader_id, theme_active)
					  VALUES ('".$this->edit($name)."','". $this->edit($short_name)."',
							  '". $leader_id."','". $status."'
							  )";
                
                $add_query = "INSERT INTO themes
                    (name, short_name, leader_id, theme_active)
			VALUES (:name, :short_name, :leader_id, :theme_active)";
                $params = array("name"=>$name, 
                    "short_name"=>$short_name, 
                    "leader_id"=>$leader_id, 
                    "theme_active"=>$status);
                        
                $result = $this->db->get_insert_result($add_query, $params);       
                //$result = $this->db->insert_query($add_query);
                
		return $result;


	}
        
// Static Functions
        
        public static function get_themes($db, $active) {
            
            $themes = array();
            
            $query = "SELECT theme_id FROM themes WHERE theme_active = '1' ORDER BY short_name ASC "; 
            $params = array();
            $result = $db->get_query_result($query, $params);
            foreach($result as $theme_data) {
                $theme_id = $theme_data['theme_id'];
                $curr_theme = new theme($db, $theme_id);
                $themes[] = $curr_theme;
                
            }
            return $themes;
        }


/////////////////Private Functions///////////////////





/*
edit()
takes in a string and edits to a format suitable to enter into database
*/
	private function edit($string)
	{
		  $result = trim(rtrim($string));
		  $result = mysqli_real_escape_string($this->db->get_link(), $result);
		  return $result;
	
	}














}

?>
