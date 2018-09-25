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