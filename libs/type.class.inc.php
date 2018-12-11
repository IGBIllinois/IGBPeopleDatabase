<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class type{
    
    private $db;
    
    private $id;
    private $name;
    private $active;
    
    public function __construct($db, $type_id = 0) {
            $this->db = $db;
            if ($type_id != 0) {
                $this->load($type_id);
            }

    }
    public function __destruct() {

    }
    
    public function get_id() { return $this->id; }
    public function get_name() { return $this->name; }
    public function get_active() { return $this->active; }
    
    
    /** Determines if a type already exists with the given name
    * 
    * @param string $name Name of the Type to check for
    * @return boolean ID of the type if it exists, else false
    */
    public function type_name_exists($name) {
        $query = "SELECT type_id from type where ".
                "name = :name";
        $params = array("name"=>$name);

        $result = $this->db->get_query_result($query, $params);

        if(count($result) > 0) {
            return $result[0]['type_id'];
        } else {
            return false;
        }
    }
    
    /** Edit this type
     * 
     * @param string $name New type name
     * @param boolean $active True if this type is active, else false
     */
    public function edit_type($name,$active) {
        $query = "UPDATE type set"
                . " name=:name, "
                . " type_active=:active WHERE "
                . " type_id = :type_id ";

        $params = array("name"=>$name,
                        "active"=>$active,
                        "type_id"=>($this->id));

        $this->db->get_update_result($query, $params);

        $this->name = $name;
        $this->active = $active;
    }    
    
    // static functions
        
    /** Add a new type
     * 
     * @param db $db Database object
     * @param string $name Type name
     * @param boolean $active True if this type is active, else false
     * @return int ID of newly created type
     */
    public static function add_type($db, $name, $active=1) {
        $query = "INSERT INTO type "
                . "(name, type_active) "
                . " VALUES "
                . "(:name, :active)";
        $params = array("name"=>$name, "active"=>$active);

        $result = $db->get_insert_result($query, $params);

        return $result;

    }
    
    /** Returns an array of all types in the database
     * 
     * @param db $db Database object
     * @return \type Array of all types in the database
     */
    public static function get_all_types($db) {
        $select_all_types = "SELECT type_id, name, type_active FROM type "; 
        $result = $db->get_query_result($select_all_types);
        $type_list = array();
        foreach($result as $type) {
            $type = new type($db, $type['type_id']);
            $type_list[] = $type;
        }
        return $type_list;
    }
        
    // Private functions
    private function load($type_id) {
        $query = "SELECT type_id, name, type_active from type where type_id=:type_id limit 1";
        $params = array("type_id"=>$type_id);
        $results = $this->db->get_query_result($query, $params);
        $type = $results[0];
        
        $this->id = $type['type_id'];
        $this->name = $type['name'];
        $this->active = $type['type_active'];
        
    }
            
}

?>